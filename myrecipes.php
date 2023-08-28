<?php
	require 'foodieHeader.php';
	session_start();
	if(!isset($_SESSION['foodieuserid'])) {
		header("location: index.php");
	}
	$_SESSION['rid'] = null;
?>
<script>
	$(document).on('ready', function(){

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

	input {
		margin-bottom: 16px;
	}

</style>
<body>
<!-- TopNavbar -->
<?php require 'foodienav.php'; ?>		
<!-- /.TopNavbar -->
<div id="topContainer" class="container-fluid row m-0 p-0 pb-4 bg-light" style="min-height: 82vh">
	<!-- <div class="d-block bg-light text-center py-1 w-100"></div> -->
	<article id="content" class="col-md-12 m-0 mt-4 p-0 px-4 pl-md-5">
		<section class="m-0" id="myrecipes">
			<h3 id="myrecipes-title" class="myrecipes-title d-inline display-6">My recipes</h3>
			<hr class="mt-1 mb-0 bg-warning">
			<div class="myrecipes-list row mt-2 mx-0 lead row">
			<?php 
				$userid = $_SESSION['foodieuserid'];
				$sql_my_recipes = "SELECT recipe_id, recipe_img_1, recipe_heading, comments_num, kudos_num, recipe_date FROM foodie_recipes_explore as f WHERE owner_id = ".$userid." ORDER BY f.recipe_date DESC";
				$con = mysqli_connect("localhost", "root", "", "foodie");
				$result_my_recipes = mysqli_query($con, $sql_my_recipes) or die("Bad Query: ".$sql_my_recipes);
				while($row_my_recipes = mysqli_fetch_assoc($result_my_recipes)) {
					$my_recipe_id = $row_my_recipes['recipe_id'];
					$my_recipe_image = $row_my_recipes['recipe_img_1'];
					$my_recipe_heading = $row_my_recipes['recipe_heading'];
					$my_recipe_comments_num = $row_my_recipes['comments_num'];
					$my_recipe_kudos_num = $row_my_recipes['kudos_num'];
					$my_recipe_recipe_date = date("F j, Y", strtotime($row_my_recipes['recipe_date']));
				echo '
				<div class="recipe-card myrecipes-post section-post px-3 card col-md-3 m-0">
					<div post-id="'.$my_recipe_id.'" class="row">
						<div class="foodie-post-image col-6 col-md-12 p-1">
							<img class="img-fluid" src="img/'.$my_recipe_image.'" alt="No image available">
						</div>
						<div class="pl-2 col-6 col-md-12">
							<div class="heading font-weight-bold">'.$my_recipe_heading.'</div>

							<div>By <span class="owner">Swasthi -</span><span class="post-date"> '.$my_recipe_recipe_date.' </span></div>
							<div>🧡<span class="kudos">'.$my_recipe_kudos_num.'</span> 🧾<span>'.$my_recipe_comments_num.'</span></div>
						</div>
						<p class="col-12 mt-2 text-right">
							<button class="btn btn-warning edit-recipe mr-2">Edit</button>
							<button class="btn btn-danger delete-recipe">Delete</button>
						</p>
					</div>
				</div>
				';
			}
			?>
			</div>
			<!-- <a href="insertRecipe.php"> -->
				<button class="btn btn-primary m-3 post-recipe">Post new recipe</button>
			<!-- </a> -->
		</section>
	</article>
</div>
<footer class="blog-footer bg-dark text-light text-center p-2">
  <div class="mb-n4 text-left"><a id="BackToTop" href="#Top" class="text-light smooth-scroll">&uparrow;Back to top </a></div>
  Foodie's World created for foodies
  <div>&copy; 2020 Copyright FoodiesWorld.com</div>
</footer>
</body>
<script>
	$(function() {
			$('.nav-link.Account').html('Account');
			$('.nav-link.Account').attr('id','accountbtn');
			$('.nav-link.Account').attr('data-toggle','dropdown')
	});
	function remove(selector){
		selector.remove();
		window.history.replaceState("", "", 'myrecipes.php');
	}
</script>
	<script type="text/javascript" src="js/foodie.js"></script>

</html>