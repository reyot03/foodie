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
	.foodie-post-image, .user-profile-image {
		width: 100%;
		min-height: 140px;
	}

	.user-profile-image img {
		max-width: 310px;
		height: auto;
		object-fit: contain;
	}

	form {
		min-width: 300px;
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
		<section class="m-0" id="user-profile">
			<h3 id="user-profile-title" class="user-profile-title d-inline display-6">Profile</h3>
			<hr class="mt-1 mb-0 bg-warning">
			<div class="user-profile-details row mt-2 mx-0 lead row">
				<!--  -->
				<?php 
					$userid = $_SESSION['foodieuserid'];
					$sql_profile = "SELECT real_name, username, user_bdate, user_city, user_contact, user_country, user_email, user_level, user_profile_picture FROM foodie_users WHERE user_id = ".$userid;
					$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection Error");
					$profile_result = mysqli_query($con, $sql_profile) or die("Bad Query");
					$profile_details = mysqli_fetch_assoc($profile_result);
					$realname = $profile_details['real_name'];
					$username = $profile_details['username'];
					$userbdate = $profile_details['user_bdate'];
					$usercity = $profile_details['user_city'];
					$usercountry = $profile_details['user_country'];
					$usercontact = $profile_details['user_contact'];
					$useremail = $profile_details['user_email'];
					$userlevel = $profile_details['user_level'];
					$userprofilepicture = $profile_details['user_profile_picture'];

				?>

				<ul class="list-unstyled p-2 col-md-6">
				<li>Name: <?php echo $realname ?></li>
				<li>Username: <?php echo $username ?></li>
				<li>Email: <?php echo $useremail ?></li>
				<li>Birth date: <?php echo $userbdate ?></li>
				<li>Contact: <?php echo $usercontact ?></li>
				<li>Location: <?php printf("%s, %s", $usercity, $usercountry) ?></li>
				</ul>
				<div class="user-profile-image col-md-6">
					<?php printf("<img src='img/%s'>", $userprofilepicture) ?>
				</div>
				<button class="btn btn-warning d-inline mr-3 mt-3 edit-profile">Edit Profile</button>
				<button class="btn btn-warning d-inline mt-3 change-pwd">Change Password</button>
			</div>
			<?php if(isset($_GET['editac']) && $_GET['editac']=="success") echo '<p class="btn text-success mt-4" role="button" onclick="remove(this)">Update: Success</p>' ?>
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
		window.history.replaceState("", "", 'profile.php');
	}
</script>

</html>