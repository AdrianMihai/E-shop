<!DOCTYPE html>
<html>
	<head>
		<title>Admin Logs</title>

		@include('components.admin-header')

		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/stylesheets/general/admin-logs.css">

		<script type="text/javascript" src="/EShop/public/resources/js/admin/logs.js"></script>
	</head>

	<body ng-app="logsApp">

		<!-- NAVBAR  -->
		@include('components.admin-navbar')

		<!-- MAIN CONTENT -->
		<div class="admin-content container-fluid" ng-controller="loadLogs">
			<div class="row">

				<!-- SIDEBAR -->
				@include('components.admin-sidebar')

				<script type="text/javascript">
					$(document).ready(function(){
						$('.sidebar .list-unstyled a:nth-child(6) .page-link').addClass('active-link');
					});
				</script>

				<div class="content-display col-xs-12 col-sm-12 col-md-10 col-md-offset-2">
					<div class="row">
						<div class="col-xs-12">
							<h2>See all the logs from each category:</h2>
						</div>
					</div>
					
					<!-- CATEGORIES OF LOGS -->
					<div class="row categories-list">
						<ul class="list-inline categories">
							<li class="col-xs-4 col-sm-4 text-center">
								<div class="link-container"  ng-class="{'active-category': header.employees}">
									<a href="employees" > Employees </a>

									<div class="hover-decoration"></div>
								</div>
								
							</li>
							<li class="col-xs-4 col-sm-4 text-center">
								<div class="link-container" ng-class="{'active-category': header.products}">
									<a href="products"> Products </a>
									<div class="hover-decoration"></div>
								</div>
							</li>
							<li class="col-xs-4 col-sm-4 text-center" >
								<div class="link-container" ng-class="{'active-category': header.orders}">
									<a href="orders"> Orders </a>
									<div class="hover-decoration"></div>
								</div>
							</li>
						</ul>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<input type="text" class="form-control" value="" placeholder="Search for something"
							 ng-model="logInfo"
							 ng-class="{'hidden': !allLogs.length}"
							 >
						</div>
					</div>
					<div class="row log-container" ng-repeat="log in allLogs | filter: logInfo">
						<div class="col-xs-12 col-sm-12">
							<span class="highlight">
								@{{log.employee + ' '}}
							</span>

							@{{log.text + ' on ' }}

							<span class="highlight">
								@{{log.created_at * 1000 | date: 'medium'}}
							</span>
							
							
						</div>
					</div>

				</div>
			</div>
		</div>

	</body>
</html>