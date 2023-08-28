<?php

if(isset($_POST["postRecipe"])){
	session_start();
	if(!isset($_SESSION['foodieuserid']) || !isset($_SESSION['erid'])) {
		header("location: index.php");
		exit();
	}
	$userid = $_SESSION['foodieuserid'];
	$recipe_id = $_SESSION['erid'];
	unset($_SESSION['erid']);

	$con = mysqli_connect('localhost', 'root', '', 'foodie') or die("Connection failed: ".mysqli_connect_error());

	if($_POST['imageedit'] == 'true') {
		$recipe_images_count = count($_FILES['recipe_images']['name']);
		$recipe_images_list = array();
		for ($i=0; $i < 3; $i++) { 
			array_push($recipe_images_list, null);
		 }
		if($_FILES['recipe_images']['name'][0]!=''){
			for($i=0; $i<$recipe_images_count; $i++){

				$fileName = $_FILES['recipe_images']['name'][$i];
				$fileTmpName = $_FILES['recipe_images']['tmp_name'][$i];
				$fileSize = $_FILES['recipe_images']['size'][$i];
				$fileError = $_FILES['recipe_images']['error'][$i];
				$fileType = $_FILES['recipe_images']['type'][$i];
				
				$fileExt = explode('.', $fileName);
				$fileNameOnly = $fileExt[0];
				$fileActualExt = strtolower(end($fileExt));

				$allowed = array('jpg', 'jpeg', 'png', 'bmp' ,'jfif','svg');

				if (in_array($fileActualExt, $allowed)) {
					if ($fileError === 0) {
						if ($fileSize < 1000000) {
							$fileNameNew = $fileNameOnly.uniqid('', true).".".$fileActualExt;
							$fileDestination = 'img/'.$fileNameNew;
							move_uploaded_file($fileTmpName, $fileDestination);
							//header("location:index.php?upload=success");
							$recipe_images_list[$i] = $fileNameNew;

						} else {
							echo "Your file is too big";
							echo $fileSize;
							exit();
						}
					} else {
						echo "There was an error uploading the file";
						exit();	
					}
				} else {
					echo "You cannot upload files of ".$fileActualExt." extension type";
					exit();
				}
			}
		}
	} else {
		$sql_get_image = "SELECT recipe_img_1, recipe_img_2, recipe_img_3 FROM foodie_recipes_explore WHERE owner_id = $userid AND recipe_id = $recipe_id";
		$result_get_image = mysqli_query($con, $sql_get_image) or die("Bad Query: ".$sql_get_image);
		$images = mysqli_fetch_assoc($result_get_image);
		$recipe_images_list[0] = $images['recipe_img_1'];
		$recipe_images_list[1] = $images['recipe_img_2'];
		$recipe_images_list[2] = $images['recipe_img_3'];
	}


	$recipe_heading = $recipe_ingredients = $recipe_steps = $recipe_notes = $recipe_img_1 = $recipe_img_2 = $recipe_img_3 = 'unknown';

	$recipe_heading = $_POST['recipe_heading'];
	$ingredients_count = $_POST['ingredients_count'];
	$recipe_steps_count = $_POST['recipe_steps_count'];
	$recipe_tags = $_POST['recipe_tags'];
	$recipe_notes = $_POST['recipe_notes'];

	$ingredients_list = [];
	$ingredients_count_limit = $ingredients_count; 
	$key = 1;	//	limit might be different than count if poster removes an input from middle //	ingredients number starts from 1
	for ($i=1; $i <= $ingredients_count_limit; $i++) { 
		if(isset($_POST['ingredient_'.$i])){
			$ingredients_list[$key] = $_POST['ingredient_'.$i];
			$key++;
		} else {
			$ingredients_count_limit++;
			if($ingredients_count_limit>500) break; //to prevent overload/maximum execution time
		}
		if ($key > 500) break;
 	}

	/////***********testing***********//////
	// $i = 1;
	// while (1) {
	// 	if($i > $ingredients_count_limit){
	// 		break;
	// 	}
	// 	elseif(isset($_POST['ingredient_'.$i])){
	// 		$ingredients_list[$i] = $_POST['ingredient_'.$i];
	// 	}
	// 	else{
	// 		$ingredients_count_limit++;
	// 	}
	// 	$i++;
	// }

	$recipe_steps_list = [];
	$recipe_steps_count_limit = $recipe_steps_count;
	$key = 1;
	for ($i=1; $i <= $recipe_steps_count_limit; $i++) { 
		if(isset($_POST['recipe_step_'.$i])){
			$recipe_steps_list[$key] = $_POST['recipe_step_'.$i];
			$key++;
		}
		else{
			$recipe_steps_count_limit++;
			if($recipe_steps_count_limit>500) break;
		}
		if ($key > 500) break;
	}

	date_default_timezone_set('Asia/Kathmandu');
	// 	$time = mktime();
	$postdate = date('Y-m-d H:i:s');
	$json_ingredients = json_encode($ingredients_list, true);
	$json_steps = json_encode($recipe_steps_list, true);

	$sqlUpdate = "UPDATE foodie_recipes_explore SET recipe_tags=?, recipe_heading=?, recipe_ingredients=?, recipe_steps=?, recipe_notes=?, recipe_img_1=?, recipe_img_2=?, recipe_img_3=? WHERE owner_id = $userid AND recipe_id = $recipe_id";

	$stmt = mysqli_stmt_init($con);
	if(!mysqli_stmt_prepare($stmt, $sqlUpdate)){
		header("location: recipe.php?rid=".$recipe_id);
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "ssssssss", $recipe_tags, $recipe_heading, $json_ingredients, $json_steps, $recipe_notes, $recipe_images_list[0], $recipe_images_list[1], $recipe_images_list[2]);
		if($report = mysqli_stmt_execute($stmt))
			header("location: recipe.php?rid=".$recipe_id);
		else
			header("location: myrecipes.php?redit=failed");
	}
	//$sql = " INSERT INTO products (p_name, cat_id, p_price) VALUES( 'ptest1', '11', '20000' )";
	// if($query = mysqli_query($con, $sqlUpdate)){
	// 	header("location: insertRecipe.php?recipe_id?rid=".$recipe_id);
	// }
	mysqli_close($con);
	exit();
}

?>