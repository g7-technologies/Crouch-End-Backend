<?php

	include('db/connection.php');
	if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
	$id = $_GET['id'];
	$delete_role = mysqli_query($conn,"UPDATE role SET status = 0 WHERE id = '$id';");
	if($delete_role){
		header("Location: all_roles.php");
	}

?>