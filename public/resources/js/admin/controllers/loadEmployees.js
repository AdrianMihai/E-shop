
//check if the text only contains numbers
//returns true/false
function onlyDigits(text){
	var patt = /[^0-9]/g;

	return !patt.test(text);
}

//check if the text contains only letters
// returns true/false
function onlyLetters(text){
	var patt = /[^a-z^A-Z]/g;

	return !patt.test(text);
}

function validFields(fields){
	var ok = true;
	angular.forEach(fields, function(value, key){
		console.log(value);
		if(value === false){
			ok = false;
			return false;
		}
	});

	return ok;
}

//preview the image that has been chosen for the product
function imagePreview(imageFile){

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

//load an already existing image in the modal form
function loadImage(imagePath){
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

//displays/hides the warnings in the form
function formWarnings(action, index){
	var group = $('#modalForm form .form-group').eq(index);
	var help_block = group.find('.help-block');

	if(action === 'show'){
		group.addClass('has-warning');
		help_block.stop().animate({
			maxHeight: 100
		}, 400);
	}
	else if(action === 'hide'){
		group.removeClass('has-warning');
		help_block.stop().animate({
			maxHeight: 0
		}, 400);
	}
}

//formWarnings('show', 0);

app.controller('loadEmployees', function($scope, $http, shareEmployees){

	$scope.employees = [];

	//load the employees
	$http.get('get_employees')
			.then(function(response){
				var resp = angular.fromJson(response.data);
				$scope.employees = resp;
			});

	$scope.clickedAddButton = function(){
		shareEmployees.clickedAdd();
	}

	$scope.clickedUpdateButton = function(index){
		var birthdate = new Date($scope.employees[index].birthdate * 1000);

		console.log(birthdate);
		var employeeData = {
			'first_name' : $scope.employees[index].first_name,
			'last_name' : $scope.employees[index].last_name,
			'phone_number': $scope.employees[index].phone_number,
			'email' : $scope.employees[index].email,
			'position': $scope.employees[index].position,
			'cnp' : $scope.employees[index].cnp,
			'birthdate': {
					'birthdate_day': birthdate.getUTCDay(),
					'birthdate_month' : birthdate.getUTCMonth(),
					'birthdate_year' : birthdate.getFullYear()
				},
			'image_path' : $scope.employees[index].image_path
			};
		console.log(employeeData);

		shareEmployees.clickedUpdate(employeeData);
	}

	$scope.$on('addedNewProduct', function(){
		$scope.employees.push( shareEmployees.data);
	});
});