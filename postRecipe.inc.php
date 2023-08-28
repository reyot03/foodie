<?php

if(isset($_POST["postRecipe"])){
	session_start();
	if(!isset($_SESSION['foodieuserid'])) {
		header("location: index.php");
		exit();
	}
	$userid = $_SESSION['foodieuserid'];

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

			$allowed = array('jpg', 'jpeg', 'png', 'bmp' ,'jfif','svg', 'webp');

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


	$recipe_heading = $recipe_ingredients = $recipe_steps = $recipe_notes = $recipe_img_1 = $recipe_img_2 = $recipe_img_3 = 'unknown';

	$con = mysqli_connect('localhost', 'root', '', 'foodie') or die("Connection failed: ".mysqli_connect_error());

	$recipe_heading = mysqli_real_escape_string($con, $_POST['recipe_heading']);
	$ingredients_count = mysqli_real_escape_string($con, $_POST['ingredients_count']);
	$recipe_steps_count = mysqli_real_escape_string($con, $_POST['recipe_steps_count']);
	$recipe_tags = mysqli_real_escape_string($con, $_POST['recipe_tags']);
	$recipe_notes = mysqli_real_escape_string($con, $_POST['recipe_notes']);

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
	$json_ingredients = json_encode($ingredients_list);
	$json_steps = json_encode($recipe_steps_list);

	$sqlInsert = "INSERT INTO  foodie_recipes_explore (owner_id, recipe_tags,recipe_heading, recipe_ingredients, recipe_steps, recipe_notes, recipe_img_1, recipe_img_2, recipe_img_3, recipe_date) VALUES ('$userid','$recipe_tags','$recipe_heading', '$json_ingredients', '$json_steps', '$recipe_notes', '$recipe_images_list[0]', '$recipe_images_list[1]', '$recipe_images_list[2]', '$postdate') ";

	//$sql = " INSERT INTO products (p_name, cat_id, p_price) VALUES( 'ptest1', '11', '20000' )";
	if($query = mysqli_query($con, $sqlInsert)){
		header("location: insertRecipe.php?recipePostReport=success");
	}
	mysqli_close($con);
	exit();
}

?>