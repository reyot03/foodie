<?php
if(isset($_POST['Signin'])){
	// include 'foodieHeader.php';
	$con = mysqli_connect('localhost','root','','foodie');
	if(!$con)
		die("Connection faied: ".mysqli_connect_error());

	$email = $_POST['email'];
	$password = $_POST['password'];

	session_start();
	if (isset($_SESSION['rid'])) {
		$referer = "recipe.php?rid=".$_SESSION['rid']."&";
	} else {
		$referer = "index.php?";
	}

	if(empty($email)||empty($password)){
		header("location:".$referer."loginerror=*Please fill all the box");
		exit();
	}else {
		$sql = "SELECT * FROM foodie_users WHERE username=? OR user_email=?; ";
		$stmt = mysqli_stmt_init($con);
		if((!mysqli_stmt_prepare($stmt,$sql))){
			header("location:".$referer."loginerror=sqlerror");
			exit();
		}else {
			mysqli_stmt_bind_param($stmt, "ss", $email, $email);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			if($row = mysqli_fetch_assoc($result)){
				$pwdCheck = password_verify("foodie".$password."foodie", $row['user_pwd']);
				if($pwdCheck == false){
					header("location:".$referer."loginerror=*Incorrect Password&email=".$email);
					exit();
				} else if($pwdCheck == true){
					session_unset();
					$_SESSION['foodieuserid'] = $row['user_id'];
					$_SESSION['foodieusername'] = $row['username'];
					$_SESSION['foodieuseremail'] = $row['user_email'];
					$_SESSION['foodielogin'] = 'true';
					
					header("location:".$referer."login=success");
					exit();
				}

			} else{
				header("location:".$referer."loginerror=*Invalid Username or Password");
				exit();
			}
		}
	}

} else{
		header("location:index.php");
		exit();
}