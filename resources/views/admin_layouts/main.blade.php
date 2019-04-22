<!DOCTYPE html>
<html>
	<head>
		<title> E-Shop Admin Page</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="csrf-token" content="{{ csrf_token() }}" />

		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../resources/views/css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../resources/views/css/stylesheets/admin.css">
		
		<script type="text/javascript" src="../public/resources/js/node_modules/jquery/dist/jquery.js"></script>
		<script type="text/javascript" src="../public/resources/js/node_modules/jquery-ui-1.11.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../public/resources/js/node_modules/angular-1.5.7/angular.min.js"></script>
		<script type="text/javascript" src="../resources/views/css/javascripts/bootstrap.js"></script>
		
		<script type="text/javascript" src="../public/resources/js/admin/app.js"></script>
	</head>
	<body ng-app="admin_app">
		<h1 class="logo text-center"> E-Shop admin</h1>
		<div class="login-form container">
			<div class="row">
				<h3 class="col-xs-12"> Log in with your admin account </h3>
			</div>
			<hr>
			<div class="row">
				<p class="col-xs-12 warning-message">There is no account registered with this email.</p>
			</div>
			<div class="row" ng-controller="login">
				<form class="form" ng-submit="login_submit()">
					{{ csrf_field() }}
					<div class="form-group col-xs-12">
						<input class="form-control" type="text" name="email" placeholder="Email" autocomplete="off" ng-model="email" required/>
					</div>
					<div class="form-group col-xs-12">
						<input class="form-control"
								type="password" name="password" placeholder="Password" autocomplete="off" ng-model="password"  maxlength="18" required/>
					</div>
					<div class="checkbox col-xs-12">
					    <label>
					      <input type="checkbox" name="remember" ng-model="remember"> Remember me
					    </label>
					</div>
					<div class="form-group col-xs-4 ">
						<button type="submit" class="btn btn-default"> Log in</button>
					</div>
					<div class="forgot-password col-xs-8 text-right">
						<a href="/admin"> Forgot your password? </a> 
					</div>
				</form>
			</div>

		</div>
		<div class="create-acc panel panel-default">
			<div class="panel-body">
				<a href="#" data-toggle="modal" data-target="#signup-admin"> Create an employee account </a>
			</div>
		</div>

		<div class="modal fade" id="signup-admin" role="dialog">
		    <div class="modal-dialog">
		    
			    <!-- Modal content-->
			    <div class="modal-content">
			        <div class="modal-header">
			          	<button type="button" class="close" data-dismiss="modal">&times;</button>
			          	<h4 class="modal-title">Create an employee account</h4>
			        </div>
			        <div class="modal-body">
						<div class="alert alert-danger">
							<a href="#" class="close" aria-label="close">&times;</a>
						  	The <strong>password </strong>is wrong. 
						</div>
			          	<form class="admin-pass" method="POST">
			          		<div class="form-group">
							    <label>Enter the administrator password</label>
							    <div class="input-group">
							    	<input type="password" class="form-control"  placeholder="Password" required />
							    	<div class="input-group-btn">
							    		<button type="submit" class="btn btn-default">
							    			<span class="glyphicon glyphicon-arrow-right"></span>
							    		</button>
							    	</div>
							    </div>
							</div>
			          	</form>
			          	
			        </div>
			        <div class="modal-footer">
			          	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        </div>
			      </div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				var admin_password = "{{$admin_pass}}";

				$(".login-form").animate({
					top: "0px",
					opacity: "1",
					maxHeight: 400
				}, 500);

				//setup headers for laravel CSRF token
				$.ajaxSetup({
        			headers: {
            			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        			}
    			});

				var signup_form = 
			          					'<div class="form-group"> ' +  
			          						'<label>Full Name of your employee</label>' + 
			          						'<input type="text" class="form-control" placeholder="Name" required /> </div>' + 
			          					 '<div class="form-group"> <label>Email of your employee</label>' + 
			          						'<input type="text" class="form-control" placeholder="Email" required /> </div>' + 
						          		'<div class="form-group"> <label>Choose a password for this employee account.</label>' + 
						          			'<input type="text" class="form-control" placeholder="Password" required /> </div>'+
						          		'<div class="form-group"> <label>Enter the password again</label>' + 
						          			'<input type="text" class="form-control" placeholder="Repeat the password" required /></div>'+
						          		'<button class="btn btn-default"> Create account</button>';
				$('.admin-pass').submit(function(){
					var password = $(".admin-pass .form-control").val();
					if(password === admin_password){
						$(".modal-body .alert").fadeOut(300);
						$('.admin-pass').fadeOut(300, function(){
							$(signup_form).appendTo('.modal-body');
							$('.signup-form').fadeIn(300);

						});
						
					}	
					else{
						$(".modal-body .alert").fadeIn(300);
						$(".admin-pass .form-control").val('');
					}
					return false;
				});
				
			});
		</script>
	</body>
</html>