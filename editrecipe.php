<?php 
	require 'foodieHeader.php';
	session_start();
	if(!isset($_SESSION['foodieuserid']) || !isset($_POST['erid'])) {
		header("location: myrecipes.php");
	}
	$_SESSION['rid'] = null;
	$userid = $_SESSION['foodieuserid'];
	$recipe_id = $_POST['erid'];
	$_SESSION['erid'] = $_POST['erid'];

	$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection Error");
	$sql_get_recipe = "SELECT  * from foodie_recipes_explore WHERE recipe_id = $recipe_id AND owner_id = $userid";
	$result_get_recipe = mysqli_query($con, $sql_get_recipe) or die("Bad Query");
	$row = mysqli_fetch_assoc($result_get_recipe);
	$heading = $row['recipe_heading'];
	$ingredients = $row['recipe_ingredients'];
	$steps = $row['recipe_steps'];
	$image1 = $row['recipe_img_1'];
	$image2 = $row['recipe_img_2'];
	$image3 = $row['recipe_img_3'];
	$notes = $row['recipe_notes'];
	$tags = $row['recipe_tags'];

	$ingredients_list = json_decode($ingredients, true);
	$steps_list = json_decode($steps, true);
	// echo "Ingredients list: ".count($ingredients_list);
	// echo "<br>steps list: ".count($steps_list);
	// echo $notes;

?>
<link rel="stylesheet" type="text/css" href="css/fontawesome.css">
<link rel="stylesheet" type="text/css" href="css/all.min.css">
<style>


	form label {
		margin-bottom: 0;
		padding-bottom: 0;
	}

	form input, button {
		margin: 8px 0px;
	}

	input, textarea, #image_previews {
		box-shadow: 0px 0px 5px 0.25px #1742b8;
	}

	form > input:nth-child(2) {
		margin: 0px;
	}

	textarea#recipe_notes {
		height: 100px;
	}

	#image_previews {
		height: 155px;
		margin-bottom: 8px;
	}

	#image_previews img {
		height: 150px;
		width: 150px;
		padding-right: 5px;
		background-repeat: no-repeat;
		line-height: 150px;
	}

	@media (max-width: 544px) {
		#image_previews img {
			height: 100%;
			width: 30vw;
			line-height: 100%;
		}
		#image_previews {
			height: 30vw;
		}
	}

	#image_previews img:last-child {
		padding: 0;
	}

	.remove_ingredient, .remove_step {
		position: relative;
		margin-top: auto;
		margin-bottom: 0;
	}

	input[type=file] {
		/*padding-top: 0;*/
		height: 100%;
		line-height: 100%;
	}

	input[type=file] > #file-upload-button {
		opacity: 0;
		height: 100px;
	}
