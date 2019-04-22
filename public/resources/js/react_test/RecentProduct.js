import React, {Component} from 'react';

export default class RecentProduct extends Component{
	render(){
		return(
			<li className="product col-xs-12 col-sm-4 ">
				<div className="image-container">
					<div className="over-layer"> </div>
					<p className="product-review">
						{
							this.props.reviewsData.average[0].averageGrade ?  this.props.reviewsData.average[0].averageGrade
							: '0' + ' /5( ' + this.props.reviewsData.numberOfReviews + ' reviews )'
						} 
					</p>
					<img className="img-thumbnail img-responsive" src={this.props.image_path} />
				</div>
				<p className="product-name"> {this.props.product_name}</p>
					<a href={ 'product/' + this.props.id }>
						{ this.props.reviewsData.numberOfReviews +
							(this.props.reviewsData.numberOfReviews === 1 ? ' review' : ' reviews' )}
					</a>
					<p className={"product-price " + (this.props.discount != 0 ? 'old-price' : '') }>
						{this.props.price + ' LEI'}
					</p>
					<p className={"product-discounted-price " + (this.props.discount == 0 ? 'hidden' : '')}>
						{this.props.discount != 0 ? this.props.discounted_price + ' LEI' : ''}
					</p>
					<button type="button" className="add-product btn btn-default"
						onClick={()=>{
							if(this.props.discount != 0) 
								this.props.add_discounted(this.props.id)
							else
								this.props.add_undiscounted(this.props.id)
							}
						}>
						<span className="glyphicon glyphicon-shopping-cart"></span>
						Add to cart
					</button>
			</li>
		);				
	}
}