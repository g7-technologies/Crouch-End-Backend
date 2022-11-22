<?php

	include('db/connection.php');
	
	if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
	
	$id = $_GET['id'];
	$activate_user = mysqli_query($conn,"UPDATE posts SET status = 1, is_deleted = 0 WHERE id = '$id';");
	//var_dump($activate_user);
	if($activate_user){
 		header("Location: active_posts.php");
	}

?>