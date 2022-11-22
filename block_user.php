<?php

	include('db/connection.php');
	if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
	$id = $_GET['id'];
	$block_user = mysqli_query($conn,"UPDATE user SET status = 0 WHERE id = '$id';");
	if($block_user){
		header("Location: blocked_users.php");
	}

?>