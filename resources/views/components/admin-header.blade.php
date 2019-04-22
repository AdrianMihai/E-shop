		
		<!-- META TAGS -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		
		<!-- CSS -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/stylesheets/admin.css">

		<!-- JAVASCRIPT -->
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="/EShop/public/resources/js/node_modules/jquery-ui-1.11.4/jquery-ui.min.js"></script>
		<script src="/EShop/public/resources/js/node_modules/jquery-mobile/jquery.mobile.custom.min.js"></script>
		<script type="text/javascript" src="/EShop/public/resources/js/node_modules/angular-1.5.7/angular.min.js"></script>
		<script type="text/javascript" src="/EShop/resources/views/css/bootstrap/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="/EShop/public/resources/js/admin/animations.js"></script>
		<script type="text/javascript">
			$.ajaxSetup({
        		headers: {
            		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        		}
    		});
		</script>
