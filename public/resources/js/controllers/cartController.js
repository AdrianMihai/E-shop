app.factory('addToCart', function($rootScope){
	var addToCart = {};

	addToCart.addQuantity = function(product, pickedQuantity){
		product.picked_quantity = pickedQuantity;
		product.final_price = product.discounted_price ? product.discounted_price : product.price;
		this.data = product;
		this.addProduct();
	}

	addToCart.prep_product = function(product){
		product.picked_quantity = 1;
		product.final_price = product.discounted_price ? product.discounted_price : product.price;
		this.data = product;
		this.addProduct();
	}
	addToCart.addProduct = function(){
		$rootScope.$broadcast('addingProduct');		
	}
	return addToCart;
});

app.controller('cart_contents', function($scope, $http, addToCart){
	$scope.cartProducts = $scope.quantites = [];

	$scope.totalSum = 0;

	$scope.shippingPrices = {};

	function modifySum(index, operation){
		if(operation == '+')
			$scope.totalSum += parseFloat(
								($scope.cartProducts[index].final_price * $scope.cartProducts[index].picked_quantity).toFixed(2) );
		else if(operation == '-')
			$scope.totalSum -= parseFloat(
								($scope.cartProducts[index].final_price * $scope.cartProducts[index].picked_quantity).toFixed(2) );

		else
			return false;

		$scope.totalSum = parseFloat(($scope.totalSum).toFixed(2) );
		return true;
	}

	$http.get('/EShop/public/shipping_prices')
			.then(function(response){
				var resp = angular.fromJson(response.data);

				$scope.shippingPrices = resp;

				console.log($scope.shippingPrices);
			},
			function(error){
				console.log(error.data);
			});
			
	$http.get('/EShop/public/cartContents').then(function(response){
		$scope.cartProducts = response.data;
		for(var i = 0; i < $scope.cartProducts.length; i++){

			$scope.cartProducts[i].image_path = '/EShop/public/' + $scope.cartProducts[i].image_path;
			$scope.quantites[$scope.cartProducts[i].id] = $scope.cartProducts[i].picked_quantity;

			modifySum(i, '+');
			//$scope.totalSum += $scope.cartProducts[i].picked_quantity * $scope.cartProducts[i].final_price;
		}
		if($scope.cartProducts.length == 0)
			hide_cart();
		console.log($scope.cartProducts);

	});

	//Make a post request to save the new product to session
	$scope.$on('addingProduct', function(){
		$http({
			method: 'post',
			url: '/EShop/public/addToCart',
			headers: {
				'ContentType' : undefined
			},
			data: addToCart.data
		})
		.then(function(response){
			var resp = angular.fromJson(response.data);
			console.log(resp);
			if(resp){
				response.data.image_path = '/EShop/public/' + response.data.image_path;
				$scope.cartProducts.push(response.data);
				$scope.quantites[$scope.cartProducts[$scope.cartProducts.length - 1].id] = response.data.picked_quantity;

				//Modify total sum
				modifySum($scope.cartProducts.length - 1, '+');

				showCartMessage( response.data.product_name,
								 response.data.image_path,
								 'was <span class="text-success"> added </span> to your <a href="cart"> shopping cart </a> .'
								);
				if($scope.cartProducts.length == 1)
					show_cart();
			}
			else{
				showCartMessage( addToCart.data.product_name,
								 '/EShop/public/' + addToCart.data.image_path,
								 'is <span class="text-weak"> already </span> in your <a href="cart"> shopping cart </a> .'
								);
			}
				
		});
	});

	//Removing an item from the cart
	$scope.removeItem = function(index){
		$http({
			method: 'post',
			url: '/EShop/public/removeFromCart',
			headers: {
				'ContentType' : undefined
			},
			data: {'id' : $scope.cartProducts[index].id}
		}).then(function(response){
			if(response.data){
				showCartMessage( $scope.cartProducts[index].product_name,
								 $scope.cartProducts[index].image_path,
								 'was <span class="text-bad"> removed </span> from your <a href="cart"> cart </a> .'
								);
				//Modify the total sum
				modifySum(index, '-');

				//Remove product from array
				$scope.cartProducts.splice(index, 1);

				if($scope.cartProducts.length == 0)
					hide_cart();
			}
		});
	}

	//Modify the quantity for the specific product
	$scope.changeQuantity = function(index){
		console.log($scope.quantites[$scope.cartProducts[index].id]);
		if($scope.quantites[$scope.cartProducts[index].id]){
			$http({
				method : 'post',
				url : '/EShop/public/updateCart',
				headers: {
					'ContentType' : undefined
				},
				data : {'id' : $scope.cartProducts[index].id, 'quantity' : $scope.quantites[$scope.cartProducts[index].id]}
			}).then(function(response){
				var resp = angular.fromJson(response.data);
				console.log(resp);
				if(resp){
					modifySum(index, '-');
					$scope.cartProducts[index].picked_quantity = resp;
					$scope.quantites[$scope.cartProducts[index].id] = resp;
					modifySum(index, '+');
				}
				else{
					modifySum(index, '-');
					$scope.cartProducts[index].picked_quantity = 1;
					$scope.quantites[$scope.cartProducts[index].id] = $scope.cartProducts[index].picked_quantity;
					modifySum(index, '+');
				}
			});
		}
		else{
			$scope.cartProducts[index].picked_quantity = 1;
			$scope.quantites[$scope.cartProducts[index].id] = $scope.cartProducts[index].picked_quantity;
		}
		
	}

	//Hover animation for summary list
	$scope.color_element = function(index){
		var element = $('.cart-summary ol li').eq(index);
		element.addClass('active', 180);
	}

	$scope.remove_color = function(index){
		var element = $('.cart-summary ol li').eq(index);
		element.removeClass('active', 180);
	}
});