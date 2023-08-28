<div id="Top" class="sr-only">Top</div>
<nav id="navbarTop" class="navbar navbar-expand-md navbar-dark zd-md-flex sticky-top px-0 px-md-3" style="background: rgba(15, 17, 16, 0.1);">
	<a class="navbar-brand d-flex align-items-center" href="index.php">
		<img src="img/foodie.png" fsrcset="img/logo-frontend-ninja_2@2x.png 2x, img/logo-frontend-ninja_2@3x.png 3x" class="logo_frontend-ninja" style="height: 30px">
		<span style="color: #fff;font-family: Segoe UI;line-height: 16px;opacity: 0.7">Foodie's<br>WORLD</span>
	</a>

	<button id="cToggler" class="fnavbar-dark navbar-toggler fml-auto" type="button" data-toggle="collapse" data-target="#navbarFoodie" aria-controls="navbarFoodie" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarFoodie">
		<div class="navbar-nav ml-md-auto align-items-center">
			<!-- <div class="nav-item active"> -->
				<a class="nav-item nav-link Home px-5 px-md-2" href="index.php">Home</a>
			<!-- </div> -->
			<!-- <div class="nav-item"> -->
				<a class="nav-item nav-link Popular_Recipe" href="index.php#most-viewed-post"> Popular Recipes </a>
				<a class="nav-item nav-link Search_Recipe" href="#"> Search Recipes </a>
			<!-- </div> -->
			<!-- <div class="nav-item"> -->
				<a class="nav-item nav-link Account dropdown-toggle" fdata-toggle="dropdown" id="btn" aria-haspopup="false" aria-expanded="false"> Account </a>
				<div class="dropdown-menu" aria-labelledby="accountbtn" style="background-color: transparent; border:none">
					<ul class="dropdown-item" style="background-color: transparent; cursor: pointer;">
					<li class="list-group-item d-flex"><a href="profile.php">View Profile</a></li>
					<li class="list-group-item d-flex"><a href="myrecipes.php">My Recipes</a></li>
					<li class="list-group-item d-flex text-danger" id="foodie_signout">Sign out</li>
					</ul>
				</div>
			<!-- </div> -->
			<div class="nav-item dropdown mr-md-0 mt-0">
				<a class="nav-link text-center dropdown-toggle searchFoodie" href="" data-toggle="dropdown" id="searchFoodie" aria-haspopup="false" aria-expanded="false"> <img src="img/ic-search@3x.png" srcset="img/ic-search@3x.png 2x, img/ic-search@3x.png 3x" class="ic_search"> </a>
				<div class="dropdown-menu p-0" aria-labelledby="searchFoodie" style="right: 0px;left:auto">
					<form class="form-inline dropdown-item" konsubmit="return false">
			    		<input class="form-control d-block" type="text" placeholder="Search" aria-label="Search" id="search-recipe-direct" list="search-hints">
		    		</form>
		    		<datalist id='search-hints'>
						 <!-- data filled using AJAX -->
						<!-- <option value="desserts">
							<option value="curry"> -->
					</datalist>
				</div>
			</div>
		</div>
	</div>
</nav>	