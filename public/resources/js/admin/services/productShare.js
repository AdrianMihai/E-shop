app.factory('productShare', function($rootScope){
	var productShare = {};

	productShare.data = {};

	//used when adding a new product to database
	productShare.prep_product = function(product){
		this.data = product;
		this.share_product();
	}

	productShare.share_product = function(){
		$rootScope.$broadcast('handleSharing');
	}

	productShare.addNewProduct = function(){
		$rootScope.$broadcast('clickedAddProduct');
	}

	//used when updating a specific product
	productShare.updateProduct = function(product){
		this.data = product;
		this.sendExistingProduct();
	}

	//used when finished updating data for a product and inserting the new data in the table
	productShare.updateTable = function(product){
		this.data = product;
		$rootScope.$broadcast('updateInTable');
	}
	productShare.sendExistingProduct = function(){
		$rootScope.$broadcast('sendExistingProduct');	
	}

	productShare.removeProduct = function(product){
		this.data = product;
		$rootScope.$broadcast('removeExistingProduct');
	}
	
	productShare.deleteFromTable = function(id){
		this.id = id;
		$rootScope.$broadcast('removeFromTable');
	}
	
	return productShare;
});