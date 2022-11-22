<?php

	include('db/connection.php');
	if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
	$id = $_GET['id'];
	$delete_user = mysqli_query($conn,"UPDATE user SET is_deleted = 0 WHERE id = '$id';");
	if($delete_user){
		header("Location: active_users.php");
	}

?>