# README #

### Missing ###
* metaphone
* multiple image
* edit/delete comment
---
### Problems ###
* comment count number is JS based	# possible soln: use of session or increment in server-side
* Show less comment option is server based
* ?loginerror=* shows signup form for account holder
---
## ToDo ###
1. database_design ✔
2. signup_login_page ✔
3. recipe_entry_page ✔
4. recipe_display_page:
	* comments_kudos_entry_display ✔
	* related_recent_pages_card_display ✔
	* direct_recipe_search_design ✔
5. home_page ✔
6. Account page:
	* Edit section ✔
7. search_result_page ✔
8. advance_search_page
9. top_recipes_page ❕❓
---
## Variables ###
   variable		=>	acquired as
   
1. userid		=>	$_SESSION['foodieuserid']
2. recipe_id	=>	$_GET['rid']