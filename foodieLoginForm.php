<div id="signform-div" class="d-none row" style="top:0">
	<div id="signbg" style="position:absolute;height: 100%;width: 100%; background-color: rgba(0,0,255,0.3);">
	</div>
	<form class="form-signin pb-4 col-md-3" id="form-signin" action="foodieSignin.inc.php" method="POST">
		<img class="mb-4" src="img/foodie.png" alt="" width="100" height="72">
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