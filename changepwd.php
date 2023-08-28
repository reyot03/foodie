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
</head>
<body>
<!-- TopNavbar -->
<?php require 'foodienav.php'; ?>		
<!-- /.TopNavbar -->
<div id="topContainer" class="container-fluid row m-0 p-0 pb-4 bg-light" style="min-height: 82vh">
	<div class="d-block bg-light text-center py-0 w-100"></div>
	<article id="content" class="col-md-12 m-0 p-0 px-4 pl-md-5">
		<section class="m-0" id="change-pwd">
			<h3 id="change-pwd-title" class="change-pwd-title d-inline display-6">Change Password</h3>
			<hr class="mt-1 mb-0 bg-warning">
			<div class="mt-2 mx-0 lead">
				<!--  -->
			<?php
				if(!isset($_SESSION['foodieuserid'])){
					header("location: index.php");
					exit();
				}
				$userid = $_SESSION['foodieuserid'];
				if (isset($_GET['err'])){
					echo '<p class="text-danger text-center" role="button" onclick="remove(this)">'.$_GET['err'].'</p>';
				}
				if (isset($_GET['action'])){
					echo '<p class="text-success text-center" role="button" onclick="remove(this)">Password Changed Successfully</p>';
				}
				echo '
					<form autocomplete="off" action="foodie-execute.php" method="POST" class="text-center m-auto col-md-8" onsubmit="return check_form()">
						Current Password: <input type="password" name="pass" class="form-control" title="password" required>
						New Password: <input type="password" name="newpass" class="form-control" title="password" required>
						Confirm New Password: <input type="password" name="confirmpass" class="form-control" title="password" required>
						<button type="submit" class="btn btn-primary form-control mt-3" name="submit_pwd_change">Change Password</button>

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
	function check_form() {
		let currentpass = $("form").find("input[name='pass']").val();
		let newpass = $("form").find("input[name='newpass']").val();
		let confirmpass = $("form").find("input[name='confirmpass']").val();
		$("p").remove();
		if (currentpass.length < 8 || newpass.length < 8) {
			$("form").before("<p class='text-center text-danger alert-danger' onclick='$(this).remove()'>*Password must be of atleast 8 characters long</p>");
			return false;
		}
		if(newpass != confirmpass) {
			$("Form").before("<p class='text-center text-danger' onclick='$(this).remove()'>*New passwords are different</p>");
			return false;
		}
		else {
			return confirm("Submit Change?");
		}
	}
	function remove(selector){
		selector.remove();
		window.history.replaceState("", "", 'changepwd.php');
	}
</script>
</html>