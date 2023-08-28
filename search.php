<?php
	require 'foodieHeader.php';
	session_start();
	$_SESSION['rid'] = null;
	$keywords = mysqli_real_escape_string(mysqli_connect("localhost", "root", "", "foodie"), $_GET['q']);
	printf('<script>var keywords = "%s";</script>', $keywords);
?>
<script>
	var search_result_lower_limit = 0;
	function show_search_result(lower_limit = 0) {
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {show_search_result: 1, keywords: keywords, lower_limit: lower_limit},
			success: function(data){
				$(".search-result-list").append(data);
				if(data == '') {
					$("#show-more-search-result").hide();
				}
			}
		});
	}
	$(document).on('ready', function(){
		show_search_result();
		$("#show-more-search-result").click(function(){
			search_result_lower_limit += 8;
			show_search_result(search_result_lower_limit);
		})
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

</style>
<style >
	@media (max-width: 576px){
		.related-post > div, .most-viewed-post > div, .search-result > div {
			display: inline-flex;
		}
	}
	.related-post, .most-viewed-post, .search-result {
		border: solid #f8f9fa 5px;
		border-width: 5px 8px;
	}
	.foodie-post-image {
		width: 100%;
		min-height: 140px;
	}
</style>
<body>
<!-- TopNavbar -->
<?php require 'foodienav.php'; ?>			
<!-- /.TopNavbar -->

<div id="topContainer" class="container-fluid row m-0 p-0 pb-4 bg-light">
	<div class="d-block bg-light text-center py-3 w-100"></div>
	<article id="content" class="col-md-12 m-0 p-0 px-4 pl-md-5">
		<section class="m-0" id="search-result">
			<h3 id="search-result-title" class="search-result-title d-inline display-6">Search Result</h3>
			<hr class="mt-1 mb-0 bg-warning">
			<div class="search-result-list row mt-2 mx-0">
				<!-- search result filled by AJAX -->
			</div>
			<button class="btn btn-light float-right" id="show-more-search-result" style="border-bottom-color: grey; border-right-color: grey" >More Recipes..</button>
		</section>
	</article>
</div>

<footer class="blog-footer bg-dark text-light text-center p-2">
  <div class="mb-n4 text-left"><a id="BackToTop" href="#Top" class="text-light smooth-scroll">&uparrow;Back to top </a></div>
  Foodie's World created for foodies
  <div>&copy; 2020 Copyright FoodiesWorld.com</div>
</footer>
</body>
<!-- SignUp Form -->
<?php require 'foodieLoginForm.php'; ?>
<!-- /.SignUp Form -->
</html>