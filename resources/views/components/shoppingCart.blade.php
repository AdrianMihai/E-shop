<!-- Shopping cart contents -->
		<div class="cart-contents" ng-controller="cart_contents" data-opened="false" data-maxHeight="180">
			
			<div class="container-fluid">
				<hr id="cartDragBar">
				<div class="row">
					<div class=" col-xs-3 col-md-1">
						<span class="toggle-cart glyphicon glyphicon-menu-up"></span>
					</div>
					<div class="col-xs-6 col-md-10 text-center">
						<a href="/EShop/public/cart">
							<button type="button" class=" cart-header btn btn-default">
								<div class="checkout-total">
									<strong> @{{ totalSum }} RON </strong>
								</div>
								<div class="checkout-button">
									<span class="glyphicon glyphicon-ok"></span>
									<span> Checkout </span>
								</div>
								
							</button>
						</a>
						
					</div>
					<div class="col-xs-3 col-md-1">
						<p class="number-of-products">@{{ cartProducts.length }}</p>
					</div>
				</div>
					<div class="row">
						<div class="table-container col-xs-12">
							<div class="table-responsive">
								<table class="table table-striped ">
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
										<tr ng-repeat="product in cartProducts">
											<td class="remove-item text-center" ng-click="removeItem($index)">
												<span class="glyphicon glyphicon-remove-sign"></span></td>
											<td>
												<div class="product-image">
													<img class="img-responsive" ng-src="@{{ product.image_path}}">
												</div>
											</td>
											<td> @{{ product.product_name }}</td>
											<td> @{{ product.manufacturer}} </td>
											<td>
												<div class="form-group">
													<input type="number" class="form-control" min="1" max="@{{product.quantity}}"
															 value="@{{product.picked_quantity}}" ng-model="quantites[product.id]"
															 ng-change="changeQuantity($index)"/>
												</div>
												
											</td>
											<td> @{{ (product.final_price * product.picked_quantity).toFixed(2)}} </td>
										</tr>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row" ng-class="{'hidden' : totalSum == 0}">
						<div class="col-xs-10 col-xs-offset-2 text-right">
							<span class="total-price">
								<strong> Total: </strong>
								 @{{ totalSum }} RON
								</span>
						</div>
					</div>
			</div>
		</div>
<script type="text/javascript">

	//function which handles the events for resizing the shopping cart
	function cartResize(){
		var maxHeight = 180, mousePos = $('.cart-contents').position() ;

		mousePos.top -= 115;
		//alert(mousePos.top);

		$('#cartDragBar').mousemove(function(e){
			if(e.which == 1){
				if($('.cart-contents').data('opened')){
					console.log(e.clientY);
					maxHeight =  maxHeight + mousePos.top - e.clientY;
						
					$('.cart-contents').css('maxHeight', maxHeight);
								
					mousePos.top = e.clientY;

					$('.cart-contents').data('maxheight', maxHeight);
					console.log(e.clientY);
				}
			}
		});

	}
	$(document).ready(cartResize());
</script>