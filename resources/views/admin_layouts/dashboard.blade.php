<!DOCTYPE html>
<html>
	<head>
		<title> EShop Admin Page </title>
		
		<!-- <script type="text/javascript" src="../resources/js/node_modules/jquery/dist/jquery.js"></script>
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
		
		<script type="text/javascript" src="../resources/js/admin/animations.js"></script>
		<script type="text/javascript" src="../resources/js/node_modules/jquery-ui-1.11.4/jquery-ui.min.js"></script>
		<script type="text/javascript" src="../resources/js/node_modules/angular-1.5.7/angular.min.js"></script>
		<script type="text/javascript" src="/EShop/resources/views/css/javascripts/bootstrap.js"></script> -->
		@include('components.admin-header')
	</head>
	<body>
		<!-- NAVBAR  -->
		@include('components.admin-navbar')

		<!-- MAIN CONTENT -->
		<div class="admin-content container-fluid">
			<div class="row">

				<!-- SIDEBAR -->
				@include('components.admin-sidebar')
				<script type="text/javascript">
					$(document).ready(function(){
						$('.sidebar .list-unstyled a:first-child .page-link').addClass('active-link');
					});
				</script>

				<div class="content-display col-xs-12 col-md-10 col-md-offset-2">
					<h1>This is the admin dashboard</h1>
				</div>
			</div>
		</div>
	</body>
</html>