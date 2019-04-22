<!DOCTYPE html>
<html>
	<head>
		<title> EShop Admin Page </title>
		
		@include('components.admin-header')

		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/stylesheets/general/admin-products.css">

		<script type="text/javascript" src="../resources/js/node_modules/angular-1.5.7/angular-animate.min.js"></script>

		<script type="text/javascript" src="../resources/js/admin/messages.js"></script>
		<!--MAIN APP-->
		<script type="text/javascript" src="../resources/js/admin/products_app.js"></script>

		<!-- SERVICES -->
		<script type="text/javascript" src="../resources/js/admin/services/productShare.js"></script>

		<!-- CONTROLLERS -->
		<script type="text/javascript" src="../resources/js/admin/controllers/removeProduct.js"></script>
		
	</head>
	<body ng-app="products_app">
		<!-- NAVBAR  -->
		@include('components.admin-navbar')

		<!--IMAGE WARNINGS -->
		@include('components.admin-imagesWarnings')

		<!-- MESSAGES FOR PRODUCTS -->
		@include('components.admin-infoMessages')

		<!--DIALOG BOX FOR REMOVING A PRODUCT-->
		<div ng-controller="deleteProduct" class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">

    				<!--Modal header -->
      				<div class="modal-header">
        				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        					<span aria-hidden="true"> &times; </span>
        				</button>
        				<h5 class="modal-title">
        					Are you sure you want to 
        					<strong class="text-bad"> 
        						remove 
        					</strong>
        					this product?
        				</h5>
      				</div>

      				<!--Modal Body-->
      				<div class="modal-body">
      					<div class="container-fluid">
      						<div class="row">
      							<div class="col-xs-12">
      								<p>This product will be permanently deleted :</p>
      							</div>
      						</div>
      						<div class="row">
      							<div class="col-xs-2">
      								<p> @{{ productId }}</p>
      							</div>
      							<div class="col-xs-4 col-md-2">
      								<div class="product-image">
      									<img class="img-responsive" ng-src="@{{ '../' + productImage }}">
      								</div>
      							</div>
      							<div class="col-xs-6 col-md-8">
      								<p> @{{  productName }}</p>
      							</div>
      						</div>
      					</div>
        				
      				</div>

      				<div class="modal-footer">
        				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        				<button ng-click="sendId()" type="button" class="btn btn-danger">Remove it</button>
      				</div>
    			</div><!-- /.modal-content -->
  			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- MAIN CONTENT -->
		<div class="admin-content container-fluid">
			<div class="row">

				<!-- SIDEBAR -->
				@include('components.admin-sidebar')
				
				<script type="text/javascript">
					$(document).ready(function(){
						$('.sidebar .list-unstyled a:nth-of-type(3) .page-link').addClass('active-link');
					});
				</script>

				<div class="content-display col-xs-12 col-md-10 col-md-offset-2"
					 ng-controller="load_products">
					<h3>Here you can see all the products and manage them.</h3>
					<hr>
					<input type="text" class="form-control" placeholder="Search for a product" 
							ng-value="{{ $q }}" ng-model="searchText"/>

					@if( $position == 0 || $position == 1)
						<button type="button" class="add-button btn btn-primary" ng-click="addProduct()">
							<span class="glyphicon glyphicon-plus"></span>
							Add a new product
						</button>
					@endif

					<div class="table-responsive">
						<table class="products-table table table-striped table-bordered">
							<thead>
								<tr>
									<th class="product-id"> ID </th>
									<th class="image"> Image </th>
									<th class="product-name"> Name</th>
									<th class="product-manufacturer"> Manufacturer </th>
									<th class="initial-price"> Price/Piece (RON)</th>
									<th class="discounted-price"> New price</th>
									<th class="quantity"> Quantity </th>
									<th class="date"> AddedOn</th>
									<th class="added-by"> AddedBy</th>
								</tr>
							</thead>
							<tbody>
								<tr class="product-row"
									ng-repeat="product in products | limitTo: display | filter: searchText track by product.id"
									ng-class="{'danger': product.quantity <= 5}">
									

									<td class="product-id"> @{{product.id}} </td>
									<td class="image">
										<div class="product-image">
											<img  class="img-responsive" ng-src=' @{{"../" + product.image_path}} ' >
										</div>
									</td>
									<td class="product-name">
										@{{product.product_name}}
									</td>
									<td class="product-manufacturer">
										@{{product.manufacturer}}
									</td>
									<td class="initial-price">
										@{{product.price}}
									</td>
									<td class="discounted-price">
										@{{product.discounted_price ? (product.discounted_price).toFixed(2) : '-'}}
									</td>
									<td class="quantity">
										@{{product.quantity}}
									</td>
									<td class="date">@{{product.addedon * 1000 | date:'medium' }}</td>

									<td class="added-by">@{{product.addedby}}</td>

									<td class="productSettings" >
										<div class="dropdown">
											<span class="glyphicon glyphicon-option-vertical"
												 data-toggle="dropdown"
												  aria-haspopup="true" aria-expanded="false">
											</span>
											<ul class="dropdown-menu" aria-labelledby="dLabel">
										    	<li class="text-center">
										    		<button class="btn btn-info"
										    				 ng-click="updateProduct(product.id)">

										    				 Update Product
										    		</button>
										    	</li>
										    	<li role="separator" class="divider"></li>
										    	<li class="text-center">
										    		<button class="btn btn-danger"
										    				ng-click = "deleteProduct(product.id)">

										    		 		Remove Product
										    		</button>
										    	</li>
										  	</ul>
										</div>
										
									</td>
									
								</tr>
							</tbody>
						</table>
					</div>
					
				</div>
			</div>
		</div>

		@if( $position == 0 || $position == 1)
		<!-- MODAL -->
			<div id="modal" class="modal fade" role="dialog" ng-controller="addProduct">
	  			<div class="modal-dialog modal-lg">

		    		<!-- Modal content-->
		    		<div class="modal-content">
	      				<div class="modal-header">
	        				<button type="button" class="close" data-dismiss="modal">&times;</button>
	        				<h4 class="modal-title">@{{header}}</h4>
	     				</div>
	      				<div class="modal-body" >
	      					
	      					<div class="row">
	      						<p class="col-xs-12 helper"> Complete the following data for your product</p>
	      					</div>
	      					<div class="row" >
		        				<form class="form" ng-submit="add_product()">
		        					<div class="form-group col-xs-6">
		        						<input  type="text" class="form-control idInput" placeholder="ID" ng-model="id" required />
		        					</div>
		        					<div class="form-group col-xs-6">
		        						<input  type="text" class="form-control" placeholder="Name" ng-model="name" required />
		        					</div>
									
									<div class="form-group col-xs-6">
		        						<select class="form-control" ng-model="category" ng-change="category_change()" required>
			        						<option value="">Select a category</option>
			        						<option ng-repeat="categ in categories" value="@{{ categ.category}}"> 
			        							@{{ categ.category}}
			        						</option>
			        					</select>
		        					</div>

		        					<div class="form-group col-xs-6">
		        						<select class="form-control" ng-model="subCategory" required>
			        						<option value="">Select a subcategory</option>
			        						<option ng-repeat="subCateg in sub_categories" value="@{{ subCateg }}"> 
			        							@{{ subCateg }}
			        						</option>
			        					</select>
		        					</div>
		        					
		        					<div class="form-group price-control col-xs-6">
		        						<div class="input-group">
		        							<input  type="text" class="form-control" placeholder="Price (RON)" ng-model="price" ng-keyup="price_check()" required  />
		        							<div class="input-group-addon">RON</div>
		        						</div>
		        						
		        						<p class="help-block"> The price cannot be a negative value or a letter/symbol.</p>
		        					</div>

		        					<div class="form-group col-xs-6">
		        						<input  type="text" class="form-control" placeholder="Manufacturer" ng-model="manufacturer" required />
		        					</div>

		        					<div class="form-group discount-control col-xs-6">
		        						<div class="input-group">
		        							<input  type="text" class="form-control" placeholder="Discount" ng-model="discount"
		        						 			ng-keyup="discount_check()"/>
		        						 	<div class="input-group-addon">%</div>
		        						</div>
		        						
		        						 <p class="help-block"> The discount must be a <strong> number </strong> between 0 and 100.</p>
		        					</div>

		        					<div class="form-group quantity-control col-xs-6">
		        						<input  type="text" class="form-control" placeholder="Quantity" ng-model="quantity"
		        						 	ng-keyup="quantity_check()" required />
		        						 <p class="help-block"> The quantity cannot be a negative value or a letter/symbol.</p>
		        					</div>
		        					
		        					<div class="col-xs-12">
		        						<strong> 
		        							<p>Choose the main image for the product:</p>
		        						</strong>
		        					</div>

		        					<div class="form-group col-xs-12"> 
		        						<div class="image-picker">
		        							<div class="close"> &times; </div>
		        							<img  class="img-responsive" src="" alt="">
		        							<span class="glyphicon glyphicon-plus"></span>
		        						</div>
		        					</div>
		        					
		        					
		        					<div class="form-group col-xs-12">
		        						<input type="file" id="product-image" style="display: none;">
		        						<button type="submit" class="btn btn-success"> @{{buttonText}}</button>

		        						<script type="text/javascript">
		        							$(document).ready(function(){
		        								$('.image-picker span').click(function(){
		        									$('#product-image').click();
		        								});
		        							});
		        						</script>
		        					</div>
		        					
		        				</form>
		        			</div>
	      				</div>
	      				<div class="modal-footer">
	        				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
	      				</div>
	   				</div>

	  			</div>
			</div>
		@endif
	</body>
</html>