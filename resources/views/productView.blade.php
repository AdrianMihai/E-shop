<!DOCTYPE html>
<html>
	<head>
		<title>EShop - </title>

		@include('components.start-header')

		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/stylesheets/product.css">
	</head>
	<body ng-app="productApp">
		@include('components.start-navbar')
		
		@include('components.start-categories')

		@include('components.notification-panel')

		<div class="main-container container" ng-controller="productData">
			<div class="row" ng-class="{'hidden' : !productData}">
				<div class="col-xs-12">
					<h3 class="productName"> @{{ productData.product_name }} </h3>
					<hr>
				</div>
				
			</div>

			<div class="row" ng-class="{'hidden' : !productData}">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="productImage">
						<img class="img-responsive img-thumbnail" ng-src="@{{'../' + productData.image_path}}">
					</div>
				</div>

				<div class="col-xs-12 col-sm-6 col-md-8">
					<div class="row">
						<div class="col-xs-12 col-md-12 text-ok">
							<strong>
								<p>
									@{{ reviewAverage + '/5 rating ( ' + reviews.length + (reviews.length == 1 ? ' review' : ' reviews') + ' )'}}
							 	</p>
							</strong>
						</div>

						<div class="col-xs-12 col-md-12 ">
							<p>
								<span>
									<strong> Manufacturer :</strong>
								</span>
								<span> @{{productData.manufacturer}} </span>
							</p>
							
						</div>

						<div class="col-xs-12 col-md-12 ">
							<p>
								<span>
									<strong> Price :</strong>
								</span>

								<span ng-class="{'old-price': productData.discount} "> @{{productData.price}} </span>

								<span class="discounted-price text-bad" ng-class="{'hidden' : !productData.discount}"> 
									<strong> 
										@{{(productData.price - productData.price*productData.discount/100).toFixed(2)}}
									</strong>
								</span>
							</p>
							
						</div>

						<div class="col-xs-12 col-md-12 ">
							<input type="number" class="form-control quantity-control" min="1" max="@{{ productData.quantity }}" 
									 ng-model="pickedQuantity">
							<button type="button" class="add-product btn btn-default" ng-click="addProduct()">
								<span class="glyphicon glyphicon-shopping-cart"></span>
								Add to cart
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="row" ng-class="{'hidden' : !productData}">
				<div class="col-xs-12">
					<h4>Reviews of this product</h4>
					<hr>
				</div>
				
			</div>
			<div class="row"> 
				<div class="col-xs-12">
					<p>
						<span ng-class="{'hidden': reviews.length}">
							There are no reviews.
						</span>

						@if(Auth::check())
							<span ng-class="{'hidden': reviews.length}" class="write-yours">
								<strong> Write yours. </strong>
							</span>
							<div class="col-xs-12 col-md-12">
								<form class="form" action>
									<div class="form-group">
										<label>Choose rating</label>
										<select class="form-control" name="rating">
											<option value="">Select a rating</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										</select>
									</div>
									<div class="form-group">
										<textarea class="form-control" name="reviewText" rows="5" value=""
										 placeholder="Say what you think about this product">
										</textarea>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Submit review </button>
									</div>
								</form>
								
							</div>
						@else
							<a href="/EShop/public/login">
								<span> <strong>Log in to write a review. </strong></span>
							</a>
						@endif
					</p>
										

				</div>
				
			</div>
			<div class="row review" ng-repeat="review in reviews | orderBy : created_at: true ">
				<div class="col-xs-12 content">
					<p class="user">@{{ review.username + ' says :'}}</p>

					<div class="col-xs-12 col-md-12 review-text">
						@{{ '" ' + review.review + ' " ' }}
					</div>
					<hr>
				</div>
				
			</div>

			@include('components.footer')

		</div>

		@include('components.shoppingCart')

		<script type="text/javascript">
			var app = angular.module('productApp', []);
		</script>
		<!--SERVICES -->
		<script type="text/javascript" src="/EShop/public/resources/js/controllers/cartController.js"></script>

		<script type="text/javascript">

			app.controller('productData', function($scope, $http, addToCart){
				var productId = '{{ $productId }}';

				$scope.pickedQuantity = 1;
				$scope.productData = {};
				$scope.reviews = {};

				$http.get(productId + '/getData')
						.then(function(response){
							var resp = response.data;
							$scope.productData = resp.data[0];
							$scope.reviews = resp.reviews;
							$scope.reviewAverage = resp.review_average[0].averageGrade ? resp.review_average[0].averageGrade : 0 ;
							
							console.log(reviews);
						},
						function(error){
							console.log(error.data);
						});

				$scope.addProduct = function(){
					addToCart.addQuantity($scope.productData, $scope.pickedQuantity);
				};
			});
		</script>

	</body>
</html>