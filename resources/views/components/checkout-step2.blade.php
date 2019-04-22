<div class="row">
	<div class="col-xs-12 col-sm-10 col-sm-offset-1" ng-controller="orderInfo">
		<form class="data-form" action="../step-2/check" method="POST">
			{{ csrf_field() }}
			<ul class="list-unstyled main-form" data-list="nested" data-margin="7" >
				<li class="main" data-icon="true" data-maxHeight="56">

					<!--List's Header -->
					<div class="list-header"
						ng-class="{'list-active-incomplete' : (!firstName || !lastName || !email || !phoneNumber),
								   'list-active-untouched' : (!firstName && !lastName && !email && !phoneNumber),
								   'list-active-completed' : (firstName && lastName && email && phoneNumber)
								   
								}">
						<span class="glyphicon glyphicon-triangle-right"></span>
						<span> Personal Data</span>
					</div>

					<!--Nested List -->
					<ul class="nested-list list-unstyled">
						<div class="sliding-line"></div>
						<li data-toggled="false">
							<label>First name</label>
							<input type="text" class="form-control" name="firstName" placeholder="Your first name" 
									ng-model="firstName"
									 />
						</li>
						<li data-toggled="false">
							<label>Last name</label>
							<input type="text" name="lastName" class="form-control"  placeholder="Your last name" 
							ng-model="lastName" />
						</li>	
						<li data-toggled="false">
							<label>Email</label>
							<input type="text" name="email" class="form-control"  placeholder="Your email"
							 ng-model="email" />
						</li>
						<li data-toggled="false">
							<label>Your phone number</label>
							<input type="text" name="phoneNumber" class="form-control" placeholder="Phone number"
							 ng-model="phoneNumber" />
						</li>	
					</ul>
				</li>
				<li class="main" data-toggled="false" data-icon="true" data-maxHeight="56">

					<!--List's Header -->
					<div class="list-header list-active-incomplete"
						ng-class="{'list-active-untouched' : (!personal_county && !personal_city && !personal_adress),
								   'list-active-completed' : (personal_county && personal_city && personal_adress),
								   'list-active-incomplete' : (!personal_county || !personal_city || !personal_adress)
								}">
						<span class="glyphicon glyphicon-triangle-right"></span>
						<span> Personal Adress</span>
					</div>

					<!--Nested List -->
					<ul class="nested-list list-unstyled">
						<div class="sliding-line"></div>
						<li data-toggled="false">
							<label>Select your county</label>
							<select class="form-control" name="personal_county" 
									ng-model="personal_county" ng-change="get_personal_cities()">
				        		<option value="">Select your county</option>
				        		<option ng-repeat="county in counties" value="@{{ county.id }}"> 
				        			@{{ county.name }}
				        		</option>
				        	</select>
						</li>
						<li data-toggled="false">
							<label>Select your city</label>
							<select class="form-control" name="personal_city" ng-model="personal_city" >
								<option value=""> Select your city </option>
								<option ng-repeat="city in personal_cities | orderBy:name" value="@{{ city.id }}">
									@{{ city.name }}
								</option>
							</select>
						</li>
						<li data-toggled="false">
							<label>Your personal adress</label>
							<input type="text" name="personal_adress" class="form-control" placeholder="street, str.number, number"
							 ng-model="personal_adress">
						</li>		
					</ul>
				</li>
				<li class="main" data-toggled="false" data-icon="true" data-maxHeight="56">

					<!--List's Header -->
					<div class="list-header"
						 ng-class="{'list-active-untouched' : (!shipping_county && !shipping_city && !shipping_adress),
								   'list-active-completed' : (shipping_county && shipping_city && shipping_adress),
								   'list-active-incomplete' : (!shipping_county || !shipping_city || !shipping_adress)
								}">
						<span class="glyphicon glyphicon-triangle-right"></span>
						<span> Shipping Adress</span>
					</div>

					<!--Nested List -->
					<ul class="nested-list list-unstyled">
						<div class="sliding-line"></div>
						<li class="checkbox">	
							<label>
								<input type="checkbox" value="false" ng-model="changeShippingAddress" ng-change="sameAdress()">
								Make the same as the personal adress
							</label>
						</li>
						<li data-toggled="false">
							<label>Select county</label>
							<select class="form-control" name="shipping_county" 
									 ng-model="shipping_county" ng-change="get_shipping_cities()">
				        		<option value="">Select your county</option>
				        		<option ng-repeat="county in counties" value="@{{ county.id }}"> 
				        			@{{ county.name }}
				        		</option>
				        	</select>
						</li>
						<li data-toggled="false">
							<label>Select city</label>
							<select class="form-control" name="shipping_city"  ng-model="shipping_city" >
								<option value=""> Select your city </option>
								<option ng-repeat="city in shipping_cities | orderBy:name" value="@{{ city.id }}">
									@{{ city.name }}
								</option>
							</select>
						</li>
						<li data-toggled="false">
							<label>Shipping adress</label>
							<input type="text" name="shipping_adress" 
									class="form-control" placeholder="Adress" ng-model="shipping_adress"></input>
						</li>		
					</ul>
				</li>
			</ul>
		</form>
	</div>

