app.controller('usersForm', function($scope, $http, shareEmployees){
	
	var update = false, an_bisect = false, i, imageObject = new FormData(), employeeData = new FormData();
	var validatingFields = {
			'first_name' : false,
			'last_name' : false,
			'phone_number' : false,
			'cnp' : false,
			'password' : false
		};

	$scope.formHeader = 'Create a new employee account';
	$scope.subHeader = 'Insert the data for the new employee';
	$scope.buttonText = 'Add employee';
	$scope.displayPassInput = true;

	$scope.position = $scope.birthdate_day = $scope.birthdate_month = $scope.birthdate_year = null;	
	$scope.positions = $scope.years = [];

	$scope.months = [];
	$scope.days = [];

	$scope.limitDays = 31;

	//load days
	for( i = 1; i<=31 ;i++){
		$scope.days.push(i);
	}
		
	//load months
	var months = ['ianuarie', 'februarie', 'martie', 'aprilie', 'mai', 'iunie', 'iulie' ,'august', 'septembrie',
							'octombrie', 'noiembrie', 'decembrie' ];

	for(i = 0; i < months.length; i++)
		$scope.months.push( {'month_number' : i+1, 'month_name' : months[i]});

	//load years
	for( i = 1917; i <= 2003; i++)
		$scope.years.push(i);

	$scope.change_month = function(){
		switch($scope.birthdate_month){
			case '1':
			case '3':
			case '5':
			case '7':
			case '8':
			case '10':
			case '12':
				$scope.limitDays = 31;
				break;
			case '2':
				if(an_bisect)
					$scope.limitDays = 29;
				else
					$scope.limitDays = 28;
				break;
			default:
				$scope.limitDays = 30;
				break;
		}
	}

	$scope.change_year = function(){
		if($scope.birthdate_year % 4 === 0)
			an_bisect = true;
		else
			an_bisect = false;
		if($scope.birthdate_year)
			$scope.change_month();
	}

	$scope.validate_firstName = function(){
		if(onlyLetters($scope.first_name)){
			formWarnings('hide', 0);
			validatingFields.first_name = true;
		}	
		else{
			validatingFields.first_name = false;
			formWarnings('show', 0);
		}
			
	}

	$scope.validate_lastName = function(){
		if(onlyLetters($scope.last_name)){
			validatingFields.last_name = true;
			formWarnings('hide', 1);
		}
		else{
			validatingFields.last_name = false;
			formWarnings('show', 1);
		}
	}

	$scope.validate_phoneNumber = function(){
		if(onlyDigits($scope.phone_number)){
			validatingFields.phone_number = true;
			formWarnings('hide', 3);
		}
		else{
			validatingFields.phone_number = false;
			formWarnings('show', 3);
		}

	}

	$scope.validate_cnp = function(){
		
		if((onlyDigits($scope.cnp) && ($scope.cnp.length === 13)) ||(!$scope.cnp.length)) {
			validatingFields.cnp = true;
			formWarnings('hide', 5);
		}
		else{
			validatingFields.cnp = false;
			formWarnings('show', 5);
		}
	}

	$scope.validate_password = function(){
		if(( ($scope.password.length > 0 && $scope.password.length < 8) || $scope.password.length > 16)
		 	|| onlyDigits($scope.password) || onlyLetters($scope.password)){
			validatingFields.password = false;
			formWarnings('show', 7);
		}
		else{
			validatingFields.password = true;
			formWarnings('hide', 7);
		}
	}

	$(document).ready(function(){

		$('#employee-image').change(function(event){
			var file = event.target.files[0];
			
			if(typeof  file != 'undefined'){
				imageObject.append('image', file);

				$http.post('image_check', imageObject, {
	                transformRequest: angular.identity,
	                headers: {'Content-Type': undefined}
	            })
	            	.success(function(response){
	            		response = angular.fromJson(response);
	            		
	               		if(response){
	               			imagePreview(file);
	               			employeeData.append('image', file); //append it to a FormData object
	               		}               			
	               		else
	               			imageWarnings();
	               	})
	               	.error(function(){
	               		console.log(error.data);
	               	});
			}
		});

		$('.image-picker .close').click(function(){
			employeeData.delete('image');
			removeImage();
		});
	});

	//load positions
	$http.get('positions').
			then(function(response){
				var resp = angular.fromJson(response.data);
				console.log(resp);

				$scope.positions = resp;
			},
			function(error){
				console.log(error.data);
			});

	function clearForm(){
		$scope.first_name = $scope.last_name = $scope.email = $scope.phone_number = $scope.cnp = $scope.password = '';
		$scope.position = $scope.birthdate_day = $scope.birthdate_month = $scope.birthdate_year = null;

		employeeData.delete('image');
		removeImage();
	}

	$scope.submit_data = function(){
		var dataToBeSent = {
			'first_name' : $scope.first_name,
			'last_name' : $scope.last_name,
			'email' : $scope.email,
			'phone_number': $scope.phone_number,
			'cnp' : $scope.cnp,
			'position' : $scope.selectedPosition,
			'password' : $scope.password,
			'birthdate' : {
				'birthdate_day' : $scope.birthdate_day,
				'birthdate_month' : $scope.birthdate_month,
				'birthdate_year' : $scope.birthdate_year
			}
		};

		var completedForm = true;

		console.log(dataToBeSent);

		angular.forEach(dataToBeSent, function(value, key){
			if(typeof value === 'undefined')
				completedForm = false;
			else if( value === null)
				completedForm = false;
			else if( value === '')
				completedForm = false;
		});

		console.log(completedForm);

		if(!update){
			if(completedForm){
				if(validFields(validatingFields)){

					dataToBeSent.birthdate = dataToBeSent.birthdate.birthdate_year.toString() + '-'
												+ dataToBeSent.birthdate.birthdate_month.toString() + '-'
												+ dataToBeSent.birthdate.birthdate_day.toString();

					angular.forEach(dataToBeSent, function(value, key){
						employeeData.append(key, value);
					});

					$http.post('add_employee', employeeData, {
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
									infoMessage('There was an error adding this employee to the database!', 'alert-danger');
									break;
								default: 
									if(typeof resp === 'object'){

										//update in the table
										shareEmployees.addInTable(resp);

										$('#modalForm').modal('hide');
										
										infoMessage('Employee added successfully!', 'alert-success');
										clearForm();
									}
							}
					console.log(resp);
				},
				function(error){
					console.log(error.data);
				});
				}
			}
			else
				infoMessage('All the fields must be completed.', 'alert-danger');
		}
		else{

		}
	}
	$scope.$on('clickedAdd', function(){
		update = false;

		$scope.formHeader = 'Create a new employee account';
		$scope.subHeader = 'Insert the data for the new employee';
		$scope.buttonText = 'Add employee';
		$scope.displayPassInput = true;

		clearForm();
		$('#modalForm').modal('show');
	});

	$scope.$on('updateRequest', function(){
		update = true;

		$scope.formHeader = 'Update data of this employee';
		$scope.subHeader = 'Edit data';
		$scope.buttonText = 'Update';
		$scope.displayPassInput = false;

		angular.forEach( shareEmployees.data, function(value, key){
			if(key !== 'birthdate')
				$scope[key] = value;
		});

		$scope.birthdate_day = shareEmployees.data.birthdate.birthdate_day;
		$scope.birthdate_month = shareEmployees.data.birthdate.birthdate_month
		$scope.birthdate_year = shareEmployees.data.birthdate.birthdate_year;

		$('#modalForm').modal('show');

		$('#modalForm').on('shown.bs.modal', function(){
			if(update)
				loadImage("../" + shareEmployees.data.image_path);
		});
	});
});