</style>
</head>
<body>
	<!-- TopNavbar -->
	<?php require 'foodienav.php'; ?>		
	<!-- /.TopNavbar -->
	<?php
		if (isset($_GET['recipePostReport'])){
			echo '<p class="text-success text-center" role="button" onclick="remove(this)">Recipe Added Successfully</p>';
		}
	?>
	<form id="updateRecipeForm" class="container col-md-8" action="editrecipe.inc.php" enctype="multipart/form-data" method="POST" onsubmit="return confirm('Post It?')">
		<label for="insertRecipeForm" class="text-center"><h1>Edit Recipe Details</h1></label>
		<label for="recipe_heading">Recipe Heading</label>
		<?php
		printf('
		<input id="recipe_heading" class="col-sm-12" type="text" name="recipe_heading" placeholder="Recipe Heading" value= "%s" autofocus required>
		', $heading);
		?>
		<label for="recipe_ingredients">Recipe Ingredients</label>

		<?php
		// for ($i=1; $i <= count($ingredients_list); $i++) { 
		// 	printf('
		// 	<input id="ingredient_%s" class="ingredients" type="text" name="ingredient_%s" placeholder="Example: 1 tablespoon of salt" value="%s" required>
		// 	', $i, $i, $ingredients_list[$i]);
		// }
		foreach ($ingredients_list as $key => $value) {
			printf('
			<input id="ingredient_%s" class="ingredients" type="text" name="ingredient_%s" placeholder="Example: 1 tablespoon of salt" value="%s" required>
			', $key, $key, $value);
		}
		?>
<!-- 		<input id="ingredient_2" class="ingredients" type="text" name="ingredient_2" placeholder="Example: 1 tablespoon of salt" required>
		<input id="ingredient_3" class="ingredients" type="text" name="ingredient_3" placeholder="Example: 1 tablespoon of salt" required> -->
		<button id="add_more_ingredients_btn" type="button">Add more Ingredients</button>
		<input id="ingredients_count" type="number" name="ingredients_count" class="sr-only" min="3" max="100">

		<label for="recipe_steps"><h5>Recipe Steps</h5></label>
		<?php
			foreach ($steps_list as $key => $value) { 
				printf('<input id="steps_%s" class="recipe_steps" type="text" name="recipe_step_%s" placeholder="Example: Add some water" value="%s" required>', $key, $key, $value);
			}
		?>
		
<!-- 		<input id="steps_2" class="recipe_steps" type="text" name="recipe_step_2" placeholder="Example: Turn on the heat" required>
		<input id="steps_3" class="recipe_steps" type="text" name="recipe_step_3" placeholder="Example: Boil for 30 minutes" required> -->
		<button id="add_more_steps_btn" type="button">Add more Steps</button>
		<input id="recipe_steps_count" type="number" name="recipe_steps_count" class="sr-only" min="3" max="100">

		<input type='file' accept='image/*' name='recipe_images[]' class='recipe_images' id='recipe_images' placeholder='address of image' multiple>
		<div class="badge" id="image_previews" max="3"><!--  <img id='preview' src='' alt='No Image Selected Yet'> -->
			<?php
				$images = [$image1, $image2, $image3];
				foreach ($images as $image) {
					if($image != null && $image != ""){
						echo "<img id='preview' src='img/$image' alt='No Image Selected Yet'>";
					}
				}
			?>
		</div>
		<input type="hidden" id="imageedit" name="imageedit" value="false">
		<?php
		printf('
		<input id="recipe_tags" type="text" name="recipe_tags" placeholder="Relevant tags for your recipe. For Example: curry, fried foods, desserts.." value="%s" required>
		<textarea id="recipe_notes" name="recipe_notes" placeholder="Write some notes on your recipe here..">%s</textarea>
		', $tags, $notes);
		?>

		<button type="submit" name="postRecipe" >Post It</button>
	</form>
<div id="time_div" class="text-right">
	<div></div>
	<div></div>
</div>
<script>
	$(function(){
		var date = new Date().toLocaleDateString('en-us', {weekday:'long', month:'long', day:'numeric', year:'numeric'});
		$('#time_div > div:first').text(date);
		var showtime = setInterval(function(){
			var time = new Date().toLocaleTimeString();
			$('#time_div > div').eq(1).text(time);
		},1000);
	})
</script>
</body>
<script>
	
	$('form label').addClass('col-form-label-lg form-text mt-2');
	$('button').addClass('btn bg-dark btn-outline-light ');
	if($(window).width()>720){
		$('button').removeClass('btn-outline-light');
		$('button').addClass('btn-outline-secondary');
	}
	$('input#file-upload-button').addClass('btn bg-dark btn-outline-secondary');
	$('input').addClass('form-control');
	$('textarea').addClass('form-control');

	var ingredients_count = <?php echo count($ingredients_list) ?>;	// increases and decreases with addition and removal
	var ingredient_key = ingredients_count;		// keeps increasing
	$('#ingredients_count').val(ingredients_count);
	function add_more_ingredients() {
		ingredients_count++;
		ingredient_key++;
		$('#ingredients_count').val(ingredients_count);
		var ingredients_field = '<div class="input-group"><input id="ingredient_'+ingredient_key+'" class="ingredients form-control col-10 col-md-11" type="text" name="ingredient_'+ingredient_key+'" placeholder="Example: 1 tablespoon of salt" required><div class="form-control col-2 col-md-1 btn btn-danger remove_ingredient fa fa-trash"></div></div>';
		$('#add_more_ingredients_btn').before(ingredients_field);

	}

	$("#add_more_ingredients_btn").click(function(){
		add_more_ingredients();
	});

	$("#add_more_steps_btn").click(function(){
		add_more_steps();
	});

	$("body").delegate(".remove_ingredient","click",function(event){
		event.preventDefault();
		$(this).parent().remove();
		ingredients_count--;
		$('#ingredients_count').val(ingredients_count);
	});

	var recipe_steps_count = <?php echo count($steps_list) ?>;
	var recipe_step_key = recipe_steps_count;
	$('#recipe_steps_count').val(recipe_steps_count);

	function add_more_steps() {
		recipe_steps_count++;	
		recipe_step_key++;			
		$('#recipe_steps_count').val(recipe_steps_count);
		var steps_field = '<div class="input-group"><input id="recipe_step_'+recipe_step_key+'" class="recipe_steps form-control" type="text" name="recipe_step_'+recipe_step_key+'" placeholder="More Steps"><div class="form-control col-2 col-md-1 btn btn-danger remove_step" fonclick="alert(\'clicked\')">X</div></div>';
		$('#add_more_steps_btn').before(steps_field);

	}

	$("form#InsertRecipeForm").find("input").keypress(function(event){
		if(event.which==13){
			// event.preventDefault();
			$(this).nextAll('INPUT, TEXTAREA').first().focus();
			return false;
		}
		// event.stopPropagation();
	});

	$("body").delegate(".remove_step","click",function(event){
		event.preventDefault();
		$(this).parent().remove();
		recipe_steps_count--;
		$('#recipe_steps_count').val(recipe_steps_count);
	});

	$(function(){
		// insert_blank_image();
	});

	function insert_blank_image(){
		$('#image_previews').html("<img id='preview' src='' alt='No Image Selected Yet'>");
		$('#imageedit').val("false");
	}

	$("body").delegate("#recipe_images","change",function(event){
		var image_count = $(this)[0].files.length;
		if(image_count==0){
			insert_blank_image();
			return;
		}
		$('#imageedit').val("true");
		if(image_count>3)
			alert("Sorry only 3 images allowed right now.");

		image_count = 3;
		$("#image_previews").empty();
		for(var i=0; i<image_count; i++){
			if(event.target.files[i]!=null){
				var new_preview = "<img id='preview_"+i+"' src='"+URL.createObjectURL(event.target.files[i])+"'>";
				$('#image_previews').append(new_preview);
			}

		}
	});

	function remove(selector){
		selector.remove();
		window.history.replaceState("", "", 'profile.php');
	}

	$(function() {
		$('.nav-link.Account').html('Account');
		$('.nav-link.Account').attr('id','accountbtn');
		$('.nav-link.Account').attr('data-toggle','dropdown')
	});

</script>

</html>