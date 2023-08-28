
<?php

require 'foodieHeader.php';
session_start();

if(isset($_GET['rid']) && is_numeric($_GET['rid'])){
	$recipe_id = $_SESSION['rid'] = $_GET['rid'];

} 
else header("location: index.php");

$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection Error: ".mysqli_connect_error());
$sql_recipe = "SELECT recipe_heading, username, recipe_date, recipe_ingredients, recipe_steps, recipe_img_1, recipe_img_2, recipe_img_3, recipe_notes, kudos_num as kudos_count, recipe_tags  FROM foodie_recipes_explore as a, foodie_users as b WHERE a.recipe_id = $recipe_id AND a.owner_id = b.user_id";
$result_recipe = mysqli_query($con, $sql_recipe);
$resultCheck = mysqli_num_rows($result_recipe);
if ($resultCheck < 1) {
	// header("location: index.php");
	die("resultCheck: ".$resultCheck);
}

$sql_comment = "SELECT f.*, u.username as commentor FROM foodie_comments as f, foodie_users as u WHERE f.recipe_id=$recipe_id AND u.user_id=f.commentor_id ORDER BY f.comment_id DESC LIMIT 2";


$sql_comment_count = "SELECT count(comment_id) as comments_count FROM foodie_comments as c WHERE c.recipe_id=$recipe_id";
$result_comment = mysqli_query($con, $sql_comment);
$result_comment_count = mysqli_query($con, $sql_comment_count);

$row_recipe = mysqli_fetch_assoc($result_recipe);
// $row_comment = mysqli_fetch_assoc($result_comment);
$row_comment_count = mysqli_fetch_assoc($result_comment_count);


// $recipe_id = $row['recipe_id'];
$recipe_heading = $row_recipe['recipe_heading'];
$recipe_owner = $row_recipe['username'];
$date = date("F j, Y", strtotime($row_recipe['recipe_date']));
$ingredients_list = json_decode($row_recipe['recipe_ingredients'],true);
$steps_list = json_decode($row_recipe['recipe_steps'],true);
$image1 = $row_recipe['recipe_img_1'];
$image2 = $row_recipe['recipe_img_2'];
$image3 = $row_recipe['recipe_img_3'];
$images = [$image1, $image2, $image3];
$recipe_notes = $row_recipe['recipe_notes'];
$kudos_count = $row_recipe['kudos_count'];
$_SESSION['recipe_tags'] = explode(" ", str_replace(",", "", $row_recipe['recipe_tags']));
$_SESSION['recipe_heading'] = $recipe_heading;
$comments_count = $row_comment_count['comments_count'];



// mysqli_close($con);


?>
<script>
	var recent_posts_count = 0;
	$(document).on('ready', function(){
		$("#show-more-related-posts").click();
		$("#show-more-recent-posts").click();
		$("#show-more-recent-posts").click();
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
<!-- <link rel="stylesheet" type="text/css" href=""> -->


<style>
.underlined {
  text-decoration: underline;
}

ol, ul {
	padding-left: 1.4rem;
}
ol, ul, .recipe-notes > p, .foodie-response > div:first-child {
	font-family: -apple-system,"Segoe UI", Arial, sans-serif;
}

.foodie-response > div > span {
	cursor: pointer;
}
</style>
<style>
	/*fitting and vertically aligning all images in carousel*/
	#myslider {
		height: 400px;
		width: 100%;
		background-color: #7777;
	}
	#myCarousel div div img {
		width: 100%;
		max-width: 100%;
		max-height: 400px;
		object-fit: contain;
		position: relative;
		top: 0;
		bottom: 0;
		left: 50%;
		transform: translateX(-50%);
	}
	#myCarousel {
		line-height: 400px;
		margin:auto;
		background-color: #f8f9fa;
	}
	@media (max-width: 735px) {
		#myslider {
			height: 200px;
		}
		#myCarousel div div img {
			max-height: 200px;
		}
		#myCarousel {
			line-height: 200px;
		}
	}
	.fa-edit {
		color: var(--warning);
	}
	.fa-trash {
		color: var(--danger);
	}
</style>

<body>

