//	===== for signup form =====
function show_SignUp_Form (){					// needs to def. outside to match with scripts written by php
	$('#signform-div').removeClass('d-none');
	$('#inputEmail').focus();
	$('#navbarFoodie').removeClass('show'); // hides navbar-collapse for smaller screens
}

function kudos_given_check() {
	recipe_id = $("#recipe").attr('recipe_id');
	$(".kudos_given_icon").load("foodie-execute.php", {kudos_given_check: 1, recipe_id: recipe_id});
}

// $(document).on('ready', function(){		// note: short versions doesn't work for signup toggle
// $(document).(function(){
$(function(){

	$("#loginbtn").click(function() {
		if($('#signform-div').hasClass('d-none')){
			show_SignUp_Form();
		} else {
			$('#signform-div').addClass('d-none');
		}
	});

	$("#signbg").click(function() {
		if(!($('#navbarFoodie').hasClass('show'))){		// checks navbar-collapse for smaller screens
			$('#signform-div').addClass('d-none');
		} else {
			$('#navbarFoodie').removeClass('show');
		}
	});
	//	--- script for SignUp form toggle ends ---

	//	--- script for navbar-collapse don't-show ---
	$('#content').click(function() {
		$('#navbarFoodie').removeClass('show');
	});
	//	--- script for navbar-collapse don't-show ends ---

	//	--- for signout button ---
	$("#foodie_signout").click(function(){
		// $(document).load("foodie-execute.php", {signout: 1});
		// alert("clicked");
		window.location.href = "foodie-execute.php?signout=1";

	});
	//	--- for signout button ends ---
	//	===== for signup form ends =====

	//	----- for search option -----

	$("body").delegate("#search-recipe-direct","keypress change",function(event){
		// event.preventDefault();
		keywords = $('#search-recipe-direct').val();
		if(event.which==13) {
			event.preventDefault();		//	stop submission
			if(keywords.replace(/\s/g, "").length > 4) {
				// alert("return pressed");
				window.location.href = "search.php?q="+keywords;				
			} else {
				alert("Too less characters");
			}
		} 
		// alert(event.type+event.which);
		if(keywords.length != 0){	//	to show search hints
			$.ajax({
				url : "foodie-execute.php",
				method : "POST",
				data : {searchhints:1, keywords: keywords},
				success : function(data){
						$("#search-hints").html(data);
				}
			});
		} else {
			$("#search-hints").empty();		// clear all hints when input is empty
		}
	});

	//	----- for comment section -----
	//	--- for comment-list ---
	kudos_given_check();

	$(".post-kudos").click(function(){
		recipe_id = $("#recipe").attr('recipe_id');
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {post_kudos: 1, recipe_id: recipe_id},
			success: function(data){
				if(data=="noId") {
					// alert('Please login first');
					$(".post-kudos").parent().after('<p class="text-danger" role="button" onclick="$(this).remove()">Please login first</p>');
					setTimeout( function(){
						$("#loginbtn").click();
					}, 500);
					return;
				} else if (data == "given") {
					$(".kudos-num").text(parseInt($(".kudos-num").text()) + 1);
					$(".kudos_given_icon").text("💙");
				} else if (data == "taken") {
					$(".kudos-num").text(parseInt($(".kudos-num").text()) - 1);
					$(".kudos_given_icon").text("🤍");
				} else {
					alert(data);
				}

			}
		})
	})

	var comments_count = 2;
	var recipe_id = $("#recipe").attr("recipe_id");

	$("#show-more-comments").click(function() {
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {comments_count: comments_count, recipe_id: recipe_id, more: 1},
			success: function(data){
				let current_text = $(".comments-list").html();
				if(data != ""){				//	not working
					$(".comments-list").append(data);
					comments_count+=2;
				} else {
					$("#show-more-comments").hide();
				}
				if(comments_count > 2) $("#show-less-comments").removeClass('d-none');
				else $("#show-less-comments").addClass('d-none');	// optional: to tackle messing with button
			}
		});
	});

	$("#show-less-comments").click(function(){
		comments_count-=2;
		$(".comments-list").load("foodie-execute.php", {
			comments_count: comments_count,
			recipe_id: recipe_id
		});
		if(comments_count<=2) {
			$(this).addClass('d-none');
		}
		$("#show-more-comments").show();	//	to show after hiding
	})
	//	--- for comment-list ends ---

	//	--- for comment-box and give-comment button ---
	if (comment_text = sessionStorage.getItem("comment")) {
		$("#comment-box").val(comment_text);
	}

	$("#give-comment").on("click",function(event){
		text = $("#comment-box").val();
		recipe_id = $("#recipe").attr('recipe_id');
		sessionStorage.setItem("comment", text);	//	for after-login state
		if(text!=""){
			$.ajax({
				url : "foodie-execute.php",
				method : "POST",
				data : {give_comment:1,text:text, recipe_id:recipe_id},
				success : function(data){
						if (data == "success") {
							$("#comment_state_msg").html('<p class="text-success d-inline">Your comment is posted</p>');
							$("#comment-box").val("");
							$(".comments-num").text(parseInt($(".comments-num").text())+1);
							sessionStorage.removeItem("comment");

						} else if (data == "noId") {
							$("#comment_state_msg").html('<p class="text-danger d-inline">Please login first</p>');
							$("#loginbtn").click();
							return;
						} 
						else {
							$("#comment_state_msg").html('<p class="text-danger d-inline">Something went wrong</p>');
						}
						$(".comments-list").load("foodie-execute.php", {
							comments_count: comments_count,
							recipe_id: recipe_id
						});

				}
			});

		} else {
			$("#comment_state_msg").html('<p class="text-danger d-inline">First enter something to comment!!</p>');
			$("#comment-box").focus();
			// $('html, body').animate({scrollTop: $("#comment-box").offset().top}, 500);
		}

	});

	$("#comment_state_msg").click(function() {
		$(this).empty();
	});
	//	--- for comment-box and give-comment button ends ---
	//	------ for comment section ends -----



	//	----- for aside -----
	//	--- for related posts ---
	var related_posts_count = 0;
	$("#show-more-related-posts").click(function(){
		recipe_id = $("#recipe").attr('recipe_id');
		// $(".related-posts-list").load("foodie-execute.php", {
		// 	related_posts_count: related_posts_count,
		// 	recipe_id: recipe_id
		// });
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {related_posts_count: related_posts_count, recipe_id: recipe_id},
			success: function(data){
				$(".related-posts-list").append(data);
				related_posts_count+=2;
			}
		});
	}
	);

	// var recent_posts_count = 4;		//	in page
	$("#show-more-recent-posts").click(function(){
		recipe_id = $("#recipe").attr('recipe_id');
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {recent_posts_count: recent_posts_count, recipe_id: recipe_id},
			success: function(data){
				$(".recent-posts-list").append(data);
				recent_posts_count+=2;
			}
		});
	}
	);

	//	--- for home page ---
	$("#show-more-recent-posts-home").click(function(){
		// $(".recent-posts-list").load("foodie-execute.php", {
		// 	recent_posts_count: recent_posts_count,
		// 	home_page: 1
		// });
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {recent_posts_count: recent_posts_count, home_page: 1},
			success: function(data){
				$(".recent-posts-list").append(data);
				recent_posts_count+=4;
			}
		});
	}
	);

	// var most_viewed_posts_count = 0;

	$("#show-more-most-viewed-posts-home").click(function(){
		$.ajax({
			url: "foodie-execute.php",
			method: "POST",
			data: {most_viewed_posts_count: most_viewed_posts_count, home_page: 1},
			success: function(data){
				$(".most-viewed-posts-list").append(data);
			}
		});
		most_viewed_posts_count+=4;
	});

	$("body").delegate(".recipe-card .heading, .recipe-card img", "click", function(){		//	use of delegate to use loading/timing problem - deprecated function
		$redirect_id = $(this).parent().parent().attr("post-id");
		// alert($redirect_id);
		window.location.href = "recipe.php?rid=" + $redirect_id;
	});

	$(".edit-profile").click(function(){
		// alert("clicked");
		window.location.href = "editprofile.php";
	});

	$(".change-pwd").click(function(){
		window.location.href = "changepwd.php";
	});

	$(".edit-recipe").click(function(){
		let recipe_id = $(this).parent().parent().attr("post-id");
		// window.location.href = "editrecipe.php?erid=";
		let form = $("<form action='editrecipe.php' method='POST'><input type='hidden' name='erid' value='"+recipe_id+"'></form>");
		$('body').append(form);
		form.submit();
	});

	$(".delete-recipe").click(function(){
		let confirm_delete = confirm("Do you really want to delete this recipe?");
		if (confirm_delete) {
			let selector = $(this);
			let recipe_id = selector.parent().attr("post-id");
			$.ajax({
				url: "foodie-execute.php",
				method: "POST",
				data: {delete_recipe: 1, recipe_id: recipe_id},
				success: function(data){
					if (data == "success"){
						selector.parent().parent().remove();
					} else {
						// alert("Something went wrong. Try again later");
						alert(data);
					}
				}
			});
		}
	});

	$(".post-recipe").click(function(){
		window.location.href = "insertRecipe.php";
	});

});