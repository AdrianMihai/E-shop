<!DOCTYPE html>
<html lang="en">
	<head>
		<title>EShop</title>

		<!-- META TAGS -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		
		<!-- CSS -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/EShop/resources/views/css/stylesheets/start.css">

		<!-- JAVASCRIPT -->
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="/EShop/resources/views/css/bootstrap/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="/EShop/public/resources/js/animations.js"></script>
		<script type="text/javascript">
			$.ajaxSetup({
        		headers: {
            		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        		}
    		});
		</script>

	</head>

	<body>
		@include('components.start-navbar')
		
		@include('components.start-categories')

		@include('components.notification-panel')

		<!-- Main Container -->
		<div class="main-container container" id="root">
			
			@include('components.footer')
		</div>
		
	<script type="text/javascript" src="/EShop/public/resources/js/transformed/indexAppTransformed.js"></script>
	</body>
</html>