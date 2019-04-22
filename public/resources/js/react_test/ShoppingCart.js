import React, {Component} from 'react';
import {findDOMNode} from 'react-dom';
import $ from 'jquery';
import 'whatwg-fetch';

export default class ShoppingCart extends Component{
	constructor(props){
		super(props);

		this.state = {
			cartProducts: [],
			totalSum: 0		
		};

		this.getCartProducts = this.getCartProducts.bind(this);
		this.addProduct = this.addProduct.bind(this);
		this.changeQuantityHandler = this.changeQuantityHandler.bind(this);
		this.searchProduct = this.searchProduct.bind(this);
		this.searchProductIndex = this.searchProductIndex.bind(this);
		this.removeItem = this.removeItem.bind(this);
		this.calculateSum = this.calculateSum.bind(this);
		this.modifySum = this.modifySum.bind(this);
		this.toggleCart = this.toggleCart.bind(this);
	}

	//function that calculates the total sum of the products in the shopping cart
	calculateSum(products){
		var sum = 0;

		for (var i = products.length - 1; i >= 0; i--) {
			sum += (products[i].final_price * products[i].picked_quantity);
		}

		return sum;
	}

	modifySum(product, operation){
		var currentSum = this.state.totalSum;

		console.log(product);

		if(operation === '+')
			currentSum += (product.final_price*product.picked_quantity);
		else if (operation === '-')
			currentSum -= (product.final_price*product.picked_quantity);

		console.log(currentSum);

		return currentSum;
	}

	//function that gets all the cart product from the back-end
	getCartProducts(){
		fetch('cartContents',{
			method: 'get',
  			credentials: 'include'
		})
		.then(function(response){
			return response.json();
		})
		.then(data => {
			const products = data, currentSum = this.calculateSum(products);
			console.log(products);

			this.setState({
				cartProducts: products,
				totalSum: currentSum
			});
		});

		/*var xhttp = new XMLHttpRequest();

		xhttp.onreadystatechange = () =>{
			if(xhttp.readyState === 4 && xhttp.status === 200){
				console.log(xhttp.responseText);
			}
		}

		xhttp.open('get', '/EShop/public/cartContents', true);
		xhttp.send();*/
	}

	//function that does the animations for toggling the shopping cart
	toggleCart(){
		if(!$('.cart-contents').data('opened')){

			$('.cart-contents').data('opened', true);

			$(".toggle-cart").removeClass("glyphicon-menu-up", 200, function(){
				$(".toggle-cart").addClass("glyphicon-menu-down", 200);
			});

			$(".cart-contents").animate({
				maxHeight: $('.cart-contents').data('maxheight')

			}, 400, function(){
				$(".cart-contents").css("overflow-y", "auto");
				$('#cartDragBar').css('cursor', 'row-resize');
			});
			
		}
		else{
			
			$('.cart-contents').data('opened', false);
			$(".toggle-cart").removeClass("glyphicon-menu-down", 200, function(){
				$(".toggle-cart").addClass("glyphicon-menu-up", 200);
			});
			$(".cart-contents").animate({
				maxHeight: "55px",
				scrollTop: 0

			}, 400, function(){
				$(".cart-contents").css("overflow-y", "hidden");
				$('#cartDragBar').css('cursor', 'default');
			});
		}

	}

	//function that search for a product in the shopping cart by its id
	//returns the product object if found, false - otherwise
	searchProduct(id){
		for (var i = this.state.cartProducts.length - 1; i >= 0; i--) {
			if(id === this.state.cartProducts[i].id)
				return this.state.cartProducts[i];
		}

		return false;
	}

	//function that search for a product in the shopping cart by its id
	//returns the product index in the array if found, false - otherwise
	searchProductIndex(id){
		for (var i = this.state.cartProducts.length - 1; i >= 0; i--) {
			if(id === this.state.cartProducts[i].id)
				return i;
		}

		return false;
	}

	addProduct(product){

		fetch('addToCart',{
			method: 'post',
  			headers: {
			    'Accept': 'application/json',
			    'Content-Type': 'application/json',
			    'Cache': 'no-cache',
			    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

  			},
  			body: JSON.stringify(product),
  			credentials: 'include'
		})
		.then(function(response){
			return response.json();
		})
		.then(resp => {
			if(!resp)
				showCartMessage(product.product_name, product.image_path,
				 	'is <span class="text-weak"> already </span> in your <a href="cart"> shopping cart </a> .');
			else{
				showCartMessage(product.product_name, product.image_path,
					'was <span class="text-success"> added </span> to your <a href="cart"> shopping cart </a> .');

				var currentProducts = this.state.cartProducts;

				currentProducts.push(resp);

				const newSum = this.calculateSum(currentProducts);

				this.setState({
					cartProducts: currentProducts,
					totalSum: newSum
				});
			}

		});
	}
	
