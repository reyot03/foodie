<?php
	
	if (isset($_POST['kudos_given_check'])) {
		session_start();
		if(isset($_SESSION['foodieuserid'])){
			$user_id = $_SESSION['foodieuserid'];
			$recipe_id = $_POST['recipe_id'];
			$con = mysqli_connect("localhost", "root", "", "foodie");
			$check_kudos_given_query = "SELECT kudos_id FROM foodie_kudos WHERE giver_id = $user_id AND recipe_id = $recipe_id AND kudos_value = 1";
			$sql_result = mysqli_query($con, $check_kudos_given_query) or die("Bad Query".$check_kudos_given_query);
			$result_num = mysqli_num_rows($sql_result);
			mysqli_close($con);
			if($result_num != 0) { 
				echo '💙';
				exit();
			}
		}
		echo '🤍';
		exit();
	}

	// for increasing and decreasing kudos count
	if (isset($_POST['post_kudos'])) {
		session_start();
		if(isset($_SESSION['foodieuserid'])){
			$user_id = $_SESSION['foodieuserid'];
			$recipe_id = $_POST['recipe_id'];
			$con = mysqli_connect("localhost", "root", "", "foodie");
			$query_check_kudos = "SELECT kudos_value FROM foodie_kudos WHERE giver_id = $user_id AND recipe_id = $recipe_id";
			$sql_result = mysqli_query($con, $query_check_kudos) or die("Bad Query");
			$result_num = mysqli_num_rows($sql_result);
			if($result_num == 0) {
				$query_give_kudos = "INSERT INTO foodie_kudos VALUES(NULL, $user_id, $recipe_id, 1)";
				$query_update_kudos_num = "UPDATE foodie_recipes_explore SET kudos_num = kudos_num + 1 WHERE recipe_id = $recipe_id";
				mysqli_query($con, $query_give_kudos) or die("Bad Query".$query_give_kudos);
				mysqli_query($con, $query_update_kudos_num) or die("Bad Query".$query_update_kudos_num);
				mysqli_close($con);

				echo "given";
				exit();
			} else if($result_num == 1) {
				$row = mysqli_fetch_assoc($sql_result);
				if($row['kudos_value'] == '1'){
					$change_value = 0;
					$update_change = -1;
					echo "taken";

				} else if ($row['kudos_value'] == '0') {
					$change_value = 1;
					$update_change = 1;
					echo "given";
				} else {
					echo 'none';
					exit();
				}

				$query_update_kudos_value = "UPDATE foodie_kudos SET kudos_value = $change_value WHERE giver_id = $user_id AND recipe_id = $recipe_id";
				$result = mysqli_query($con, $query_update_kudos_value) or die("Bad Query: ".$query_update_kudos_value);

				$stmt = mysqli_stmt_init($con);
				mysqli_stmt_prepare($stmt, "UPDATE foodie_recipes_explore SET kudos_num = kudos_num + ? WHERE recipe_id = $recipe_id");
				mysqli_stmt_bind_param($stmt, "s", $update_change);
				mysqli_stmt_execute($stmt);

				// echo "changed to ".$change_value;
			}

		} else {
			echo 'noId';
		}
		exit();
	}

	// for showing more and less comments
	if(isset($_POST['comments_count'])){
		$comments_count = $_POST['comments_count'];
		if ($comments_count < 2 || !isset($_POST['recipe_id']) ){
			echo "Please don't mess with my code";
			exit();
		}
		$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection Error ".mysqli_connect_error());
		session_start();
		$recipe_id = $_SESSION['rid'];
		$sql_comment = "SELECT f.*, u.username as commentor  FROM foodie_comments as f, foodie_users as u WHERE f.recipe_id = $recipe_id AND u.user_id=f.commentor_id ORDER BY f.comment_id DESC  LIMIT ".$comments_count;
		if (isset($_POST['more'])) {
			$sql_comment .= ", 2";
		}
		$result_comment = mysqli_query($con, $sql_comment) or die("Bad Query: $sql");
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

		mysqli_close($con);
		exit();
	}

	// for inserting comments in database
	if(isset($_POST['give_comment'])) {
		session_start();
		if(isset($_SESSION['foodieuserid'])){
			$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection Error ".mysqli_connect_error());
			$text = $_POST['text'];
			$recipe_id = $_POST['recipe_id'];
			$commentor_id = $_SESSION['foodieuserid'];
			date_default_timezone_set('Asia/Kathmandu');
			$date = date('Y-m-d H:i:s');

			$sql_give_comment = "INSERT INTO  foodie_comments (recipe_id, commentor_id, comment_des, comment_date) VALUES(?,?,?,?)";
			$stmt = mysqli_stmt_init($con);
				if(!mysqli_stmt_prepare($stmt, $sql_give_comment)){
					header("location:index.php?error=*sqlerror");
					exit();
				}else {
					mysqli_stmt_bind_param($stmt,"ssss",$recipe_id, $commentor_id, $text, $date);
					mysqli_stmt_execute($stmt);
				}
			mysqli_stmt_close($stmt);

			$query_update_comments_num = "UPDATE foodie_recipes_explore SET comments_num = comments_num + 1 WHERE recipe_id = $recipe_id";
			$result = mysqli_query($con, $query_update_comments_num) or die("Bad Query: ".$query_update_comments_num);

			mysqli_close($con);
			echo "success";
		} else {
			echo "noId";
		}
		exit();
	}

	// for showing more and less related posts
	if(isset($_POST['related_posts_count'])) {
		session_start();
		$tags = $_SESSION['recipe_tags'];
		$recipe_heading = $_SESSION['recipe_heading'];
		$recipe_heading = str_replace(",", "", $recipe_heading);
		$recipe_heading = str_replace("-", "", $recipe_heading);
		$recipe_heading = str_replace(":", "", $recipe_heading);

		$headings = explode(" ", $recipe_heading, 5);

		$related_posts_count = $_POST['related_posts_count'];
		$recipe_id = isset($_POST['recipe_id']) ? $_POST['recipe_id'] : 0;
		if (is_null($recipe_id) || !is_numeric($recipe_id) || ($recipe_id < 1)) $recipe_id = 0;
		// $tags = explode(" ", str_replace(",", "", $row_recipe['recipe_tags']));
		// echo sizeof($tags);
		$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection Error ".mysqli_connect_error());
		$sql_related_post = "SELECT recipe_id, recipe_img_1, recipe_heading, comments_num, kudos_num, recipe_date FROM foodie_recipes_explore WHERE recipe_id != ".$recipe_id;
		// $sql_related_post_and = $sql_related_post_or = $sql_related_post;
		// $sql_related_post_or .= " AND (";
		// foreach ($tags as $key => $value) {
		// 	if(count($tags)-1 != $key) $add = " OR ";
		// 	else $add = " )";
		// 	$sql_related_post_and .= " AND recipe_tags LIKE '%".$value."%'";
		// 	$sql_related_post_or .= "recipe_tags LIKE '%".$value."%'".$add;
		// }

		// echo $sql_related_post_or;
		// $sql_related_post = $sql_related_post_and." UNION ".$sql_related_post_or;
		// echo $sql_related_post;

		$sql_related_post .= "AND (";
		foreach ($headings as $key => $value) {
			if(count($headings)-1 != $key) $add = " OR ";
			else $add = " )";
			$sql_related_post .= "recipe_tags LIKE '%".$value."%'".$add;
		}
		$result_related_post = mysqli_query($con, $sql_related_post." LIMIT ".$related_posts_count.", 2") or die("Bad Query: ".$sql_related_post);
		while($row_related_post = mysqli_fetch_assoc($result_related_post)) {
			$related_recipe_id = $row_related_post['recipe_id'];
			$related_image = $row_related_post['recipe_img_1'];
			$related_recipe_heading = $row_related_post['recipe_heading'];
			$related_comments_num = $row_related_post['comments_num'];
			$related_kudos_num = $row_related_post['kudos_num'];
			$related_recipe_date = date("F j, Y", strtotime($row_related_post['recipe_date']));

			echo '
			<div class="recipe-card related-post p-3 card row">
			<div class="d-inline-flex" post-id="'.$related_recipe_id.'">
				<div class="col-6 p-0">
					<img class="img-fluid" src="img/'.$related_image.'">
				</div>
				<div class="pl-2">
					<div class="heading font-weight-bold">'.$related_recipe_heading.'</div>

					<div>By <span class="owner">Swasthi -</span><span class="post-date"> '.$related_recipe_date.' </span></div>
					<div>🧡<span class="kudos">'.$related_kudos_num.'</span> 🧾<span>'.$related_comments_num.'</span></div>
				</div>
			</div>
			</div>
			';
		}
		mysqli_close($con);
		exit();
	}

	// for showing more and less recent posts
	if (isset($_POST['recent_posts_count'])) {
		$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection Error ".mysqli_connect_error());
		$recent_posts_count = $_POST['recent_posts_count'];

		$recipe_id = isset($_POST['recipe_id']) ? $_POST['recipe_id'] : 0;
		if (is_null($recipe_id) || !is_numeric($recipe_id) || ($recipe_id < 1)) $recipe_id = 0;

		if (isset($_POST['home_page'])) {
			$items_num = 4;
		} else {
			$items_num = 2;
		}

		$sql_recent_post = "SELECT recipe_id, recipe_img_1, recipe_heading, comments_num, kudos_num, recipe_date, username as owner FROM foodie_recipes_explore as f, foodie_users as u WHERE recipe_id != ".$recipe_id." AND u.user_id = f.owner_id ORDER BY f.recipe_date DESC LIMIT ".$recent_posts_count.", ".$items_num;
		// echo $sql_recent_post;
		$result_recent_post = mysqli_query($con, $sql_recent_post) or die("Bad Query: ".$sql_recent_post);
		while($row_recent_post = mysqli_fetch_assoc($result_recent_post)) {
			$recent_recipe_id = $row_recent_post['recipe_id'];
			$recent_image = $row_recent_post['recipe_img_1'];
			$recent_recipe_heading = $row_recent_post['recipe_heading'];
			$recent_comments_num = $row_recent_post['comments_num'];
			$recent_kudos_num = $row_recent_post['kudos_num'];
			$recent_recipe_date = date("F j, Y", strtotime($row_recent_post['recipe_date']));
			$owner = $row_recent_post['owner'];

		if(isset($_POST['home_page'])) {
			echo '
			<div class="recipe-card recent-post section-post p-3 card col-md-3 m-0">
				<div post-id="'.$recent_recipe_id.'">
					<div class="foodie-post-image col-6 col-md-12 p-0">
						<img class="img-fluid" src="img/'.$recent_image.'">
					</div>
					<div class="pl-2 col-6 col-md-12">
			';
		} else {
			echo '
			<div class="recipe-card recent-post p-3 card row">
				<div class="d-inline-flex" post-id="'.$recent_recipe_id.'">
					<div class="col-6 p-0">
						<img class="img-fluid" src="img/'.$recent_image.'">
					</div>
					<div class="pl-2">
			';
		}
		echo '
						<div class="heading font-weight-bold">'.$recent_recipe_heading.'</div>

						<div>By <span class="owner">'.$owner.' -</span><span class="post-date"> '.$recent_recipe_date.' </span></div>
						<div>🧡<span class="kudos">'.$recent_kudos_num.'</span> 🧾<span>'.$recent_comments_num.'</span></div>
					</div>
				</div>
			</div>
			';
		}
		mysqli_close($con);
		exit();
	}


	// for showing more most viewed posts 
	if(isset($_POST['most_viewed_posts_count'])) {
		$most_viewed_posts_count = $_POST['most_viewed_posts_count'];
		$sql_recent_post = "SELECT recipe_id, recipe_img_1, recipe_heading, comments_num, kudos_num, recipe_date FROM foodie_recipes_explore as f ORDER BY f.kudos_num DESC LIMIT ".$most_viewed_posts_count.", 4";
			$con = mysqli_connect("localhost", "root", "", "foodie");
			$result_recent_post = mysqli_query($con, $sql_recent_post) or die("Bad Query: ".$sql_recent_post);
			while($row_recent_post = mysqli_fetch_assoc($result_recent_post)) {
				$recent_recipe_id = $row_recent_post['recipe_id'];
				$recent_image = $row_recent_post['recipe_img_1'];
				$recent_recipe_heading = $row_recent_post['recipe_heading'];
				$recent_comments_num = $row_recent_post['comments_num'];
				$recent_kudos_num = $row_recent_post['kudos_num'];
				$recent_recipe_date = date("F j, Y", strtotime($row_recent_post['recipe_date']));
			echo '
			<div class="recipe-card most-viewed-post section-post p-3 card col-md-3 m-0">
				<div post-id="'.$recent_recipe_id.'">
					<div class="foodie-post-image col-6 col-md-12 p-0">
						<img class="img-fluid" src="img/'.$recent_image.'">
					</div>
					<div class="pl-2 col-6 col-md-12">
						<div class="heading font-weight-bold">'.$recent_recipe_heading.'</div>

						<div>By <span class="owner">Swasthi -</span><span class="post-date"> '.$recent_recipe_date.' </span></div>
						<div>🧡<span class="kudos">'.$recent_kudos_num.'</span> 🧾<span>'.$recent_comments_num.'</span></div>
					</div>
				</div>
			</div>
			';
			}
		mysqli_close($con);
		exit();
	}

	// for signout option or destroying current session
	if(isset($_GET['signout'])){
		session_start();
		session_destroy();
		// echo "string";
		header("location:index.php?logout=success");
		exit();
	}

	//	for search option
	if(isset($_POST['searchhints'])){
		$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection error");
		$keywords = mysqli_real_escape_string($con, $_POST['keywords']);
		if(strlen($keywords) == 0 || strlen($keywords) > 100){
			echo '';
			exit();
		}
		$query_hints = mysqli_query($con, "SELECT recipe_heading FROM foodie_recipes_explore WHERE recipe_heading LIKE '%$keywords%' ORDER BY kudos_num LIMIT 10") or die("Bad query: ");
		while($row = mysqli_fetch_assoc($query_hints)){
			echo " <option value='".$row['recipe_heading']."'></option>";
		}
		mysqli_close($con);
		exit();

	}

	// for showing search results
	if(isset($_POST['show_search_result'])) {
		if (!isset($_POST['keywords']) || !isset($_POST['lower_limit']) || strlen($_POST['keywords']) < 5 || !is_numeric($_POST['lower_limit'])) {
			echo 'Stop messing around!!';
			exit();
		}
		$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection error");
		$keywords = mysqli_real_escape_string($con, $_POST['keywords']);
		$lower_limit = $_POST['lower_limit'];
		$keywords_list = explode(" ", str_replace(",", "", $keywords));
		// print_r($keywords_list);
		$sql_search = "SELECT recipe_id, recipe_img_1, recipe_heading, comments_num, kudos_num, recipe_date, username as owner FROM foodie_recipes_explore as f, foodie_users as u WHERE u.user_id = f.owner_id ";
		$sql_search1 = $sql_search2 = $sql_search3 = $sql_search;
		foreach ($keywords_list as $key => $value) {
			$sql_search1 .= "AND f.recipe_steps LIKE '%".$value."%'";
			$sql_search2 .= "AND f.recipe_ingredients LIKE '%".$value."%'";
			$sql_search3 .= "AND f.recipe_heading LIKE '%".$value."%'";
			// if (count($keywords_list)-1 != $key){
			//  $sql_search_result .= " AND ";
			// }

		}
		$sql_search4 = $sql_search1." UNION ".$sql_search2." UNION ".$sql_search3;
		// echo $sql_search_result;
		if ($lower_limit == 0) {
			$result_search_result_check_num = mysqli_query($con, $sql_search4." LIMIT ".$lower_limit.", 1000") or die("Bad Query: ".$sql_search3);
			$num_result = mysqli_num_rows($result_search_result_check_num);
			if ($num_result == 0) {
				echo "<p class='col-12 text-danger lead'>Sorry no related recipes found for '$keywords'!!</p>";
			} else{
				if ($num_result == 1) $num_sign = '';
				else $num_sign = 's';
				echo "<p class='col-12 text-success lead'>".$num_result." search result".$num_sign." found for '$keywords'</p>";
			}
		}
		$result_search_result = mysqli_query($con, $sql_search4." LIMIT ".$lower_limit.", 8") or die("Bad Query: ".$sql_search3);
		while($row_related_post = mysqli_fetch_assoc($result_search_result)) {
			$related_recipe_id = $row_related_post['recipe_id'];
			$related_image = $row_related_post['recipe_img_1'];
			$related_recipe_heading = $row_related_post['recipe_heading'];
			$related_comments_num = $row_related_post['comments_num'];
			$related_kudos_num = $row_related_post['kudos_num'];
			$related_recipe_date = date("F j, Y", strtotime($row_related_post['recipe_date']));
			$owner = $row_related_post['owner'];

			echo '
			<div class="recipe-card search-result section-post p-3 card col-md-3 m-0">
				<div post-id="'.$related_recipe_id.'">
					<div class="foodie-post-image col-6 col-md-12 p-0">
						<img class="img-fluid" src="img/'.$related_image.'">
					</div>
					<div class="pl-2 col-6 col-md-12">
					<div class="heading font-weight-bold">'.$related_recipe_heading.'</div>

					<div>By <span class="owner">'.$owner.' -</span><span class="post-date"> '.$related_recipe_date.' </span></div>
					<div>🧡<span class="kudos">'.$related_kudos_num.'</span> 🧾<span>'.$related_comments_num.'</span></div>
				</div>
			</div>
			</div>
			';
		}
		mysqli_close($con);
		exit();
	}

	if(isset($_POST['edit_profile'])){
		
	}

	//	for editing profile
	if(isset($_POST['submit_profile_edit'])) {
		session_start();
		if(!isset($_SESSION['foodieuserid'])){
			header("location: index.php");
			exit();
		}
		$userid = $_SESSION['foodieuserid'];
		$realname = $_POST['realname'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$country = $_POST['country'];
		$city = $_POST['city'];
		$bdate = $_POST['bdate'];
		$contactnum = $_POST['contactnum'];
		$pass = $_POST['pass'];
		$forminfo = "realname=".$realname."&username=".$username."&email=".$email."&country=".$country."&city=".$city."&bdate=".$bdate."&contactnum=".$contactnum;

		if(empty($realname) || empty($username) || empty($email) || empty($country) || empty($city) || empty($bdate) || empty($contactnum)|| empty($pass) ){
			header("location:editprofile.php?editerr=*fill all fields&".$forminfo);
			exit();
		} else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $realname) || !preg_match("/^[a-zA-Z0-9_ ]*$/", $username) || !preg_match("/^[a-zA-Z0-9]*$/", $country) || !preg_match("/^[a-zA-Z0-9]*$/", $city) || !preg_match("/^[a-zA-Z0-9+ ]*$/", $contactnum)) {
			header("location:editprofile.php?editerr=*invalid characters somewhere&".$forminfo);
			exit();
		} else if(strlen($username)<5){
			header("location:editprofile.php?editerr=*username must be of atleast 5 characters&".$forminfo);
			exit();
		}

		$con = mysqli_connect("localhost", "root", "", "foodie");
		$sql_check_pwd = "SELECT user_pwd FROM foodie_users WHERE user_id = ".$userid;
		$result = mysqli_query($con, $sql_check_pwd);
		if($row = mysqli_fetch_assoc($result)){
			$pwdCheck = password_verify("foodie".$pass."foodie", $row['user_pwd']);
			if($pwdCheck == false){
				header("location:editprofile.php?editerr=*Incorrect Password&".$forminfo);
				exit();
			} else if($pwdCheck == true){
				$sql_edit = "UPDATE foodie_users SET real_name=?, username=?, user_email=?, user_country=?, user_city=?, user_bdate=?, user_contact=? WHERE user_id = ?";
				$stmt = mysqli_stmt_init($con);
				if(!mysqli_stmt_prepare($stmt, $sql_edit)){
					header("location:editprofile.php?editerr=*sqlerror");
					exit();
				}else {
					mysqli_stmt_bind_param($stmt,"ssssssss",$realname, $username,$email, $country, $city, $bdate, $contactnum, $userid);
					mysqli_stmt_execute($stmt);
					
					mysqli_close($con);
					header("location:profile.php?editac=success");
					exit();
				}
			} else {
				mysqli_close($con);
				session_destroy();
				header("location: index.php");
				exit();
			}

		}
	}

	//	for changing password
	if (isset($_POST['submit_pwd_change'])) {
		session_start();
		if (!isset($_SESSION['foodieuserid']) || !isset($_POST['pass']) || !isset($_POST['newpass']) || !isset($_POST['confirmpass'])) {
			session_destroy();
			header("location: index.php");
			exit();
		}

		$userid = $_SESSION['foodieuserid'];
		$con = mysqli_connect("localhost", "root", "", "foodie");
		$pass = mysqli_real_escape_string($_POST['pass']);
		$newpass = mysqli_real_escape_string($_POST['newpass']);
		$confirmpass = mysqli_real_escape_string($_POST['confirmpass']);

		if (strlen($pass) < 8 || strlen($newpass < 8) || $newpass != $confirmpass) {
			header("location: index.php");
			mysqli_close($con);
			exit();
		}

		$query_currentpwd = mysqli_query($con, "SELECT user_pwd FROM foodie_users WHERE user_id = ".$userid ) or die("Bad Query");
		if($row = mysqli_fetch_assoc($query_currentpwd)){
			$pwdCheck = password_verify("foodie".$newpass."foodie", $row['user_pwd']);
			if($pwdCheck == false){
				header("location: changepwd.php?err=*incorrect password");
				exit();
			}else if($pwdCheck == true){

				$sql = "UPDATE foodie_users SET user_pwd = ? WHERE user_id = ".$userid;
				$stmt = mysqli_stmt_init($con);
				if(!mysqli_stmt_prepare($stmt, $sql)){
					header("location: changepwd.php?err=*something went wrong. Try again later");
					exit();
				}else {
					$encryptpas = "foodie".$newpass."foodie";
					$hashedPwd = password_hash($encryptpas, PASSWORD_DEFAULT);//using bcrypt hashing method
					mysqli_stmt_bind_param($stmt,"s",$hashedPwd);
					mysqli_stmt_execute($stmt);
				}
			}
		}
		mysqli_close($con);
		header("location: changepwd.php?action=success");
		exit();
	}

	if(isset($_POST['delete_recipe'])){
		session_start();
		if(!isset($_SESSION['foodieuserid']) || !isset($_POST['recipe_id']) || !is_numeric($_POST['recipe_id'])) {
			session_destroy();
			header("location: index.php");
			exit();
		}
		$userid = $_SESSION['foodieuserid'];
		$recipe_id = $_POST['recipe_id'];
		$con = mysqli_connect("localhost", "root", "", "foodie");
		$sql_delete_recipe = "DELETE FROM foodie_recipes_explore WHERE recipe_id = ".$recipe_id." AND owner_id = ".$userid;
		$result_delete_recipe = mysqli_query($con, $sql_delete_recipe) or die("Bad Query");
		if($result_delete_recipe){
			echo "success";
		} else {
			echo "failed";
		}
		mysqli_close($con);
		exit();

	}
?>