<!-- TopNavbar -->
<?php require 'foodienav.php'; ?>
<!-- /.TopNavbar -->
<!-- SignUp Form -->
<div id="signform-div" class="d-none row" style="top:0">
	<div id="signbg" style="position:absolute;height: 100%;width: 100%; background-color: rgba(0,0,255,0.3);">
	</div>
	<form class="form-signin pb-4 col-md-3" id="form-signin" action="foodieSignin.inc.php" method="POST">
		<img class="mb-4" src="img/foodie.png" alt="foodie" width="100" height="72">
		<h1 class="h3 mb-3 font-weight-normal font-warning">Please sign in</h1>
		<div id="SignUpErrorBox" class="text-danger"><?php if(isset($_GET['loginerror'])) echo $_GET['loginerror']; ?></div>
		<label for="inputEmail" class="sr-only">Email address</label>
		<input title="Email address or Username" type="text" name="email" id="inputEmail" class="form-control" placeholder="Email address or Username" required>
		<label for="inputPassword" class="sr-only">Password</label>
		<input title="Password" type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
		<div class="checkbox mb-2">
			<label>
				<input type="checkbox" value="remember-me"> Remember me
			</label>
		</div>
		<button class="btn btn-lg btn-warning btn-block mb-3" type="submit" name="Signin">Sign in</button>
		<label for="foodieSignup_link">No Foodie Account?</label>
		<a class="link mb-2" id="foodieSignup_link" href="foodieSignup.php">Click here to SignUp Now!!</a>
		<a class="link mt-3 d-block" href="forgotPwd.php">Forgot Password?</a>

		<p class="text-muted m-0 mt-2 p-0" style="cursor:default;line-height: normal">&copy; 2020-2021</p>
	</form>
</div>
<!-- /.SignUp Form -->
<div id="topContainer" class="container-fluid row m-0 p-0 pb-4 bg-light">
	<div class="d-block bg-light text-center py-3 w-100"></div>
	<article id="content" class="col-md-8 m-0 p-0 px-4 pl-md-5">
		<!-- Using php to load ingredients from database  -->
		<?php
		echo '
		<h5><u>Recipe</u><!-- <hr class="mt-0 mb-3 bg-danger"> --></h5>
		<section class="m-0" id="recipe" recipe_id="'.$recipe_id.'">
			<h1 id="recipe-title" class="recipe-title mb-0 d-inline display-4">
			'.$recipe_heading.'
			</h1>
			<hr class="m-0 mb-0 bg-warning">
			<div class="owner float-right">By <span>
			'.$recipe_owner.'
			</span>, On <span>'. $date .'</span></div>

			<div class="ingredients mt-4">
				<h4>Ingredients<hr class="mt-0 bg-warning"></h4>
				<ul class="ingredients-list">
		';
					foreach ($ingredients_list as $ingredients) {
					 	echo "<li>$ingredients</li>";
					 }
			echo '
				</ul>
			</div>
			';
			?>
			<div class="recipe-steps mt-4">
				<h4>Steps<hr class="mt-0 bg-warning"></h4>
				<ol class="recipe-steps-list text-justify">
					<!-- Loading recipe steps -->
				<?php
					foreach ($steps_list as $step) {
					 	echo "<li>$step</li>";
					 }
				?>
				</ul>
			</div>
			<div class="recipe-notes mt-4">
				<h4>Notes<hr class="mt-0 bg-warning"></h4>
				<p class="text-justify">
					<!-- Loading recipe notes  -->
					<?php echo $recipe_notes ?>
				</p>
			</div>

			<div class="recipe-images mt-4">
				<h4>Images<hr class="mt-0 bg-warning"></h4>
				<div id="myslider">
					<div id="myCarousel" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
						  <?php
						  	$active = "active";
						  	$i = 0;
						  	foreach ($images as $image) { 
						  		if ($image == Null)
						  			break;
						  		
						  	 	echo '
						  	 	<li data-target="#myCarousel" data-slide-to="'.$i.'" class="'.$active.'"></li>
						  	 	';
						  	 	$i++;
						  	 	$active = Null;
						  	 }
						  ?>
						</ol>
						<div class="carousel-inner">
						<?php
							$active = "active";
							foreach ($images as $image) {
								if ($image == Null)
									break;
								echo '
								  <div class="carousel-item '.$active.'">
								    <img src="img/'.$image.'" alt="'.$recipe_heading.'">
								  </div>
								';
								$active = Null;
							 }
						?>
						</div>
						<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
						  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
						  <span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
						  <span class="carousel-control-next-icon" aria-hidden="false"></span>
						  <span class="sr-only">Next</span>
						</a>
					</div>
				</div>
			</div> <!-- ./recipe-images -->
			<div class="foodie-response mt-5">
				<div>
					Kudos: <span class="post-kudos"><span class="kudos_given_icon">🤍</span><span class="kudos-num"><?php echo $kudos_count; ?></span></span> | Comments: <span class="recipe-comments">🧾<span class="comments-num"><?php echo $comments_count; ?></span></span>
				</div>
				<div class="panel panel-info">
					<div class="lead mt-2 panel-heading">Comments<hr class="mt-0 bg-warning"></div>
					<div class="comments-list card panel-body p-2">

						<?php
						function display_comments(){
							global $result_comment;
							while ($row_comment = mysqli_fetch_assoc($result_comment)) {
								$comment_id = $row_comment['comment_id'];
								$commentor_name = $row_comment['commentor'];
								$date = date("F j, Y \A\\t g:i A", strtotime($row_comment['comment_date']));
								$comment_text = $row_comment['comment_des'];
								echo '
									<div class="list-group p-2" comment_id="'.$comment_id.'">
										<div><span class="commenter">'.$commentor_name.'</span><span> - </span> <span class="comment-date">'.$date.'</span></div>
										<div class="comments-list-comment card-text">'.$comment_text.'</div>
									</div>
								';
							}
						}
						display_comments();
						?>
					</div>
					<button class="btn btn-primary mt-1" id="show-more-comments">Show More Comments..</button><button class="btn btn-danger float-right mt-1 mb-3 d-none" id="show-less-comments">Show Less</button>
					<textarea type='text' name='input-comment' class='form-control mt-3' placeholder='Give your comment here...' title='Give your comment here' id='comment-box'></textarea>
					<button class='btn btn-warning my-1' fname='commentSubmit' id='give-comment'> Comment </button><span id="comment_state_msg" class="pl-4"></span>
					<script></script>
				</div>
			</div>
		</section>
	</article>
	<style>
		.related-post, .recent-post {
			margin-bottom: 1.25em;
		}
	</style>
	<aside class="col-md-4 m-0 p-0 px-4">
		<section class="related-posts card p-4">
			<h5>Related Posts<hr class="m-0 mt-0 bg-danger"></h5>
			<div class="related-posts-list"></div>
			<button class="btn btn-light" id="show-more-related-posts">More Related Posts..</button>
		</section>
		<section class="recent-posts card p-4">
			<h5>Recent Posts<hr class="m-0 mt-0 bg-danger"></h5>
			<div class="recent-posts-list">
			</div>
			<button class="btn btn-light" id="show-more-recent-posts">More Recent Posts..</button>
		</section>
	</aside>
