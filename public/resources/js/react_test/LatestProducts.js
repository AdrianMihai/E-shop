import React, {Component} from 'react';
import RecentProduct from './RecentProduct';
import 'whatwg-fetch';

export default class LatestProducts extends Component{
	constructor(props){
		super(props);

		this.state = {
			latestAddedProducts: [],
			latestPromotions: []
		};

		this.getProducts = this.getProducts.bind(this);
		this.addUndiscounted = this.addUndiscounted.bind(this);
		this.addDiscounted = this.addDiscounted.bind(this);
	}

	//function that fetches all the products that need to be displayed on the first page
	//(latest added product with no discount and with discount)
	getProducts(){
		fetch('latestProducts')
			.then(function(response){
				return response.json();
			})
			.then((data)=>{

				this.setState({
					latestAddedProducts: data[0],
					latestPromotions: data[1]
				});

				console.log(this.state);
			});
	}

	//function that adds an undiscounted product to the shopping cart
	addUndiscounted(id){
		for (var i = this.state.latestAddedProducts.length - 1; i >= 0; i--) {
			if(this.state.latestAddedProducts[i].id === id){
				let productToBeShared = this.state.latestAddedProducts[i];
				productToBeShared.picked_quantity = 1;
				productToBeShared.final_price = productToBeShared.price;

				this.props.shareProduct(productToBeShared);
				i = -1; //stops the for
			}
		}
	}

	//function that adds an undiscounted product to the shopping cart
	addDiscounted(id){
		for (var i = this.state.latestPromotions.length - 1; i >= 0; i--) {
			if(this.state.latestPromotions[i].id === id){
				let productToBeShared = this.state.latestPromotions[i];
				productToBeShared.picked_quantity = 1;
				productToBeShared.final_price = productToBeShared.discounted_price;

				this.props.shareProduct(productToBeShared);
				i = -1;//stop the for
			}
		}
	}

	render(){
		var latestAddedProductsContent, latestPromotionsContent;

		if(!this.state.latestAddedProducts.length){
			latestAddedProductsContent = (
				<div className="loader-container">
					<div className="loader"></div>
				</div>
			);
		}
		else{
			latestAddedProductsContent = this.state.latestAddedProducts.map((product,i) => {
				return <RecentProduct 
					key={"product_" + i}
					index={i}
					id={product.id}
					product_name={product.product_name}
					image_path={product.image_path}
					price={product.price}
					discounted_price={product.discounted_price}
					discount={product.discount}
					reviewsData={product.reviewsData}
					add_discounted={this.addDiscounted}
					add_undiscounted={this.addUndiscounted}
				/>
			});
		}
			

		if(!this.state.latestPromotions.length){
			latestPromotionsContent = (
				<div className="loader-container">
					<div className="loader"></div>
				</div>
			);
		}
		else{
			latestPromotionsContent = this.state.latestPromotions.map((product, i) =>{
				return <RecentProduct 
					key={"product_" + i}
					index={i}
					id={product.id}
					product_name={product.product_name}
					image_path={product.image_path}
					price={product.price}
					discounted_price={product.discounted_price}
					discount={product.discount}
					reviewsData={product.reviewsData}
					add_discounted={this.addDiscounted}
					add_undiscounted={this.addUndiscounted}
				/>
			});
		}

		return(
			<div className="row">
				<div className="latest-products col-xs-12 col-md-6">
					<h3> Latest added products </h3>
					<hr />
					<ul className="list-inline row">
						{latestAddedProductsContent}

					</ul>
				</div>

				<div className="latest-discounts col-xs-12 col-md-6 col-sm-12">
					<h3> Latest promotions </h3>
					<hr />
					<ul className="list-inline row">

						{latestPromotionsContent}
					</ul>
				</div>
			</div>
		);
	}

	componentDidMount(){
		this.getProducts();
	}
}