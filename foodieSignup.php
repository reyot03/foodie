<?php
	require 'foodieHeader.php';
?>

<style>

*, .form-control, .btn {
  font-family: monotype-corsiva,'Handlee',fantasy,cursive,monospace, Arial;
  letter-spacing: 0.05rem;
  font-weight: bold;
  color: rgba(0,0,0,1);
}

body{
	/*background-image: url('img/loginbg.jpg');*/
	background-image: url('img/img25.jpg');
	background-attachment: fixed;
	background-position: bottom;
	background-size: cover;
	background-repeat: no-repeat;
	justify-content: center;
}

#form-signup{
	top:0;
	bottom:0;
	left:0;
	right:0;
	margin: auto;
	background-color: rgba(200, 200, 200, 0.5);
	text-align: center;
	height: auto;
}

.form-signup > input, .form-signup > .input-group {
	margin-bottom: 16px;
}

a:hover {
	text-decoration: none;
}

/*
button {
	height:30px;
	width: 70px;
	color: darkgreen;
	background-color: lightblue;
}
input{
	color: darkgreen;
	width:230px;
	height: 30px;
	dborder: none;
	font-size: 1.1em;
	padding:2px;
}
#contactnum{
	width:230px;
	margin-top:0;
}
a{
	color: blue;
	background-color: transparent;
}
a:hover{
	background-color: transparent;
}*/
.errorbox {
	border:2px solid rgba(255,0,0,0.8);
}

</style>
<!-- <script src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.4.1.min.js"></script> -->
<!-- <script type="text/javascript" src="js/npm.js"></script>
<script src="js/bootstrap.min.js.map"></script> -->

<form class="form-signup px-md-4 pt-3 pb-4 col-md-4" id="form-signup" style="width: 360px;max-width: 100%;" action="foodieSignup.inc.php" method="POST" jautocomplete="off" onsubmit="return confirm('Submit this form?')">
	<img class="mb-4" src="img/foodie.png" alt="" width="100" height="72">
	<h1 class="h3 mb-3 font-weight-normal font-warning">Please fillup the form</h1>
	<div id="msg" style="color:red;margin-top:10px;padding-bottom: 10px"></div>
	<div class="input-group">
		<input class="form-control" title="Your First Name" id="realnamef" type="text" name="realnamef" placeholder="Your First Name" autofocus required>
		<input class="form-control" id="realnamel" type="text" name="realnamel" placeholder="Your Last Name"  title="Your Last Name"autofocus required>
	</div>
	<input class="form-control" id="username" type="text" name="username" placeholder="Username"  title="Username"required>
	<input class="form-control" id="email" type="email" name="email" placeholder="Email"  title="Email Adress"required autocomplete="on">
	<div class="input-group">
		<input class="form-control" id="country" type="text" name="country" placeholder="Country"  title="Country"required>
		<input class="form-control" id="city" type="text" name="city" placeholder="City"  title="City"required>
	</div>
	<input class="form-control" id="bdate" type="date" name="bdate" placeholder="1990-01-01"  title="Date of Birth" min='1920-01-01' max='2019-01-01' required />
	<div class="input-group row mx-auto">
		<select id="countrycodeval" class="col-2 m-0 p-0" name="countrycodeval" title="countrycode">
			<option value="+86" selected> +86 </option>
			<option value="+977"> +977 </option>
			<option value="+44"> +44 </option>
			<option value="+91"> +91 </option>
		</select>
		<input class="form-control col-10" id="contactnum" type="number" name="contactnum" placeholder="Contact Number" title="Mobile Number" min='9800000000' max='9900000000' required autocomplete="on">
	</div>
	<input class="form-control" class="password" type="password" name="newpass" placeholder="New password" title="New Password" required>
	<input class="password form-control" type="password" name="confirmpass" placeholder="Confirm password" title="Confirm Password" required>
	<button class="btn btn-lg btn-warning btn-block mb-3" type="submit" name="signup">Signup</button>

	<p class="ntext-muted m-0 mt-2 p-0" style="cursor:default;line-height: normal;color: #fff;opacity: 1">&copy; 2020-2021</p>
</form>
<script>
$("form").find("input:text").keypress(function(event){
if(event.which==13){
	// event.preventDefault();
	$(this).next('INPUT').focus();
	return false;
}
// event.stopPropagation();
});
</script>

<?php
// showing input mistakes info/message to applicant
	if(isset($_GET['error'])){
		$error = $_GET['error'];
		echo '<script> $("#msg").html("'.$error.'") </script>';

// providing previous inputs in input fields
		$infolist = array('realnamef' => 'realnamef', 'realnamel' => 'realnamel', 'username' => 'username', 'email' => 'email', 'country' => 'country', 'city' => 'city', 'bdate' => 'bdate', 'countrycodeval' => 'countrycodeval', 'contactnum' => 'contactnum');
		foreach ($infolist as $key => $value) {
			if(isset($_GET[$value])){
				$infolist[$key] = $_GET[$value];
				echo '<script> $("#'.$key.'").val("'.$infolist[$key].'") </script>';
			}else {
				$infolist[$key]=0;
			}		
		}
	} else {
		$error=0;
	}


?>