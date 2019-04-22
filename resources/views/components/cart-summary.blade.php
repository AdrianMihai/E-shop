<div class="col-xs-12 col-sm-4 col-md-3 col-sm-offset-8 col-md-offset-9 summary-container">
	<div class="panel panel-default">
  		<div class="panel-heading text-center">Summary</div>
  			<div class="panel-body cart-summary">
                <ol>
                	<li ng-repeat="product in cartProducts" data-toggled="false">
                        @{{ product.picked_quantity + ' x ' + product.final_price + ' = '
                             + (product.picked_quantity * product.final_price).toFixed(2) + ' RON'}}
                	</li>
                  <li>
                    @{{ 'Shipping = ' + ((totalSum < shippingPrices.free_from) ? shippingPrices.price : 0 )}}
                  </li>
                </ol>
                <hr>
                <p>Total: @{{ totalSum + ((totalSum < shippingPrices.free_from) ? shippingPrices.price : 0 ) }} RON</p>
  			</div>
	</div>
</div>