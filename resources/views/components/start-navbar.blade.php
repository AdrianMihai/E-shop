<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">

				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
					 data-target="#header-collapse" aria-expanded="false">
        				<span class="sr-only">Toggle navigation</span>
        				<span class="icon-bar"></span>
        				<span class="icon-bar"></span>
        				<span class="icon-bar"></span>
      				</button>
      				
					<p class="logo">
						E-Shop
					</p>
					
				</div>
				
				<!-- COLLAPSED   -->
				<div class="collapse navbar-collapse navbar-right" id="header-collapse">
					
					<form class="navbar-form navbar-left navbar-nav" role="search">
						<div class="input-group">
							<input type="text" class="search-product form-control" placeholder="search for a product" />
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</div>
						</div>
					</form> 
					<ul class="nav navbar-nav navbar-left">
						<li>
							<button type="button" class="categories-switch btn btn-default navbar-btn">
								<span class="glyphicon glyphicon-th-list"></span>
								Categories
							</button>
						</li>

						<li class="username dropdown">
							<button type="button" class=" btn btn-default navbar-btn" data-toggle="dropdown" role="button"
							 aria-haspopup="true" aria-expanded="false">
								<span class="glyphicon glyphicon-user"> </span>
								@if( Auth::check() || Auth::viaRemember() )
									<span> {{ $username }} </span>

								@else
									<span> User </span>
								@endif
							</button>
							<ul class="dropdown-menu">
								@if( Auth::check() || Auth::viaRemember() )
									<li class="dropdown-header"> {{ $username}} </li>
									<li class="text-left">
										<a href="../"> 
											<span class="glyphicon glyphicon-home"></span>
											Main Page
										</a>
									</li>
									<li class="text-left">
										<a href="/EShop/public/cart"> 
											<span class="glyphicon glyphicon-shopping-cart"></span>
											Your cart 
										</a>
									</li>
									<li class="text-left">
										<a href="wishlists"> 
											<span class="glyphicon glyphicon-gift"></span>
											Your wishlists
										</a>
									</li>
									<li class="text-left">
										<a href="/EShop/public/settings"> 
											<span class="glyphicon glyphicon-cog"></span>
											Settings
										</a>
									</li>

									<li class="text-left">
										<a href="/EShop/public/logout"> 
											<span class="glyphicon glyphicon-log-out"></span>
											Logout
										</a>
									</li>	

								@else
									<li>
										<a href="/EShop/public/login" > Log in </a>
									</li>
									<li role="separator" class="divider"></li>
									<li>
										<a href="/EShop/public/login"> Create an account </a>
									</li>
								@endif 
								
							</ul>
						</li>

						<!--<li class="cart ">
							<button type="button" class="btn btn-default navbar-btn">
								<span class="glyphicon glyphicon-shopping-cart"></span>
								<span>Shopping Cart</span>
							</button>
						</li>-->
						<li class="menu dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
	          					aria-expanded="false">  Menu <span class="caret"></span>  </a>
	          				<ul class="dropdown-menu">
	          					<li>
	          						<button type="button" class="promotions btn btn-default navbar-btn">
										<span class="glyphicon glyphicon-piggy-bank"></span>
									Promotions
									</button>
	          					</li>
	          					
	          					<li role="separator" class="divider"></li>
	          					
	          					<li> 
	          						<button type="button" class="about btn btn-default navbar-btn">
										<span class="glyphicon glyphicon-info-sign"></span>
										About E-Shop
									</button>
	          					</li>

	          				</ul>
						</li>
					</ul>
					
				</div>

			</div>
		</nav>