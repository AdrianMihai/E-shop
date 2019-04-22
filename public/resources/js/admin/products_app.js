var app = angular.module('products_app', ['ngAnimate']);

var fd = new FormData(); //used for an early checking of the image
var data = new FormData(); //used for sending data of a product to the server

var fields = { //used for validating form fields
	'price' : false,
	'discount' : false,
	'quantity' : false
};

//checks if the objects fields has all its properties 
function check_fields(fields){
	var ok = true;

	angular.forEach(fields, function(value, key){
		if(!value){
			ok = false;
			return false;
		}		
	});	

	return ok;
}

function modifyFields(fields, val){
	angular.forEach(fields, function(value, key){
		fields[key] = val;		
	});	
}

//load an already existing image in the modal form
function loadImage(imagePath){

	data.delete('image');
	
	$('.image-picker img').attr({'src': imagePath});

	//make the + icon go in the corner of the image picker
	$('.image-picker span').animate({
		top : 5,
		left:5
	}, 300);

	var img_height = $('.image-picker img').outerHeight() + 4;
			 
	//animate the height of the image container    
	$('.image-picker').animate({
		width: 'auto',
		height: img_height
	}, 300);

	//make the remove button visible
	$('.image-picker .close').fadeIn(300);
}

//remove image from form
function removeImage(){
	//var img = document.getElementById("product-image");

	data.delete('image');

	$('.image-picker img').attr({ src: ''});
			
	$('.image-picker .close').fadeOut(300);
	$('.image-picker').animate({
		height: 200
	}, 300);

	var width = $('.image-picker').width()/2 - 16;

	$('.image-picker span').animate({
		top: 84,
		left: width
	});

}

//preview the image that has been chosen for the product
function imagePreview(imageFile){
	data.append('image', imageFile); //append it to a FormData object

	console.log(imageFile);

	//hide previous warning message
	$('.warnings').animate({
	    maxHeight: 0,
	    opacity: 0
	}, 300);

	//actually preview the image
	var preview = new FileReader();

	preview.onload = function(e){

		//set the src attribute of the img tag
		$('.image-picker img').attr({'src' : e.target.result});

		//make the + icon go in the corner of the image picker
		$('.image-picker span').animate({
			top : 5,
			left:5
		}, 300, function(){
			//Get the height of the image
			var img_height = $('.image-picker img').outerHeight() + 4;

			//animate the height of the image container    
			$('.image-picker').animate({
				width: 'auto',
				height: img_height
			}, 300);

			console.log( $('.image-picker img').outerHeight() );
		});

		

		//make the remove button visible
		$('.image-picker .close').fadeIn(300);
	}

	preview.readAsDataURL(imageFile);
	
}

//Controller for loading all the products in the table
app.controller('load_products', function($scope, $http, productShare){

	//variable that holds how many products should be displayed in the table
	$scope.display = 20;

	//get all the products
	$http.get('get_products').then(function(response){
		$scope.products = angular.fromJson(response.data);

		for(var i = 0; i < $scope.products.length; i++){
			var _this = $scope.products[i];
			//console.log(_this);
			
			if( _this.discount){
				$scope.products[i].discounted_price = _this.price * (100 - _this.discount)/100;
			} 
		}
		//console.log(typeof $scope.products[0].displayed);
	},	
	function(err){
		console.log(err);
	});

	//funtion that searches the product in the array of products and returns it
	//if it doen't find any products with that id return false
	function searchProduct(id){
		for (var i = $scope.products.length - 1; i >= 0; i--) {
			if($scope.products[i].id === id)
				return $scope.products[i];
		};
		return false;
	}

	function removeProduct(id){
		for (var i = $scope.products.length - 1; i >= 0; i--) {
			if($scope.products[i].id === id){
				$scope.products.splice(i, 1);
				return true;
			}
		};
		return false;
	}

	//function that is fired when the "add product " button is pressed
	$scope.addProduct = function(){
		productShare.addNewProduct();
	}

	//function that fires when the update button for a product is pressed
	$scope.updateProduct = function(id){
		var product = searchProduct(id);
		if(product){
			productShare.updateProduct(product);
		}
	}

	//function that is fired when the "remove product " button is pressed
	$scope.deleteProduct = function(id){
		var product = searchProduct(id);
		if(product){
			productShare.removeProduct(product);
		}
	}

	//insert the new product in the array of products
	$scope.$on('handleSharing', function(){
		$scope.products.push(productShare.data);
	});

	//update the data of a product in the table
	$scope.$on('updateInTable', function(){
		for(var i = 0; i < $scope.products.length; i++){
			if($scope.products[i].id == productShare.data.id){

				//load the new data
				angular.forEach(productShare.data, function(value, key){
					$scope.products[i][key] = productShare.data[key];
				});
				break;
			}
		}
	});

	//remove a product from the table
	$scope.$on('removeFromTable', function(){
		removeProduct(productShare.id);
	});
	
});

