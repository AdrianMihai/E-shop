var app = angular.module('logsApp', []);

app.controller('loadLogs', function($scope, $location, $http){

	$scope.header = {
		'employees' : false,
		'products' : false,
		'orders' : false
	};

	$scope.allLogs = [];

	var requestUrl, currentCategory = $location.$$absUrl.split('/');

	currentCategory = currentCategory[currentCategory.length-1];

	requestUrl = currentCategory + '/get_them';

	angular.forEach($scope.header, function(value, key){
		if(key === currentCategory)
			$scope.header[key] = true;
	});

	$http.get(requestUrl)
			.then(function(response){
				var resp = angular.fromJson(response.data);
				$scope.allLogs = resp;
				//console.log(resp);
			},
			function(error){
				console.log(error.data);
			});
});