</div>

<footer class="blog-footer bg-dark text-light text-center p-2">
  <div class="mb-n4 text-left"><a id="BackToTop" href="#Top" class="text-light smooth-scroll">&uparrow;Back to top </a></div>
  Foodie's World created for foodies
  <div>&copy; 2020 Copyright FoodiesWorld.com</div>
</footer>
</body>


<?php 
if(!isset($_SESSION['foodieuserid'])){
	// script for Login button or Account button in navbar
	echo "
		<script>
			$(function() {
					$('.nav-link.Account').html('Login');
					$('.nav-link.Account').attr('id','loginbtn');
				});
		</script>
		";
	}
else {
	echo "
		<script>
			$(function() {
					$('.nav-link.Account').html('Account');
					$('.nav-link.Account').attr('id','accountbtn');
					$('.nav-link.Account').attr('data-toggle','dropdown')
				});
		</script>
		";
	}
	// script forLogin button or Account button in navbar ends

if(isset($_GET['loginerror'])){
	echo "<script> $(function() {show_SignUp_Form(); })</script>";
}
?>

<script>
	$(function(){
		$("#loginbtn").click(function() {
		if($('#signform-div').hasClass('d-none')){
			show_SignUp_Form();
		} else {
			$('#signform-div').addClass('d-none');
		}
	});
		
	})
</script>

<script>
	$(function(){
		var related_posts_count = 0;
		recipe_id = $("#recipe").attr('recipe_id');
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {related_posts_count: related_posts_count, recipe_id: recipe_id},
			success: function(data){
				$(".related-posts-list").append(data);
				related_posts_count+=2;
			}
		});


		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {recent_posts_count: recent_posts_count, recipe_id: recipe_id},
			success: function(data){
				$(".recent-posts-list").append(data);
				recent_posts_count+=2;
			}
		});
	});	


</script>
</html>