//Controller for adding a new product in the database/updating an already existing product
app.controller('addProduct', function($scope, $http, productShare){

	$scope.header = "Add new product to the database";
	$scope.buttonText = "Add product";
	$scope.category = $scope.subCategory = null;
	$scope.sub_categories = [];

	var update = false; //if true, the form in the modal will be used for updating a specific product,
						// otherwise for inserting a new one

	//Get all the categories and the subcategories
	$http.get('get_categories').then(
		function(response){
			$scope.categories = response.data;
			for(var i = 0; i < $scope.categories.length; i++){
				$scope.categories[i].sub_category = angular.fromJson($scope.categories[i].sub_category);
			}
			//console.log($scope.categories);
		}, function(error){
			console.log(error);
		});

	$scope.category_change = function(){
		$scope.subCategory = null;

		for(var i = 0; i < $scope.categories.length; i++){
			if($scope.categories[i].category === $scope.category){
				var index = i;
				break;
			}

		}
		if(typeof index != 'undefined'){
			$scope.sub_categories = $scope.categories[index].sub_category;
		}
		else{
			$scope.sub_categories = [];
		}
		console.log(index);
	}

	
	$(document).ready(function(){

		//Send the image to the back-end for checking whenever a new one is picked
		$('#product-image').change(function(){
			var img = document.getElementById("product-image");
			
			if(typeof  img.files[0] != 'undefined'){
				fd.append('image', img.files[0]);

				$http.post('image_check', fd, {
	                transformRequest: angular.identity,
	                headers: {'Content-Type': undefined}
	            })
	            	.success(function(response){
	            		response = angular.fromJson(response);
	            		
	               		if(response)
	               			imagePreview(img.files[0]);
	               		else
	               			imageWarnings();
	               	})
	               	.error(function(){
	               		
	               	});
			}
		});

		$('.image-picker .close').click(function(){
			removeImage();
		});

	});

	//Price evaluation
	$scope.price_check = function(){
		$scope.price = parseFloat($scope.price) ? parseFloat($scope.price) :  $scope.price;
		
		if(typeof $scope.price != 'undefined' ){
			if( $scope.price < 0 || !angular.isNumber($scope.price)){
				fields.price = false;
				$('.modal-body .price-control').addClass('has-warning');
				$('.modal-body .price-control .help-block').animate({
					maxHeight: 50,
					marginTop: 5,
					marginBottom: 10
				}, 200);
			}
			else{
				fields.price = true;
				$('.modal-body .price-control .help-block').animate({
					maxHeight: 0,
					margin: 0
				}, 200);
				$('.modal-body .price-control').removeClass('has-warning');
			}
		}
		else{
			$('.modal-body .price-control .help-block').animate({
				maxHeight: 0,
				margin: 0
			}, 200);
			$('.modal-body .price-control').removeClass('has-warning');
		}
		

	}

	//Discount evaluation
	$scope.discount_check = function(){
		$scope.discount = parseFloat($scope.discount) ? parseFloat( $scope.discount ) : $scope.discount;

		if(typeof $scope.discount != 'undefined'){
			
			if($scope.discount >= 0 && $scope.discount < 100 && angular.isNumber( $scope.discount)){
				fields.discount = true;
				$('.modal-body .discount-control .help-block').animate({
					maxHeight: 0,
					margin: 0
				}, 200);
				$('.modal-body .discount-control').removeClass('has-warning');
			}
			else{
				fields.discount = false;
				$('.modal-body .discount-control .help-block').animate({
					maxHeight: 50,
					marginTop: 5,
					marginBottom: 10
				}, 200);
				$('.modal-body .discount-control').addClass('has-warning');
			}
			if(!angular.isNumber( $scope.discount) && !$scope.discount.length){
				$('.modal-body .discount-control .help-block').animate({
					maxHeight: 0,
					margin: 0
				}, 200);
				$('.modal-body .discount-control').removeClass('has-warning');
			}
		}
		
	}

	//Quantity evaluation
	$scope.quantity_check = function(){
		$scope.quantity = parseFloat($scope.quantity) ? parseFloat( $scope.quantity ) : $scope.quantity;

		if(typeof $scope.quantity != 'undefined'){
			if(!angular.isNumber($scope.quantity)){
				fields.quantity = false;
				$('.modal-body .quantity-control .help-block').animate({
					maxHeight: 50,
					marginTop: 5,
					marginBottom: 10
				});
				$('.modal-body .quantity-control').addClass('has-warning');
			}
			else if($scope.quantity < 0){
				fields.quantity = false;
				$('.modal-body .quantity-control .help-block').animate({
					maxHeight: 50,
					marginTop: 5,
					marginBottom: 10
				});
				$('.modal-body .quantity-control').addClass('has-warning');
			}
			else{
				fields.quantity = true;
				$('.modal-body .quantity-control .help-block').animate({
					maxHeight: 0,
					margin: 0
				}, 200);
				$('.modal-body .quantity-control').removeClass('has-warning');
			}
		}
		else{
			$('.modal-body .quantity-control .help-block').animate({
				maxHeight: 0,
				margin: 0
			}, 200);
			$('.modal-body .quantity-control').removeClass('has-warning');
		}
	}

	//Function which executes when the 'add product' form is submitted
	$scope.add_product = function(){
		var product_data = {
			'id' : $scope.id,
			'name' : $scope.name,
			'manufacturer' : $scope.manufacturer,
			'category': $scope.category,
			'subCategory' : $scope.subCategory,
			'price' : $scope.price,
			'discount': $scope.discount ? $scope.discount : 0,
			'quantity' : $scope.quantity
		}
		
		angular.forEach( product_data, function(value, key){
			data.append(key, value);
		});

		console.log( fields );

		if(!$scope.discount)
			fields.discount = true;

		if( $scope.id.length && $scope.name.length && $scope.manufacturer.length && $scope.price && $scope.quantity && $scope.category
				&& $scope.subCategory && check_fields(fields) ){

			if(!update){
				$http.post('add_product', data,{
					transformRequest: angular.identity,
			        headers: {'Content-Type': undefined}
				})
					.then(function(response){
						var resp = angular.fromJson(response.data);
						console.log(resp);
						if(!resp){
							infoMessage('There is already a product with the same id.', 'alert-danger');
						}
						else{
							productShare.prep_product(resp);
							
							$('#modal').modal('hide');
							infoMessage('Product added to database.', 'alert-success');

							$scope.id = $scope.name = $scope.manufacturer = $scope.price = $scope.discount = $scope.quantity = '';
							$scope.category = $scope.subCategory = null;
				
						}	
					},
					function(error){
						console.log(error);
					});	
			}
			else{
				$http.post('update_product', data, {
					transformRequest: angular.identity,
			        headers: {'Content-Type': undefined}
				})
				.then(function(response){
					var resp = angular.fromJson(response.data);

					switch(resp){
						case 'unauthorized':
							infoMessage('You do not have permission to do this action', 'alert-danger');
							break;
						case false:
							infoMessage('There was an error updating the data of this product!', 'alert-danger');
							break;
						default: 
							if(typeof resp === 'object'){

								//update in the table
								productShare.updateTable(resp);

								$('#modal').modal('hide');
								
								infoMessage('Product updated successfully!', 'alert-success');
							
							}
					}
					console.log(resp);
				},
				function(error){
					console.log(error.data);
				});
			}
		}
		else{
			infoMessage('All fields must be completed!', 'alert-danger');
		}
	}

	$scope.$on('sendExistingProduct', function(){

		$scope.header = "Update this product";
		$scope.buttonText = "Update product";

		$('.idInput').prop({
			disabled : true
		});

		update = true;
		modifyFields(fields, true);

		//load the data for the product that you want to update in the form
		$scope.name = productShare.data.product_name;
		$scope.id = productShare.data.id;
		$scope.manufacturer = productShare.data.manufacturer;

		$scope.category = productShare.data.category;
		$scope.category_change();
		$scope.subCategory = productShare.data.sub_category;

		$scope.price = productShare.data.price;
		$scope.discount = productShare.data.discount;
		$scope.quantity = productShare.data.quantity;

		$('#modal').modal('show');

		$('#modal').on('shown.bs.modal', function(){
			if(update)
				loadImage('../' + productShare.data.image_path);
		});
	});
	
	$scope.$on('clickedAddProduct', function(){
		if(update){
			//form setup
			$scope.header = "Add new product to the database";
			$scope.buttonText = "Add product";

			update = false;
			modifyFields(fields, false);

			//make the id input editable
			$('.idInput').prop({
				disabled : false
			});

			//remove any data that is on the form
			$scope.id = $scope.name = $scope.manufacturer = $scope.price = $scope.discount = $scope.quantity = '';
			$scope.category = $scope.subCategory = null;

			//remove the image from the form if there is one already loaded
			removeImage();

		}

		$('#modal').modal('show');	
	});
});

