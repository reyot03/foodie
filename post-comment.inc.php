<?php

	function postComment() {
		if(isset($_POST['commentSubmit'])){
			if(isset($_POST['give_comment'])) {
				$con = mysqli_connect("localhost", "root", "", "foodie") or die("Connection Error ".mysqli_connect_error());
				$text = $_POST['text'];
				if(isset($_SESSION['foodieuserid'])){
					echo "foodieuserid: ".$_SESSION['foodieuserid'];
				} else {
					echo "no user id";
				}
				exit();
			}
			echo "clicked";
		}
	}