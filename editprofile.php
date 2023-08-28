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
	<div class="d-block bg-light text-center py-3 w-100"></div>
	<article id="content" class="col-md-12 m-0 p-0 px-4 pl-md-5">
		<section class="m-0" id="edit-profile">
			<h3 id="user-profile-title" class="user-profile-title d-inline display-6">Edit Profile</h3>
			<hr class="mt-1 mb-0 bg-warning">
			<div class="user-profile-details mt-2 mx-0 lead">
				<!--  -->
			<?php
				if(!isset($_SESSION['foodieuserid'])){
					header("location: index.php");
					exit();
				}
				$userid = $_SESSION['foodieuserid'];
				if (!isset($_GET['editerr'])){
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
				} else {
					$realname = $_GET['realname'];
					$username = $_GET['username'];
					$userbdate = $_GET['bdate'];
					$usercountry = $_GET['country'];
					$usercity = $_GET['city'];
					$usercontact = $_GET['contactnum'];
					$useremail = $_GET['email']; 
					echo '<p class="text-danger text-center" role="button" onclick="$(this).remove()">'.$_GET['editerr'].'</p>';
				}
				echo '
					<form autocomplete="off" action="foodie-execute.php" method="POST" class="text-center m-auto col-md-8" onsubmit="return confirm(\'submit it?\')">
						Name:<input type="text" name="realname" class="form-control" value="'.$realname.'" title="Your real name" autofocus required>
						Username:<input type="text" name="username" class="form-control" value="'.$username.'" title="Your user name" required>
						Email:<input type="email" name="email" class="form-control" value="'.$useremail.'" title="Email" required>
						Birthdate:<input type="date" name="bdate" class="form-control" value="'.$userbdate.'" title="Birthdate" required>
						City:<input type="text" name="city" class="form-control" value="'.$usercity.'" title="City" required>
						Country:<input type="country" name="country" class="form-control" value="'.$usercountry.'" title="Country" required>
						Contact:<input type="contact" name="contactnum" class="form-control" value="'.$usercontact.'" title="Contact" required>
						Current Password: <input type="password" name="pass" class="form-control" title="password" required>
						<button type="submit" class="btn btn-primary form-control mt-3" name="submit_profile_edit">Change Info</button>

					</form>
				';
			?>
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
	$("form").find("input").keypress(function(event){
		if(event.which==13){
			// event.preventDefault();
			$(this).nextAll('INPUT').first().focus();
			return false;
		}
		// event.stopPropagation();
	});
</script>
</html>