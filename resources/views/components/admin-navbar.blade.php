		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<!-- HEADER -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
						 data-target="#header-collapse" aria-expanded="false">
	        				<span class="sr-only">Toggle navigation</span>
	        				<span class="icon-bar"></span>
	        				<span class="icon-bar"></span>
	        				<span class="icon-bar"></span>
	      			</button>
	      				
						<p class="logo">E-Shop admin </p>
				</div>

				<!-- COLLAPSED   -->
				<div class="collapse navbar-collapse navbar-right" id="header-collapse">
					
					<ul class="nav navbar-nav navbar-left">
						<li class="employee-info">
							<button type="button" class="btn btn-default navbar-btn">
								<img class="employee-image img-responsive img-thumbnail"
									 src="/EShop/public/resources/img/poza_profil.png" alt=""/>
								{{ $first_name . ' ' . $last_name }}
							</button>
						</li>

					</ul>
					
				</div>
			</div>
		</nav>