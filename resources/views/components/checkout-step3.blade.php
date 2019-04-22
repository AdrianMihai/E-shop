@include('components.admin-infoMessages')

<style type="text/css">
		.infoMessages {
			position: fixed;
			right: 5px;
			top: 5px;
			max-width: 250px;
			min-width: 43px;
			max-height: 0px;
			border-radius: 4px;
			font-family: "Montserrat", sans-serif;
			font-size: 12px;
			overflow: hidden;
			opacity: 0.25;
			z-index: 1051;
		}
		.infoMessages .row{
				margin-top:0px !important;
		}
		/* line 39, ../../sass/general/admin-general.scss */
		.infoMessages .col-xs-10 .row {
			padding-top: 5px;
			padding-bottom: 5px;
			margin: 5px auto !important;
		}
		/* line 45, ../../sass/general/admin-general.scss */
		.infoMessages .col-xs-10 .row .col-xs-12 {
			padding: 0px;
		}
		/* line 49, ../../sass/general/admin-general.scss */
		.infoMessages .col-xs-2 {
			position: relative;
			height: 18px;
			padding-right: 0px;
			margin-top: 17px;
		}
</style>

<div class="row" ng-controller="cart_contents">

	<div class="col-xs-12 col-sm-8 col-md-9">
		<h2>Select payment method </h2>
	</div>

	<div class="col-xs-12 col-sm-8 col-md-9 text-center">
		<form class="form" action="/EShop/public/step-3/check" method="post">
			{{ csrf_field() }}
			<div class="radio">
							<label>
									<input type="radio" name="payment" value="onArrival">
									Pay on arrival
							</label>
					</div>
			</div>

			<div class="col-xs-12 col-sm-8 col-md-9 text-center">
					 <p> or </p>
			</div>
					
					<div class="col-sm-offset-2 col-xs-12 col-sm-4 col-md-4 ">
							<div class="radio">
									<label>
											<input id="check" type="radio" name="payment" value="paypal">
											<div id="paypal-button-container"> </div>
									</label>

							</div>

					</div>     
		</form>     
		@include('components.cart-summary')
</div> 

<script type="text/javascript" src="/EShop/public/resources/js/admin/messages.js"></script>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<script type="text/javascript" src="https://js.braintreegateway.com/web/3.11.0/js/client.min.js"></script>

<script type="text/javascript" src="https://js.braintreegateway.com/web/3.11.0/js/paypal-checkout.min.js"></script>

<script type="text/javascript">
		var app = angular.module("cartCheckout", []);
		
		$(document).ready(function(){
			var progressBars = $('.progress .progress-bar');

			progressBars.eq(0).css('transition-duration', '0s')
			progressBars.eq(0).css('width', '100%');
			$('.rounded-icon').eq(1).addClass('active-icon');

			progressBars.eq(1).animate({
				width: '100%'
			}, 450, function(){
				$('.rounded-icon').eq(2).addClass('active-icon');
			});

			$('.next-button').click(function(e){
				e.preventDefault();
				$('.form').submit();
			});
		});
		

				function isValid() {
						return document.querySelector('#check').checked;
				}

				function onChangeCheckbox(handler) {
						document.querySelector('#check').addEventListener('change', handler);
				}

				function toggleValidationMessage() {
						infoMessage('You did not select paypal as payment option.', 'alert-danger');
				}

				function toggleButton(actions) {
						return isValid() ? actions.enable() : actions.disable();
				}
		 // Render the PayPal button

			paypal.Button.render({

				// Set your environment

				env: 'sandbox', // sandbox | production

				validate: function(actions) {
						toggleButton(actions);

						onChangeCheckbox(function() {
								toggleButton(actions);
						});
				},

				onClick: function() {
						toggleValidationMessage();
				},

				// Wait for the PayPal button to be clicked

				payment: function() {

						// Make a call to the merchant server to set up the payment

						return paypal.request.post('../paypal/getToken').then(function(res) {
								return res.payToken;
						});
				},

				// Wait for the payment to be authorized by the customer

				onAuthorize: function(data, actions) {

						// Make a call to the merchant server to execute the payment

						return paypal.request.post('/demo/checkout/api/paypal/payment/execute/', {
								payToken: data.paymentID,
								payerId: data.payerID
						}).then(function (res) {

								document.querySelector('#paypal-button-container').innerText = 'Payment Complete!';
						});
				}

		}, '#paypal-button-container');

</script>

<script type="text/javascript" src="/EShop/public/resources/js/controllers/cartController.js"></script>