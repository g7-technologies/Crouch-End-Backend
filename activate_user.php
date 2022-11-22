<?php

	include('db/connection.php');
	
	if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
    
	$id = $_GET['id'];
	$activate_user = mysqli_query($conn,"UPDATE user SET status = 1 WHERE id = '$id';");
	if($activate_user){
		header("Location: active_users.php");
	}

?>