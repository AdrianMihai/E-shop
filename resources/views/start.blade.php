<!DOCTYPE html>
<html>
	<head>
		<title> E-Shop </title>
		@include('components.start-header')

		<script type="text/javascript" src="resources/js/mainpage.js"></script>
		<script type="text/javascript" src="resources/js/controllers/cartController.js"></script>
	</head>
	<body ng-app = "mainPageApp">
		@include('components.start-navbar')
		
		@include('components.start-categories')

		@include('components.notification-panel')

		<!-- Main Container -->
		<div class="main-container container" >
			<div class="row">
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
			  		<!-- Indicators -->
				  	<ol class="carousel-indicators">
				    	<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				    	<li data-target="#myCarousel" data-slide-to="1"></li>
				   		<li data-target="#myCarousel" data-slide-to="2"></li>
				  	</ol>

  					<!-- Wrapper for slides -->
  					<div class="carousel-inner" role="listbox" ng-controller = "carousel_preview">
    					<div class="item" ng-repeat="product in products" ng-class="{'active' : $index == 0}">
					      	<img ng-src="@{{product.image_path}}" alt="Chania">
					      	<div class="over-layer"></div>
					      	<div class="text container-fluid">
					      		<div class="row">
					      			<div class="col-xs-12">
					      				<h4 class="preview-product-name">@{{ product.product_name | html }}</h4>
					      			</div>
					      		</div>
					      		<div class="row">
					      			<div class="col-xs-12">
					      				<p class="review">
					      				 @{{
					      				 	(product.averageGrade ? product.averageGrade : 0) + '/5 ' +
					      				 product.numberOfReviews + (product.numberOfReviews === 1 ? " review" : " reviews")}}</p>
					      			</div>
					      		</div>
					      		<div class="row">
					      			<div class="col-xs-12">
					      				<span class="preview-initial-price"> @{{ product.price + ' LEI'}}</span>
					      				<span class="preview-discounted-price"> @{{product.discounted_price + ' LEI'}}</span>
					      			</div>
					      		</div>
					      		
					      		
					      	</div>
					    </div>
  					</div>

					<!-- Left and right controls -->
					<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
					    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					    <span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
					    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					    <span class="sr-only">Next</span>
					</a>
				</div>
			</div>

			<div class="row" ng-controller="latest_products">
				<div class="latest-products col-xs-12 col-md-6">
					<h3> Latest added products </h3>
					<hr>
						<ul class="list-inline row">
							<li class="product col-xs-12 col-sm-4 " ng-repeat="product in undiscounted_products">
								<div class="image-container">
									<div class="over-layer"> </div>
									<p class="product-review">
										@{{
										product.reviewsData.average[0].averageGrade ?  product.reviewsData.average[0].averageGrade
										 : '0' + ' /5( ' + product.reviewsData.numberOfReviews + ' reviews )'
										 }} 
									</p>
									<img class="img-thumbnail img-responsive" ng-src="@{{product.image_path}}">
								</div>
								<p class="product-name"> @{{product.product_name}}</p>
								<a href="@{{ 'product/' + product.id }}">
									@{{ product.reviewsData.numberOfReviews +
									 (product.reviewsData.numberOfReviews === 1 ? ' review' : ' reviews' )}}
								 </a>
								<p class="product-price" ng-class="{'old-price': product.discount != 0}">
									@{{product.price + ' LEI'}}
								</p>
								<p class="product-discounted-price" ng-class="{'hidden' : product.discount == 0}">
									@{{product.discounted_price}}
								</p>
								<button type="button" class="add-product btn btn-default" ng-click="add_undiscounted($index)">
									<span class="glyphicon glyphicon-shopping-cart"></span>
									Add to cart
								</button>
							</li>
							
						</ul>
				</div>

				<div class="latest-discounts col-xs-12 col-md-6 col-sm-12">
					<h3> Latest promotions </h3>
					<hr>
						<ul class="list-inline row">
							<li class="product col-xs-12 col-sm-4" ng-repeat="product in discounted_products">
								<div class="image-container">
									<div class="over-layer"> </div>
									<p class="product-review">
										@{{
										(product.reviewsData.average[0].averageGrade ? 
											 product.reviewsData.average[0].averageGrade : '0')
											+ ' /5( ' + product.reviewsData.numberOfReviews + 
											(product.reviewsData.numberOfReviews === 1 ? ' review)' : ' reviews)' ) }} 
									 </p>
									<img class="img-thumbnail img-responsive" ng-src="@{{ product.image_path }}">
								</div>
								<p class="product-name"> @{{ product.product_name}} </p>
								<a href="@{{ 'product/' + product.id }}">
									@{{ product.reviewsData.numberOfReviews +
									 (product.reviewsData.numberOfReviews === 1 ? ' review' : ' reviews' )}}
								 </a>
								<p class="product-price" ng-class="{'old-price': product.discount != 0}">
									@{{product.price + ' LEI'}}
								</p>
								<p class="product-discounted-price" ng-class="{'hidden' : product.discount == 0}">
									@{{product.discounted_price + ' LEI'}}
								</p>
								<button type="button" class="add-product btn btn-default" ng-click="add_discounted($index)">
									<span class="glyphicon glyphicon-shopping-cart"></span>
									Add to cart
								</button>
							</li>
							
						</ul>
							
				</div>
			</div>

			@include('components.footer')
		</div>
		@include('components.shoppingCart')
		

	</body>
</html>