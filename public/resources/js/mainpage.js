var app = angular.module('mainPageApp', [] );

app.filter('html', function($sce){
    return function(input){
        return $sce.trustAsHtml(input);
    }
});

app.controller('carousel_preview', function($scope, $http){
	$http.get('previewDiscountedProducts')
		 .then(function(response){
		 	$scope.products = response.data[0];
		 	for (var i = $scope.products.length - 1; i >= 0; i--) {
		 		$scope.products[i].numberOfReviews = response.data[1].numberOfReviews ?  response.data[1].numberOfReviews : 0;
		 		$scope.products[i].averageRating = response.data[1][i].average[0].averageGrade;
		 	};
		 	console.log($scope.products);
		 }, function(error){
		 	console.log(error.data);
		 });
});

app.controller('latest_products', function($scope, $http, addToCart){

	$http.get('latestProducts').then(function(response){
		var resp = response.data;
		$scope.undiscounted_products = resp[0];
		$scope.discounted_products = resp[1];
		console.log($scope.undiscounted_products);
	}, function(error){
		console.log(error.data);
	});

	$scope.add_undiscounted = function(index){
		console.log($scope.undiscounted_products[index]);
		addToCart.prep_product($scope.undiscounted_products[index]);
	}
	$scope.add_discounted = function(index){
		console.log($scope.discounted_products[index]);
		addToCart.prep_product($scope.discounted_products[index]);
	}
});

