import React, {Component} from 'react';
import 'whatwg-fetch';

class DisplayProduct extends Component{
	render(){
		return (
			<div className={"item " + (this.props.index === 0 ? 'active' : '')}>
				<img src={this.props.image_path} alt="Chania" />
				<div className="over-layer"></div>

				<div className="text container-fluid">
					<div className="row">
						<div className="col-xs-12">
						    <h4 className="preview-props-name">{this.props.product_name}</h4>
						</div>
					 </div>
					<div className="row">
						<div className="col-xs-12">
						    <p className="review">
						      	{
						      		(this.props.averageGrade ? this.props.averageGrade : 0) + '/5 ' +
						      			this.props.numberOfReviews + (this.props.numberOfReviews === 1 ? " review" : " reviews")
						      	}
						     </p>
						</div>
					</div>
					<div className="row">
						<div className="col-xs-12">
						    <span className="preview-initial-price"> { this.props.price + ' LEI'}</span>
						    <span className="preview-discounted-price"> {this.props.discounted_price + ' LEI'}</span>
						</div>
					</div>	      		
				</div>
			</div>
		);
	}
}

export default class PreviewProducts extends Component{
	constructor(props){
		super(props);

		this.state = {products: []};
		this.getProducts = this.getProducts.bind(this);
	}

	getProducts(){

		//load the products from the back-end
		fetch('previewDiscountedProducts')
			.then(function(response){
				return response.json();
			})
			.then(data => {

				var productsData = [], i;
				
				for(i = data[0].length - 1; i >= 0; i--){
					productsData[i] = data[0][i];
  					productsData[i].numberOfReviews = data[1].numberOfReviews ? data[1].numberOfReviews : 0;
		 			productsData[i].averageRating = data[1][i].average[0].averageGrade;
  				}

  				//console.log(productsData);

				this.setState({products: productsData});
			});
  	}

  	render(){
  			var carousel_content;

  			if(!this.state.products.length)
  				carousel_content =(
  					<div className="loader-container">
  						<div className="loader"> </div>
  					</div> 
  				) ;
  			else
  				//prepare the products to be displayed inside the carousel
  				carousel_content = this.state.products.map(function(product, i){
  					return <DisplayProduct key={'product_' + i }
  							 index={i}
  							 image_path = {product.image_path}
  							 product_name = {product.product_name}
  							 price = {product.price}
  							 discounted_price = {product.discounted_price} 
  							 averageGrade = {product.averageGrade}
  							 numberOfReviews = {product.numberOfReviews}
  						/>
  				});

  			return (
  				<div className="row">
					<div id="myCarousel" className="carousel slide" data-ride={"carousel"}>
				  		
					  	<ol className="carousel-indicators">
					    	<li data-target={"#myCarousel"} data-slide-to={"0"} className={"active"}></li>
					    	<li data-target={"#myCarousel"} data-slide-to={"1"}></li>
					   		<li data-target={"#myCarousel"} data-slide-to={"2"}></li>
					  	</ol>

	  					
	  					<div className="carousel-inner" role={"listbox"} >
	    					{carousel_content}
	    						
	  					</div>

						<a className="left carousel-control" href="#myCarousel" role={"button"} data-slide={"prev"}>
						    <span className="glyphicon glyphicon-chevron-left" aria-hidden={"true"}></span>
						    <span className="sr-only">Previous</span>
						</a>
						<a className="right carousel-control" href="#myCarousel" role="button" data-slide={"next"}>
						    <span className="glyphicon glyphicon-chevron-right" aria-hidden={"true"}></span>
						    <span className="sr-only">Next</span>
						</a>
					</div>
			</div>

  		);
  	}

  	componentDidMount(){
  		this.getProducts();
  	}
}