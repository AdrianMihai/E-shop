<!DOCTYPE html>
<html>
	<head>
		<title> E-Shop Login </title>
		
		@include('components.start-header')
		
		<link rel="stylesheet" type="text/css" href="../resources/views/css/bootstrap-social-gh-pages/assets/css/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="../resources/views/css/bootstrap-social-gh-pages/bootstrap-social.css">
		<link rel="stylesheet" type="text/css" href="../resources/views/css/stylesheets/login.css">
		
		<script type="text/javascript" src="resources/js/signup.js"></script>
		
	</head>

	<body ng-app="signupApp">
		<div class="redirect-notification alert-success container-fluid">
			<div class="row">
				<div class="col-xs-2 text-center">
					<span class="glyphicon glyphicon-ok"></span>
				</div>
				<div class="col-xs-10">
					<div class="row">
						<div class="col-xs-12">
							<span class="text"> Account created ! </span>
						</div>
					</div>
					<!--<div class="row">
						<div class="col-xs-12">
							<span> Redirecting to settings in  <strong> 3 </strong> seconds.</span>
						</div>
					</div>-->
				</div>
				
			</div>
		</div>
		
		<h1 class="logo text-center"> E-Shop </h1>
		<div class="container login-form">
			<div class="row">
				<h3 class="col-xs-12 text-center"> Here you can access your account. </h3>
			</div>
			<hr>
			@if(isset($error_message))
				<div class="row">
					<p class="col-xs-12 text-bad"> {{ $error_message }} </p>
				</div>
				
			@endif

			<div class="row">
				<form class="col-xs-12" action="login" method="POST">
					{{ csrf_field() }}
					<div class="form-group">
						<input class="form-control" type="text" name="email" placeholder="Email" value="{{ old('email') }}" required>
					</div>
					<div class="form-group">
						<input class="form-control" type="password" name="password" placeholder="Password" value="" required>
					</div>
					<div class="checkbox">
					    <label>
					      <input type="checkbox" name="remember"> Remember me
					    </label>
					</div>
						<button type="submit" class="col-xs-4  btn btn-info">Go</button>

						<div class="forgot-password col-xs-8 text-right">
							<a href="/admin"> Forgot your password? </a> 
						</div>
				</form>
			</div>
			<div class="row or">
				<div class="something">
					<span>
						or
					</span>
				</div>
			</div>
			<div class="platforms-text row">
				<span>choose one of the platforms :</span>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<a href="login/facebook" class="text-center btn btn-block btn-social btn-facebook">
						<span class="fa fa-facebook"></span>
						Facebook
					</a>
				</div>
				<div class="col-xs-6">
					<a href="google" class="text-center btn btn-block btn-social btn-google">
						<span class="fa fa-google"></span>
						Google
					</a>
				</div>
			</div>
		</div>

		<div class="create-acc container panel panel-default">
			<div class="panel-body">
				<a href="#signup" data-toggle="modal" data-target="#signup">Create your own account</a>
			</div>
		</div>

		<div class="modal fade" id="signup" role="dialog">
		    <div class="modal-dialog">
		    
			    <!-- Modal content-->
			    <div class="modal-content">
			        <div class="modal-header">
			          	<button type="button" class="close" data-dismiss="modal">&times;</button>
			          	<h4 class="modal-title">Create your own account</h4>
			        </div>
			        <div class="modal-body" ng-controller="signupCtrl">
						<p class="message"> This email is already taken.</p>
			          	<form class="first-form" ng-submit="user_check()">
			          		<div class="form-group">
							    <label>Enter your username.</label>
							    <input type="text" class="form-control"  placeholder="Username" ng-model="username" required />
							</div>
			          		<div class="form-group">
							    <label>And your email.</label>
							    <input type="email" class="form-control"  placeholder="Email" ng-model="email" required />
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-info" >
									Up next
   									<span class="glyphicon glyphicon-arrow-right"></span>
  								</button>
							</div>
			          	</form>
			          	<form class="second-form" ng-submit="final_submit()">
				          	<div class="form-group">
			          			<label> Enter your password </label>
						 		<input type="password" class="form-control" placeholder="Password" ng-model="password"
						 			ng-keyup="check_password()" required/>
						 	</div>
							<div class="form-group">
								<input type="password" class="form-control" placeholder="Repeat Password" ng-model="r_password" />
							</div>
							<div class="pass-str form-group">
								<ul class="list-inline">
									<li class="indicator"> <div class="colored bad"> </div> </li> 
									<li class="indicator"> <div class="colored weak"> </div> </li> 
									<li class="indicator"> <div class="colored ok"> </div> </li> 
									<li class="indicator"> <div class="colored success"> </div> </li> 
								</ul>
								<p class="help-block">Your password is weak.</p>
								<hr>
							</div>
							<div class="pass-hints form-group">
								<p>Password hints:</p>
								<li class="list-group-item list-group-item-info">
									<span class="	glyphicon glyphicon-info-sign"></span>
									 The password must have between 8 and 16 characters
									<!-- <span> The password must have between 8 and 16 characters.</span>-->
								</li>
								<li class="list-group-item list-group-item-info">
									<span class="	glyphicon glyphicon-info-sign"></span>
									 It should also have an uppercase.
								</li>
								<li class="list-group-item list-group-item-info">
									<span class="	glyphicon glyphicon-info-sign"></span>
									Or a number at least.
								</li>
								<li class="list-group-item list-group-item-info">
									<span class="	glyphicon glyphicon-info-sign"></span>
									Special characters work too.
								</li>
							</div>
							<div class="form-group">
			          			<button type="button" class="btn btn-info" ng-click="go_back()">
								<span class="glyphicon glyphicon-arrow-left"> </span> Go back </button> 
								<button type="submit" class="btn btn-info" >Create your account </button>
							</div>
			          	</form>
			          	
			        </div>
			        <div class="modal-footer">
			          	<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			        </div>
			      </div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.container.login-form').animate(
					{
						maxHeight:450,
						opacity: 1
					}, 400, function(){
								$('.create-acc').animate({
									maxHeight: 100,
									opacity: 1,
									padding: 15
								}, 200);
				});
			});
		</script>

	</body>
</html>