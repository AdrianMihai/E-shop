import React from 'react';
import ReactDOM from 'react-dom';
import PreviewProducts from './PreviewProducts';
import LatestProducts from './LatestProducts';
import ShoppingCart from './ShoppingCart';

class App extends React.Component{
	constructor(props){
		super(props);

		this.state = {productAux: {}};

		this.sendProductToCart = this.sendProductToCart.bind(this);
	}

	//function that is used to send a product that needs to be added to the shopping cart
	sendProductToCart(product){
		this.setState({productAux: product});
	}

	render(){
		return(
			<div>
				<PreviewProducts />
				<LatestProducts shareProduct={this.sendProductToCart}/>
				<ShoppingCart productToBeAdded={this.state.productAux}/>
			</div>
		);
	}
}

ReactDOM.render(<App />, document.getElementById('root'));