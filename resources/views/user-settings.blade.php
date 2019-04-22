<!DOCTYPE html>
<html>
	<head>
		<title>{{ $username . "'s settings"}}</title>
		
		@include('components.start-header')

		<style type="text/css">
			.messages{
				position: fixed;
				top:77px;
				right: calc(2.5% + 14px);
				max-height: 0px;

				font-family: Corbel;

				opacity: 0.25;
				overflow: hidden;
				z-index: 1900;
			}
			.form-control{
				font-family: 'Montserrat', sans-serif ;
				font-size: 12px;
			}
			.form-control-feedback{
				top: 24px;
				right: 15px;
			}
		</style>
	</head>
	<body>
		@include('components.start-navbar')

		@include('components.start-categories')

		<div class="messages">
			<ul class="list-group">
  				<li class="list-group-item list-group-item-success">
  					<span class="glyphicon glyphicon-ok"></span>
  					<span> Settings updated! </span>
  				</li>
			</ul>
		</div>

		<!-- Main Container -->
		<div class="main-container container-fluid" ng-app="settingsApp">
			<div class="row">
				<h3 class="col-xs-10 col-sm-11">Setup your account information</h3>
				<div class="col-xs-2 col-sm-1 text-right"></div>
				<p class="info" data-toggle="tooltip">
					<span class="glyphicon glyphicon-info-sign"></span>
				</p>
			</div>
			<hr>
			<div class="row" ng-controller="editSettings">
				<form ng-submit="send_data()">
					<div class="form-group first-name col-xs-12 col-sm-6">
						<label>First name</label>
						<input type="text" class="form-control"  placeholder="Your first name" 
							ng-model="first_name" />
						<span class="glyphicon glyphicon-warning-sign form-control-feedback sr-only" aria-hidden="true"></span>
						<p class="help-block sr-only"> There should be no numbers in your name</p>
					</div>
					<div class="form-group col-xs-12 col-sm-6" style="margin-bottom: 0px;">
						<label>Last name</label>
						<input type="text" class="form-control"  placeholder="Your last name" ng-model="last_name" />
						
					</div>

					<div class="col-xs-12">
						<hr>
					</div>
					
					<div class="form-group county col-xs-12 col-sm-6">
						<label>Select your county</label>
						<select class="form-control" ng-model="county" ng-change="get_cities()">
			        		<option value="">Select your county</option>
			        		<option ng-repeat="county in counties" value="@{{ county.id }}"> 
			        			@{{ county.name }}
			        		</option>
			        	</select>
					</div>
					<div class="form-group col-xs-12 col-sm-6" style="margin-bottom: 0px;">
						<label>Select your city</label>
						<select class="form-control" ng-model="city" >
							<option value=""> Select your city </option>
							<option ng-repeat="city in available_cities | orderBy:name" value="@{{ city.id }}">
								@{{ city.name }}
							</option>
						</select>
						
					</div>

					<div class="col-xs-12">
						<hr>
					</div>

					<div class="form-group col-xs-12 col-sm-6">
						<label>Your phone number</label>
						<input type="text" class="form-control" placeholder="Phone number" ng-model="phone_number" />
					</div>
					<div class="form-group col-xs-12 col-sm-6">
						<label>Your shipping adress</label>
						<textarea class="form-control" rows="1" placeholder="Adress" ng-model="adress"></textarea>
					</div>
					<div class="form-group col-xs-12">
						<button type="submit" class="btn btn-primary col-xs-4 col-md-2 col-lg-1 pull-right">Save</button>
					</div>
				</form>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip({
					animation: true,
					delay: {show:40, hide:40},
					html: true,
					placement: 'auto left',
					title: "<p> On this page you can edit your data that will be used whenever you make an order. </p>",
					template: '<div class="tooltip settings-info" role="tooltip"><div class="tooltip-arrow"> </div> '
								+'<div class="tooltip-inner"></div>	</div>' 
				}); 
				/*$('[data-toggle="tooltip"]').tooltip('show');
				setTimeout(function() {
					$('[data-toggle="tooltip"]').tooltip('hide');
				}, 10000);*/
			});

			var app = angular.module('settingsApp', []);
			var initial_values = {};

			app.factory('inputCheck', function(){
				var inputCheck = {};

				inputCheck.number_validation = function(input){
					return input.match(/[0-9]/g);
				}
				inputCheck.modified_values = function(settings, initial_values){

					console.log(initial_values);
					if(settings.first_name === initial_values.first_name && settings.last_name === initial_values.last_name
						&& settings.county === parseInt( initial_values.county ) && settings.city === parseInt (initial_values.city)
							&& settings.phone_number === initial_values.phone_number
								&& settings.shipping_adress === initial_values.adress){
						return false;

					}

 					return settings;
				}

				return inputCheck;
			});
			
			app.controller('editSettings', function($scope, $http, inputCheck){
				
				$http.get('get_settings')
						.then(function(response){
							initial_values = angular.fromJson(response.data);
							
								$scope.first_name = initial_values.first_name ;
								$scope.last_name = initial_values.last_name;				

								$scope.county =  initial_values.county ? initial_values.county : null;
								$scope.city = initial_values.city ? initial_values.city  : null;

								$scope.phone_number = initial_values.phone_number;
								$scope.adress = initial_values.adress;
						});
				

				$scope.available_cities = null;
				
				$http.get('locations')
						.then(function(response){
							$scope.counties = angular.fromJson(response.data);
							for(var i = 0; i < $scope.counties.length; i++){
								$scope.counties[i].cities = angular.fromJson($scope.counties[i].cities);
								if( $scope.counties[i].id == $scope.county )
									$scope.available_cities = $scope.counties[i].cities;
							}
							console.log($scope.available_cities);
						});
				$scope.get_cities  = function(){
					for(var i = 0; i < $scope.counties.length; i++)
						if($scope.counties[i].id == $scope.county)
							$scope.available_cities = $scope.counties[i].cities;
					$scope.city = ($scope.county == initial_values.county) ? initial_values.city : null ;
						//console.log($scope.available_cities);
				}

				$scope.send_data = function(){
					var settings = {
						'first_name': $scope.first_name,
						'last_name' : $scope.last_name,
						'phone_number' : $scope.phone_number,
						'county' : parseInt($scope.county),
						'city' : parseInt( $scope.city ),
						'shipping_adress' : $scope.adress
					};
					initial_values = inputCheck.modified_values(settings, initial_values);
					
					if(initial_values){
						$http({
							method: 'POST',
							url : 'update_settings',
							headers: {
								'ContentType' : undefined
							},
							data : settings
						}).then(function(response){
							var resp = angular.fromJson(response.data);
							if(resp){
								$('.messages').animate({
									maxHeight: 50,
									top: 83,
									opacity:1
								}, 300)
								.delay(10000)
								.animate({
									maxHeight: 0,
									top:77,
									opacity: 0.25
								},300);

						}

						});
					}
					
				}
			});
		</script>
	</body>
</html>