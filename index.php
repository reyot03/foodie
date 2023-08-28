<?php
	require 'foodieHeader.php';
	session_start();
	$_SESSION['rid'] = null;
?>
<script>
	var recent_posts_count = 0;
	$(document).on('ready', function(){
		$("#show-more-recent-posts-home").click();
		$("#show-more-recent-posts-home").click();
		$("#show-more-most-viewed-posts-home").click();
		$("#show-more-most-viewed-posts-home").click();
		
	});
</script>
<style>	
	* {
		background-repeat: no-repeat;
	}

	body{
		/*background-image: url('img/loginbg.jpg');*/
		background-image: url('img/img-foodie-image@3x.jpg');
		background-attachment: fixed;
		background-position: center;
		background-size: cover;
		background-repeat: no-repeat;
		/*height: 4000px;*/
		background-color: #fff;
	}

	#navbarFoodie.show {
		background: rgba(15, 17, 16, 0.5);
	}

	.navbar-dark .navbar-nav .nav-link {
		color: rgba(255, 255, 255, 0.8);
		/*color: #ffffff99;*/
		/*transition: background 1s ease-in-out;*/

	}

	.navbar-dark .navbar-nav .nav-link:hover {
		color: #fff;
		padding-bottom: 6px;	/* initial: 8px */
		border-bottom: 2px solid var(--green-apple);
		border-bottom: 2px solid var(--teal);
		border-bottom: 2px solid #ffc107;
		background: #0002; 
		/*transition: background 0.5s ease-in-out;*/
	}

	#navbarFoodie > div > a.nav-item.active.nav-link {
		opacity: 1;
		/*color: green;*/
		/*color: var(--green-apple);*/
		/*color: #28a745;*/
		color: #2f3;
	}

</style>
<style >
	@media (max-width: 576px){
		.recent-post > div, .most-viewed-post > div {
			display: inline-flex;
		}
	}
</style>
</head>
<body>
<!-- TopNavbar -->
<?php require 'foodienav.php'; ?>		
<!-- /.TopNavbar -->
<!-- SignUp Form -->
<?php require 'foodieLoginForm.php'; ?>
<!-- /.SignUp Form -->
<div id="topContainer" class="container-fluid row m-0 p-0 pb-4 bg-light">
	<div class="d-block bg-light text-center py-3 w-100"></div>
	<article id="content" class="col-md-12 m-0 p-0 px-4 pl-md-5">
		<section class="m-0" id="recent-post">
			<h3 id="recent-post-title" class="recent-post-title d-inline display-6">Recent Recipes</h3>
			<hr class="mt-1 mb-0 bg-warning">
			<div class="recent-posts-list row mt-2 mx-0">
				<!-- list goes here -->
			</div>
		<button class="btn btn-light float-right" id="show-more-recent-posts-home" style="border-bottom-color: grey; border-right-color: grey" >More Recent Recipes..</button>
		<!-- ./Recent Recipes ends -->

		<!-- Most viewed Recipes starts -->		
		<section class="m-0 mt-5" id="most-viewed-post">
			<h3 id="most-viewed-post-title" class="most-viewed-post-title d-inline display-6">Most Viewed Recipes</h3>
			<hr class="mt-1 mb-0 bg-warning">
		<div class="most-viewed-posts-list row mt-2 mx-0">
			<!-- list goes here -->
		</div>
		<button class="btn btn-light float-right" id="show-more-most-viewed-posts-home" style="border-bottom-color: grey; border-right-color: grey">More Similar Posts..</button>
	</article>
</div>

<footer class="blog-footer bg-dark text-light text-center p-2">
  <div class="mb-n4 text-left"><a id="BackToTop" href="#Top" class="text-light smooth-scroll">&uparrow;Back to top </a></div>
  Foodie's World created for foodies
  <div>&copy; 2020 Copyright FoodiesWorld.com</div>
</footer>
</body>
<script>
	$(function(){			

		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {recent_posts_count: recent_posts_count, home_page: 1},
			success: function(data){
				$(".recent-posts-list").append(data);
			}
		});
		recent_posts_count+=4;

		var most_viewed_posts_count = 0;
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {most_viewed_posts_count: most_viewed_posts_count, home_page: 1},
			success: function(data){
				$(".most-viewed-posts-list").append(data);most_viewed_posts_count+=4;
			}
		});
	});
</script>
</html>