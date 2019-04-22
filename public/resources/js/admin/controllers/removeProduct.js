app.controller('deleteProduct', function($scope, $http, productShare){

	var id = 0, auto_id = 0;

	$scope.productId = 666;
	$scope.productImage = 'resources/productsImages/basic.jpg';
	$scope.productName = 'Nume de produs';

	$scope.$on('removeExistingProduct', function(){
		console.log(productShare.data);

		auto_id = productShare.data.auto_id;

		$scope.productId = id = productShare.data.id;
		$scope.productImage = productShare.data.image_path;
		$scope.productName = productShare.data.product_name;

		$('#deleteConfirmation').modal('show');
	});

	//send the id of the product to the server in order to be removed  
	$scope.sendId = function(){
		if(id && auto_id){
			var data = new FormData();
			data.append('id' , id);
			data.append('auto_id' , auto_id);

			$http.post('remove_product',
						 data,
						 {  transformRequest: angular.identity,
			       			headers: {'Content-Type': undefined}
						})
				.then(function(response){
					var resp = angular.fromJson(response.data);

					switch(resp){
						case 'unauthorized':
							infoMessage('You do not have permission to remove a product!', 'alert-danger');
							break;
						case false:
							infoMessage('There is no product with the given id!', 'alert-danger');
							break;
						default:

							$('#deleteConfirmation').modal('hide');
							infoMessage('Product successfully removed!', 'alert-success');

							//remove it from the table also
							productShare.deleteFromTable(id);
					}
					console.log(resp);

				}, function(error){
					console.log(error);
				});
		}
	}
});