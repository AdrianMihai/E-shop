<!DOCTYPE html>
<html>
	<head>
		<title> EShop Settings</title>
		
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
						$('.sidebar .list-unstyled a:nth-child(7) .page-link').addClass('active-link');
					});
				</script>

				<div class="content-display col-xs-12 col-md-10 col-md-offset-2">
					<div class="row">
						<div class="col-xs-12 col-md-12">
							<h2>Here you can edit your settings</h2>
						</div>
					</div>

						<!-- SHIPPING SETTINGS -->
						<div class="row">
							<div class="col-xs-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										Shipping
									</div>
									<form lass="form" method="post" action="change_shipping_prices" >
										<div class="panel-body">
												{{csrf_field()}}
												<div class="form-group">
													<label> Shipping price </label>
													<input class="form-control" type="text" name="price" value="{{ $price }}" />
												</div>
												<div class="form-group ">
													<label> Free shipping from </label>
													<input class="form-control" type="text" name="free_from" value="{{ $free_from }} "/>
												</div>
												<div class="form-group col-xs-12 text-right">
													<button type="submit" class="btn btn-primary">Save Changes</button>
												</div>
										</div>
									</form>
									
								</div>
							</div>
							
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</body>
</html>