</div>

<script type="text/javascript" src="/EShop/public/resources/js/list_library.js"></script>
<script type="text/javascript">

	app.controller("orderInfo", function($scope, $http){
		//$scope.personal_county = $scope.shipping_county = $scope.personal_city = $scope.shipping_city = null;
		$scope.personal_city = $scope.personal_county = $scope.shipping_county = $scope.shipping_city = null;
		$scope.counties = $scope.personal_cities = $scope.shipping_cities = [];

		//Get all the counties and cities in each county
		$http.get('/EShop/public/locations').then(function(response){
			$scope.counties = angular.fromJson(response.data);
			for(var i = 0; i < $scope.counties.length; i++){
				$scope.counties[i].cities = angular.fromJson( $scope.counties[i].cities );
			}
			console.log($scope.counties);
		});

		//function that fires when county from personal adress changes
		$scope.get_personal_cities = function(value = null){

			for(var i = 0; i < $scope.counties.length; i++){
				if($scope.counties[i].id == $scope.personal_county){
					$scope.personal_cities = $scope.counties[i].cities;
					break;
				}
			}

			$scope.personal_city = value;
			if(!value)
				return false;
			else
				return true;
			//console.log($scope.personal_cities);
		}

		//function that fires when county from shipping adress changes
		$scope.get_shipping_cities = function(){
			for(var i = 0; i < $scope.counties.length; i++){
				if($scope.counties[i].id == $scope.shipping_county){
					$scope.shipping_cities = $scope.counties[i].cities;
					break;
				}
			}
			//console.log($scope.shipping_cities);
		}

		$http.get('info').then(function(response){
			
			var resp = response.data;
			console.log(resp);
			if(typeof resp === 'object'){
				angular.forEach(resp.personalInfo, function(value, key){
					$scope[key] = value;
				});
				console.log($scope);
				$scope.personal_adress = resp.personalAdress.adress;
				$scope.personal_county = resp.personalAdress.county;

				$scope.get_personal_cities(resp.personalAdress.city)

				$scope.shipping_adress = resp.shippingAdress.adress;
				$scope.shipping_county = resp.shippingAdress.county;

				$scope.get_shipping_cities();
				$scope.shipping_city = resp.shippingAdress.city;
			}
			
		},
		function(error){
			console.log(error.data);
		});

		//funciton that makes the shipping adress the same as the personal one
		$scope.sameAdress = function(){
			if($scope.changeShippingAddress){
				$scope.shipping_county = $scope.personal_county;
				$scope.shipping_adress = $scope.personal_adress;

				$scope.get_shipping_cities();
				$scope.shipping_city = $scope.personal_city;
				
			}
			else{
				$scope.shipping_city = $scope.shipping_county = null;
				$scope.shipping_adress = '';
			}
		}
	});

	$(document).ready(function(){
		var firstProgressBar = $('.progress .progress-bar')

		firstProgressBar.css('transition-duration', '0s');

		firstProgressBar.eq(0).animate({
                width: '100%'
            }, 450, function(){
                $('.rounded-icon').eq(1).addClass('active-icon');
		});

		var form_headers =  $('.main-form .list-header');
		for(var i = 0; i < form_headers.length; i++ ){
			if( ($(form_headers[i]).hasClass('list-active-untouched') || $(form_headers[i]).hasClass('list-active-incomplete'))
					&& ( !$(form_headers[i]).hasClass('list-active-completed')))
			{
				$('*ul[data-list="nested"].main-form').nestingListShow([i]);
				break;
			}
		}

		$('.next-button').click(function(e){
			e.preventDefault();
			$('.data-form').submit();
		});
		
	});
</script>
