app.factory('shareEmployees', function($rootScope){
	var shareEmployees = {};

	shareEmployees.clickedAdd = function(){
		$rootScope.$broadcast('clickedAdd');
	}

	shareEmployees.addInTable = function(employeeData){
		this.data = employeeData;
		$rootScope.$broadcast('addedNewProduct');
	}

	shareEmployees.clickedUpdate = function(employeeData){
		this.data = employeeData;
		$rootScope.$broadcast('updateRequest');
	}
	return shareEmployees;
});