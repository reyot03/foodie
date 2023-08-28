<?php
if(isset($_POST['signup'])){
	$con = mysqli_connect('localhost', 'root', '', 'foodie');
	if(!$con)
		die("Connection failed: ".mysqli_connect_error());
//retrieving data from form
	$realnamef = $_POST['realnamef'];
	$realnamel = $_POST['realnamel'];
	$username = $_POST['username'];
	$email = $_POST['email'];
	$country = $_POST['country'];
	$city = $_POST['city'];
	$bdate = $_POST['bdate'];
	$countrycodeval = $_POST['countrycodeval'];
	$contactnum = $_POST['contactnum'];
	$newpass = $_POST['newpass'];
	$confirmpass = $_POST['confirmpass'];

// saving all data to a variable to send back
	$forminfo = "realnamef=".$realnamef."&realnamel=".$realnamel."&username=".$username."&email=".$email."&country=".$country."&city=".$city."&bdate=".$bdate."&countrycodeval=".$countrycodeval."&contactnum=".$contactnum;

// checking for empty fields
	if(empty($realnamef) || empty($realnamel) || empty($username) || empty($email) || empty($country) || empty($city) || empty($bdate) || empty($countrycodeval) || empty($contactnum)|| empty($newpass) || empty($confirmpass) ){
		header("location:foodieSignup.php?error=*fill all fields&".$forminfo);
	}
// checking for invalid characters
	else if (!preg_match("/^[a-zA-Z0-9]*$/", $realnamef)||!preg_match("/^[a-zA-Z0-9]*$/", $realnamel)||!preg_match("/^[a-zA-Z0-9]*$/", $username)||!preg_match("/^[a-zA-Z0-9]*$/", $country)||!preg_match("/^[a-zA-Z0-9]*$/", $city)||!preg_match("/^[a-zA-Z0-9]*$/", $contactnum)){
		header("location:foodieSignup.php?error=*invalid characters somewhere&".$forminfo);
		exit();
	}
// checking for length of username
	else if(strlen($username)<5){
		header("location:foodieSignup.php?error=*username must be of atleast 5 characters&".$forminfo);
		exit();
	}
// checking if two passwords match
	else if ($newpass != $confirmpass){
		header("location:foodieSignup.php?pwd=ok&error=*passwords doesnot match&".$forminfo);
		exit();
	}
// checking if password is 8 or more characters
	else if(strlen($newpass)<8){
		header("location:foodieSignup.php?pwd=ok&error=*password must be of atleast 8 characters&".$forminfo);
		exit();
	}
	else{
// checking if account already exists
		$sql = " SELECT foodie_users.user_id FROM foodie_users WHERE username=? OR user_email=?";
		$stmt = mysqli_stmt_init($con);

		if(!mysqli_stmt_prepare($stmt,$sql)){
			header("location:foodieSignup.php?error=*sqlerror");
		exit();
		}else {
			mysqli_stmt_bind_param($stmt,"ss",$username,$email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);
			if($resultCheck>0) {
				header("location:foodieSignup.php?error=*username and email not available or already in use&".$forminfo);
		exit();
			}else {
				$sql = "INSERT INTO foodie_users (real_name, username, user_email, user_pwd, user_country, user_city, user_bdate, user_contact) VALUES (?,?,?,?,?,?,?,?)";
				$stmt = mysqli_stmt_init($con);
				if(!mysqli_stmt_prepare($stmt, $sql)){
					header("location:foodieSignup.php?error=*sqlerror");
					exit();
				}else {
					$realname = $realnamef." ".$realnamel;
					$wholecontactnum = $countrycodeval.$contactnum;
					$encryptpas = "foodie".$newpass."foodie";
					$hashedPwd = password_hash($encryptpas, PASSWORD_DEFAULT);//using bcrypt hashing method
					mysqli_stmt_bind_param($stmt,"ssssssss",$realname,$username,$email,$hashedPwd,$country,$city,$bdate,$wholecontactnum);
					mysqli_stmt_execute($stmt);

					$query = mysqli_query($con,"SELECT * FROM foodie_users WHERE username='$username' AND user_email='$email'");
					$user = mysqli_fetch_assoc($query);
					session_start();
					$_SESSION['foodieuserid'] = $user['user_id'];
					$_SESSION['foodieusername'] = $username;
					$_SESSION['foodieuseremail'] = $email;
					$_SESSION['foodielogin'] = 'true';
					//header('location:foodieSignup.php?error='.$userid['idusers']);
					if($_SESSION['userid']){
						header("location:index?login=success");			
					}else{
						header("location:index.php?msg=login failed. Please try again!!");
					}
					exit();
				}

			}
		}
	
	}
	mysqli_stmt_close($stmt);
	mysqli_close($con);
}
else {
	header("location:index.php");
	exit();
}
