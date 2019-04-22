<!DOCTYPE html>
<html>
	<head>
		<title>List of all employees</title>

		@include('components.admin-header')

		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/stylesheets/general/admin-employees.css">	

		<script type="text/javascript" src="/EShop/public/resources/js/admin/messages.js"></script>
	</head>
	<body ng-app="employeesApp">
		<!-- NAVBAR  -->
		@include('components.admin-navbar')

		<!--IMAGE WARNINGS -->
		@include('components.admin-imagesWarnings')

		<!-- MESSAGES FOR PRODUCTS -->
		@include('components.admin-infoMessages')

		<!-- MAIN CONTENT -->
		<div class="admin-content container-fluid">
			<div class="row">

				<!-- SIDEBAR -->
				@include('components.admin-sidebar')

				<script type="text/javascript">
					$(document).ready(function(){
						$('.sidebar .list-unstyled a:nth-child(4) .page-link').addClass('active-link');
					});
				</script>

				<div class="content-display col-xs-12 col-md-10 col-md-offset-2" ng-controller="loadEmployees">
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<h2>Table with all the employees</h2>
						</div>
					</div>

                              <div class="row">
                                    <div class="col-xs-12">
                                          <input class="form-control searchText" value="" placeholder="Search for an employee"
                                           ng-model="searchText">
                                    </div>
                              </div>

					<div class="row">
						<div class="col-xs-12">
							<button type="button" class="add-button btn btn-primary" ng-click="clickedAddButton()">
								<span class="glyphicon glyphicon-plus"></span>
								Add a new employee
							</button>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<div class="employee-data panel panel-info" >
								<div class="panel-heading">
									Employees
								</div>

								<div class="panel-body ">
									<div class="container-fluid">

										<!--User's Data Header-->

										<!--User's Data -->
										<div class="row" ng-repeat="employee in employees | filter : searchText">
											<div class="user-options col-xs-1">
												<div class="dropdown">
      												<span class="glyphicon glyphicon-option-vertical" data-toggle="dropdown"
      													  aria-haspopup="true" aria-expanded="false">
      												</span>
      												<ul class="dropdown-menu" aria-labelledby="dLabel">
      												    	<li class="text-center">
      												    		<button class="btn btn-info" ng-click="clickedUpdateButton($index)">
      												    			Update Info
      												    		</button>
      												    	</li>
      												    	<li role="separator" class="divider"></li>
      												    	<li class="text-center">
      												    		<button class="btn btn-danger">
      												    			Remove employee
      												    		</button>
      												    	</li>
      											  	</ul>
												</div>
											</div>

											<div class="user-image text-center col-xs-2">
												<img class="img-responsive img-circle" ng-src="@{{ '../' + employee.image_path}}">
											</div>
											<div class="user-name text-center col-xs-2">
												@{{ employee.first_name + ' ' + employee.last_name}}
											</div>

											<div class="user-email text-center col-xs-2 col-md-3">
												@{{ employee.email}}
											</div>

											<div class="phone-number text-center col-xs-2">
												@{{ employee.phone_number }}
											</div>

											<div class="birthdate col-xs-1 text-center">
												@{{ employee.birthdate * 1000 | date: 'longDate'}}
											</div>

											<div class="added-on text-center col-xs-2 col-md-1">
												@{{ employee.created_at * 1000 | date: 'medium' }}
											</div>
										</div>


									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>

		<!--DIALOG BOX FOR ADDING/UPDATING THE DATA OF AN EMPLOYEE-->
		<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" ng-controller="usersForm">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">

    				<!--Modal header -->
      				<div class="modal-header">
        				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        					<span aria-hidden="true"> &times; </span>
        				</button>

        				<strong> @{{ formHeader }} </strong>
      				</div>

      				<!--Modal Body-->
      				<div class="modal-body">
      					<div class="row">
      						<p class="col-xs-12 helper"> @{{ subHeader }}</p>
      					</div>

      					<div class="row">
      						<form class="form" ng-submit="submit_data()">

      							<div class="col-xs-6 form-group">
      								<input type="text" class="form-control" value="" placeholder="First Name"
      									ng-model="first_name" ng-change="validate_firstName()">

      								<p class="help-block"> The first name must contain only letters.</p>
      							</div>

      							<div class="col-xs-6 form-group">
      								<input type="text" class="form-control" value="" placeholder="Last Name"
      									ng-model="last_name" ng-change="validate_lastName()">

      								<p class="help-block"> The last name must contain only letters.</p>
      							</div>

      							<div class="col-xs-6 form-group">
      								<input type="email" class="form-control" value="" placeholder="Email"
      									ng-model="email">	
      							</div>

      							<div class="col-xs-6 form-group">
      								<input type="text" class="form-control" value="" placeholder="Phone number"
      									ng-model="phone_number" ng-change="validate_phoneNumber()">	

      								<p class="help-block"> The phone number must contain only numbers.</p>
      							</div>

      							<div class="col-xs-6 form-group">
      								<select class="form-control" ng-model="selectedPosition">
      									<option value=""> Select Position </option>
      									<option ng-repeat="position in positions" value="@{{ position.id}}"> 
			        							@{{ position.position_name }}
			        					</option>
      								</select>	
      							</div>

      							<div class="col-xs-6 form-group">
      								<input type="text" class="form-control" value="" placeholder="CNP"
      									ng-model="cnp" ng-change="validate_cnp()">
      								<p class="help-block"> The cnp must contain 13 <strong> digits </strong> . </p>
      							</div>

      							<div class="col-xs-6 form-group birthdate-picker">
      								<div class="row">
      									<div class="col-xs-4">
      										<select class="form-control" ng-model="birthdate_year" ng-change="change_year()">
      											<option value=""> Year </option>

      											<option ng-repeat="year in years | orderBy: year : true" value="@{{ year }}">
      												@{{ year }}
      											</option>
      										</select>
      										
      									</div>

      									<div class="col-xs-4">
      										<select class="form-control" ng-model="birthdate_month" ng-change="change_month()">
      											<option value=""> Month </option>

      											<option ng-repeat="month in months" value="@{{ month.month_number }}">
      												@{{ month.month_name }}
      											</option>
      										</select>
      									</div>

      									<div class="col-xs-4">
      										<select class="form-control" ng-model="birthdate_day">
      											<option value=""> Day </option>
      											<option ng-repeat="day in days | limitTo: limitDays" value="@{{ day }}">
      												@{{ day }}
      											</option>
      										</select>
      									</div>
      								</div>
      							</div>

      							<div class="col-xs-6 form-group">
      								<input type="password" class="form-control" value="" placeholder="Password"
      									ng-class="{'hidden' : !displayPassInput}" 
      									ng-model="password"
      									ng-change = "validate_password()">	

      								<p class="help-block"> The length of the password must be between 
      									<strong>8 and 16 characters</strong>.
      									 It should contain at least <strong> one digit </strong>  and  
                                                             <strong> one letter </strong>.
      								</p>
      							</div>

      							<div class="col-xs-12">
      								<input type="file" id="employee-image" style="display: none;">
      								<div class="image-picker img-circle">
      									<div class="close"> &times; </div>
      									<img class="img-responsive img-circle" src="">
      									<span class="glyphicon glyphicon-plus"></span>
      								</div>

      								<script type="text/javascript">
      									$(document).ready(function(){
      										$('.image-picker span').click(function(){
      											$('#employee-image').click();
      										});
      									});
      								</script>
      							</div>

      							<div class="col-xs-12">
      								<button type="submit" class="btn btn-primary">@{{ buttonText }}</button>
      							</div>
      							
      						</form>
      					</div>
      					
  
      				</div>

      				<div class="modal-footer">
        				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      				</div>
    			</div><!-- /.modal-content -->
  			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<script type="text/javascript">
			var app = angular.module('employeesApp', []);
		</script>

		<!--DIRECTIVES -->
		<script type="text/javascript" src="/EShop/public/resources/js/admin/directives/fileDirective.js "></script>
		<!-- SERVICES -->
		<script type="text/javascript" src="/EShop/public/resources/js/admin/services/shareEmployees.js"></script>

		<!-- CONTROLLERS -->
		<script type="text/javascript" src="/EShop/public/resources/js/admin/controllers/loadEmployees.js"></script>
		<script type="text/javascript" src="/EShop/public/resources/js/admin/controllers/usersForm.js"></script>
	</body>
</html>