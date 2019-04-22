var app = angular.module('signupApp', []);

var pass_check = {
	'number' : false,
	'uppercase' : false,
	'specialchars' : false,
	'length' : false
}
function hide_message(){
	$('.modal-body .message').animate({
		top:-5,
		left:-5,
		maxHeight: 0,
		marginBottom: 0,
		opacity: 0.25
	}, 300);
}
function show_message(text){
	$('.modal-body .message').text(text).animate({
		top:0,
		left:0,
		maxHeight: 50,
		marginBottom: 10,
		opacity: 1
	}, 300);
	setTimeout(hide_message, 10000);
}

function hide_notification(){
	$('.redirect-notification').animate({
		top: 5,
		right:5,
		maxHeight:0,
		opacity: 0.25

	},400);
}
function show_notification(text){
	$('.redirect-notification .text').text(text);
	$('.redirect-notification').animate({
		top: 15,
		right:15,
		maxHeight:60,
		opacity: 1

	},400);

	setTimeout(hide_notification, 8000);
}
function look_for_number(password){
	var patt = /[0-9]/g;

	return patt.test(password);
}
function look_for_space(password){
	var patt = /\s/g;

	return patt.test(password.trim());
}
function look_for_uppercase(password){
	var patt = /[A-Z]/g;

	return patt.test(password);
}
function look_for_specialchars(password){
	var patt = /[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/g;

	return patt.test(password);
}
function calc_strength(password){
	var k = 1;
	pass_check.number = look_for_number(password);
	pass_check.uppercase = look_for_uppercase(password);
	pass_check.specialchars = look_for_specialchars(password);
	pass_check.len = (password.length >= 8 && password.length <= 16) ;

	//console.log(pass_check);
	angular.forEach(pass_check, function(value, key){
		if(value)
			k++;
	});
	if(k == 1)
		return 1;
	else{
		k--;
	}
	if(k == 3 && !pass_check.len)
		return 2;

	return k;
}
function remove_classes(index, text_classes){
	for(var i = 0; i <= 3; i++)
		if(i != index){
			$('.modal-body .pass-str .help-block').removeClass(text_classes[i], 100);
		}
}

app.factory('passwordService', function(){
	var passwordService = {};

	passwordService.check_number = function(password){
		var patt = /[0-9]/i;
		var res = patt.text(password);

		return res;
	}

	return passwordService;
});

//Controller use when creating a new account
app.controller('signupCtrl', function($scope, $http, $timeout){

	var old_score = 0, new_score = 1;
	var text_classes = ['text-bad', 'text-weak', 'text-ok', 'text-success'];

	//Check if the username is already taken or not
	$scope.user_check = function(){
		if($scope.email.length && $scope.username.length ){
			$http({
				method: 'POST',
				url : 'login/email_check',
				headers: {
					'ContentType' : undefined
				},
				data:{'email' : $scope.email, 'username' : $scope.username}
			}).then(function(response){
				switch(response.data){
					case 'username':
						show_message('Username is already taken!');
						break;
					case 'email':
						show_message("Email is already taken!");
						break;
					default:
						hide_message();
						$('.first-form').fadeOut(200, function(){
							$('.second-form').fadeIn(200);
						});
						break;
				}
			});
		}
	}

	//Check if the passwords meets the requirements
	$scope.check_password = function(){
		if(typeof $scope.password == 'undefined'){
			$('.modal-body .pass-str').animate({
				maxHeight: 0
			}, 300);
			old_score = 0;
		}
		else{
			if( $('.modal-body .pass-str').css("maxHeight") === '0px'){
				$('.modal-body .pass-str').animate({
					maxHeight: 100
				}, 300);
			}
			new_score = calc_strength($scope.password) ;
			console.log(new_score);

			if(new_score > old_score){
				for(var i = old_score; i <= new_score; i++){
					/*$('.modal-body .pass-str .indicator:first-child .colored').eq(i).animate({
						width: '100%'
					}, 300);*/
					if(i != 0){
						if(i == 1){
							var selector = $('.modal-body .pass-str .indicator:first-child .colored');
						}
						else{
							var selector = $('.modal-body .pass-str .indicator:nth-child(' + i.toString() + ') .colored' );
						}
						selector.animate({
							width: '100%'
						}, 300);

					}	
				}
			}
			else if( old_score > new_score){
				for(var i = old_score; i > new_score; i--){
					if(i != 0){
						if(i == 1){
							var selector = $('.modal-body .pass-str .indicator:first-child .colored');
						}
						else{
							var selector = $('.modal-body .pass-str .indicator:nth-child(' + i.toString() + ') .colored' );
						}
						selector.animate({
							width: '0%'
						}, 300);
					}	
				}
			}
			if(new_score == 1)
				for(var i = 2; i <= 4; i++)
					$('.modal-body .pass-str .indicator:nth-child(' + i.toString() + ') .colored' ).animate({
							width: '0%'
						}, 100);
			switch(new_score){
				case 1:
					$('.modal-body .pass-str .help-block').text("You password is bad.");
					break;
				case 2:
					$('.modal-body .pass-str .help-block').text("You password is weak.");
					break;
				case 3:
					$('.modal-body .pass-str .help-block').text("You password is ok.");
					break;
				case 4:
					$('.modal-body .pass-str .help-block').text("You password is strong.");
					break;
				default:
					break;
			}

			old_score = new_score;
			new_score--;

			remove_classes(new_score, text_classes);
			$('.modal-body .pass-str .help-block').addClass(text_classes[new_score], 200);
			
		}
	}

	//animations for switching back to the form with username and password
	$scope.go_back = function(){
		$('.second-form').fadeOut(200, function(){
			$('.first-form').fadeIn(200);
		});
	}

	//Submit all the data needed for creating the account
	$scope.final_submit = function(){
		if(calc_strength($scope.password) < 3){
			$('.modal-body .second-form .pass-hints').animate({
				maxHeight: 250,
				opacity: 1,
				marginBottom: 10
			}, 300);
			return false;
		}
		if($scope.password != $scope.r_password){
			show_message("The passwords do not match!");
			return false;
		}
			
		var user_data = {
			'username' : $scope.username,
			'email' : $scope.email,
			'password' : $scope.password
		};
		$http({
			method: 'POST',
			url: 'login/submit_data',
			headers: {
				'ContentType' : undefined
			},
			data: user_data
		}).then(function(response){
			var resp = angular.fromJson(response.data);
			console.log(resp);
			if(resp){
				$(document).ready(function(){
					$('#signup').on('hidden.bs.modal', function(){
						show_notification("Your account has been created!");
						$scope.username = $scope.email = $scope.password = $scope.r_password = '';
					});
				});
				$('#signup').modal('hide');

			}
			else{

			}
		});
	}
});