	//function that fires whenever a quantity is changed
	//for one of the products in the shopping cart
	changeQuantityHandler(id){
		console.log(id);
	}

	removeItem(id){
		console.log(id);

		fetch('removeFromCart',
			{
				method: 'post',
				headers:{
					'Accept': 'application/json',
					'Content-Type' : 'application/json',
					'Cache': 'no-cache',
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				body: JSON.stringify({'id': id}),
				credentials: 'include'
			}
		)
		.then(response => response.json())
		.then(data =>{
			if(data === true){
				var productToBeRemovedIndex = this.searchProductIndex(id), 
					currentProducts = this.state.cartProducts, newSum;

				console.log(productToBeRemovedIndex);

				//update the total sum of the products
				newSum = this.modifySum(currentProducts[productToBeRemovedIndex], '-');

				//remove the product from the array of products
				currentProducts.splice(productToBeRemovedIndex, 1);

				//display the notification
				showCartMessage( 
					this.state.cartProducts[productToBeRemovedIndex].product_name,
					this.state.cartProducts[productToBeRemovedIndex],
					 'was <span class="text-bad"> removed </span> from your <a href="cart"> cart </a> .'
				);

				//update the state
				this.setState(
					{
						cartProducts: currentProducts,
						totalSum: newSum
					}
				);
			}
			
			console.log(data);
		});
	}

	render(){
		if(!this.state.cartProducts.length){
			return (
				<div> {JSON.stringify(this.props.productToBeAdded)} </div>
			);
		}
		else{

			const products = this.state.cartProducts.map((product, i) => {
				return (
					<tr key={'product_' + i}>
						<td className="remove-item text-center" 
							onClick={()=>{
								this.removeItem(product.id)
						}}>
							<span className="glyphicon glyphicon-remove-sign"></span>
						</td>

						<td>
							<div className="product-image">
								<img className="img-responsive" src={ product.image_path} alt="Product Image"/>
							</div>
						</td>
						<td> { product.product_name }</td>
						<td> { product.manufacturer} </td>
						<td>
							<div className="form-group">
								<input type="number" className="form-control" min="1" max={product.quantity}
										value={product.picked_quantity}
										onChange={()=> {
											this.changeQuantityHandler(product.id)
										}}
								/>
							</div>
							
						</td>
						<td> { (product.final_price * product.picked_quantity).toFixed(2)} </td>
					</tr>
				);
			});

			return (
				<div className="cart-contents" data-opened={false} data-maxHeight="180">
					<div className="container-fluid">
						<hr id="cartDragBar" />
						<div className="row">
							<div className=" col-xs-3 col-md-1">
								<span className="toggle-cart glyphicon glyphicon-menu-up" 
										onClick= {() => { this.toggleCart() } }>
								</span>
							</div>
							<div className="col-xs-6 col-md-10 text-center">
								<a href="/EShop/public/cart">
									<button type="button" className=" cart-header btn btn-default">
										<div className="checkout-total">
											<strong> { this.state.totalSum.toFixed(2) } RON </strong>
										</div>
										<div className="checkout-button">
											<span className="glyphicon glyphicon-ok"></span>
											<span> Checkout </span>
										</div>
										
									</button>
								</a>
								
							</div>
							<div className="col-xs-3 col-md-1">
								<p className="number-of-products">{ this.state.cartProducts.length }</p>
							</div>
						</div>
							<div className="row">
								<div className="table-container col-xs-12">
									<div className="table-responsive">
										<table className="table table-striped ">
											<thead>
												<tr>
													<th>  </th>
													<th>Image </th>
													<th>Product Name</th>
													<th>Manufacturer</th>
													<th>Quantity</th>
													<th>Price</th>
												</tr>
											</thead>
											<tbody>
												{products}
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div className="row">
								<div className="col-xs-10 col-xs-offset-2 text-right">
									<span className="total-price">
										<strong> Total: </strong>
										 {this.state.totalSum.toFixed(2)} RON
										</span>
								</div>
							</div>
					</div>
				</div>
			);

		}
	}

	componentDidMount(){
		this.getCartProducts();
	}

	componentDidUpdate(prevProps, prevState){

		console.log(JSON.stringify(this.props.productToBeAdded.id) != JSON.stringify(prevProps.productToBeAdded.id))
		
		if(typeof this.props.productToBeAdded === 'object' && this.props.productToBeAdded.hasOwnProperty('id')){
			if(JSON.stringify(this.props.productToBeAdded.id) != JSON.stringify(prevProps.productToBeAdded.id))
				this.addProduct(this.props.productToBeAdded);
			else
				showCartMessage(this.props.productToBeAdded.product_name, this.props.productToBeAdded.image_path,
				 	'is <span class="text-weak"> already </span> in your <a href="cart"> shopping cart </a> .');
		}
		
			
	}
}