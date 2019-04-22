var app = angular.module("admin_app", []);

app.controller("login", function($scope, $http){

	$scope.login_submit = function(){
		if($scope.email.length != 0 && $scope.password.length != 0){
			var data = {'email': $scope.email, 'password': $scope.password, 'remember': $scope.remember || false} ;

			var post = $.post("admin/login", data);
			post.done(function(data){
				console.log(data);
				switch(data){
					case 'email':
						$('.warning-message').text('There is no account registered with this mail.')
											.animate({
												maxHeight: 46
											},300);
						break;
					case 'password':
						$('.warning-message').text('Email and password do not match.')
											.animate({
												maxHeight: 46
											},300);
						break;
					default:
						$('.warning-message').animate({
												maxHeight: 0
											},300);
						location.assign("admin/dashboard");
						break;
				}
			});

		}
	}
});