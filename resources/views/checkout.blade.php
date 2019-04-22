<!DOCTYPE html>
<html>
	<head>
		<title> Shopping Cart</title>
		<!-- META TAGS -->	
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="csrf-token" content="{{ csrf_token() }}" />

		<!-- CSS -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/stylesheets/start.css">
		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/stylesheets/cart.css">

		<!-- JAVASCRIPT -->
		<script type="text/javascript" src="/EShop/public/resources/js/node_modules/jquery/dist/jquery.js"></script>
		<script type="text/javascript" src="/EShop/resources/views/css/javascripts/bootstrap.js"></script>
		<script type="text/javascript" src="/EShop/public/resources/js/node_modules/angular-1.5.7/angular.min.js"></script>
		<script type="text/javascript" src="/EShop/public/resources/js/animations.js"></script>
		<script type="text/javascript">
			$.ajaxSetup({
        		headers: {
            		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        		}
    		});
		</script>

	</head>
	<body ng-app="cartCheckout">
		@include('components.start-navbar')
		
		@include('components.start-categories')

		<div class="main-container container">
			<div class="row progress-row">
				<div class="col-xs-12 col-sm-10">
					<div class="col-xs-1 text-center">
						<a href="step-1">
							<div class="rounded-icon active-icon">
								<span class="glyphicon glyphicon-list"></span>
							</div>
						</a>
						
					</div>

					<div class="col-xs-2">
						<div class="progress">
					  		<div class="progress-bar progress-bar-striped active" role="progressbar"
					  		 aria-valuemin="0" aria-valuemax="100" >
					    		
					  		</div>
						</div>
					</div>
					
					<div class="col-xs-1 text-center">
						<a href="step-2">
							<div class="rounded-icon">
								<span class="glyphicon glyphicon-info-sign"></span>
							</div>
						</a>
					</div>
					
					<div class="col-xs-2">
						<div class="progress">
					  		<div class="progress-bar progress-bar-striped active" role="progressbar"
					  		 aria-valuemin="0" aria-valuemax="100" >
					    		
					  		</div>
						</div>
					</div>

					<div class="col-xs-1 text-center">
						<a href="step-3">
							<div class="rounded-icon">
								<span class="glyphicon glyphicon-credit-card"></span>
							</div>
						</a>
					</div>
					
					<div class="col-xs-2">
						<div class="progress">
					  		<div class="progress-bar progress-bar-striped active" role="progressbar"
					  		 aria-valuemin="0" aria-valuemax="100" >
					    		
					  		</div>
						</div>
					</div>

					<div class="col-xs-1 text-center">
						<a href="step-4">
							<div class="rounded-icon">
								<span class="glyphicon glyphicon-shopping-cart"></span>
							</div>
						</a>
					</div>
					
					<div class="col-xs-2">
						<div class="progress">
					  		<div class="progress-bar progress-bar-striped active" role="progressbar"
					  		 aria-valuemin="0" aria-valuemax="100" >
					    		
					  		</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-xs-offset-6 text-right col-sm-2 col-sm-offset-0">
					<a href="{{ isset($step) ? substr($step, 0, strlen($step) - 1) .
					 (string)((int)substr($step, strlen($step) - 1, strlen($step) ) + 1 ) : 'cart/step-2' }}">
						<button type="button" class="next-button btn btn-default">
							Next
							<span class="glyphicon glyphicon-arrow-right"></span>
							
						</button>
					</a>
					
				</div>
			</div>
			<script type="text/javascript">
				var app = angular.module("cartCheckout", []);

			</script>
			@if ($step == 'step-1')
				@include('components.checkout-step1')
			@elseif ($step == 'step-2')
				@include('components.checkout-step2')
			@elseif ($step == 'step-3')
				@include('components.checkout-step3')
			@elseif ($step == 'step-4')
				@include('components.checkout-step4')
			@else
				@include('components.checkout-step1')
			@endif

			@include('components.footer')
		</div>


	</body>
</html>