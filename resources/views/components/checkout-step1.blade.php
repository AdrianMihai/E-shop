@include('components.notification-panel')

<div class="row" ng-controller="cart_contents">
	<div class="col-xs-12 col-sm-8 col-md-9 cart-column">
		<div class="panel panel-default">
  			<div class="panel-heading">Your Shopping Cart</div>
  			<div class="panel-body" >
                <div class="row" ng-class="{'hidden' : cartProducts.length > 0}">
                    <p class="col-xs-12">You shopping cart is empty.</p>
                </div>
    			<div class="row" ng-repeat="product in cartProducts"
                        ng-mouseover="color_element($index)"
                        ng-mouseleave="remove_color($index)">

    				<!-- Remove an item from cart-->
    				<div class="remove-item col-xs-1 text-center " ng-click="removeItem($index)">
    					<span class="glyphicon glyphicon-remove-sign"></span>
    				</div>

    				<div class="col-xs-2 cart-img">
    					<img class="img-responsive img-thumbnail" ng-src="@{{ product.image_path}}">
    				</div>
    				<div class="col-xs-5 cart-name">
    					@{{ product.product_name}}
    				</div>

    				<div class="col-xs-2 cart-quantity">
    					<div class="form-group">
							<input type="number" class="form-control" min="1" max="@{{product.quantity}}"
									value="@{{product.picked_quantity}}" ng-model="quantites[product.id]"
									ng-change="changeQuantity($index)"/>
						</div>
    				</div>

    				<div class="col-xs-2 cart-final-price text-center">
    					@{{ (product.final_price * product.picked_quantity).toFixed(2) + " RON"}}
    				</div>
    			</div>
  			</div>
		</div>
	</div>
	@include('components.cart-summary')
</div>
<script type="text/javascript" src="/EShop/public/resources/js/controllers/cartController.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
                
        //Scroll event for setting the cart's summary top position
        var initial_pos = $('.summary-container').offset();
            
        var scrolled_too_much = false; 

        $(document).scroll(function(){
            var el = $('.summary-container');
                if(el.css('position') != 'static'){
                    var position = el.offset();
                        
                    if(position.top >= initial_pos.top + 62 && !scrolled_too_much ){
                        el.animate({
                            top: 79
                        }, 200);

                        scrolled_too_much = true;
                    }

                    if(position.top < initial_pos.top && scrolled_too_much){
                        el.animate({
                            top: initial_pos.top
                        }, 200);
                        scrolled_too_much = false; 
                    }
                }
            });
        });
        
</script>