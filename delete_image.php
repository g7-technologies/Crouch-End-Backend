<?php

	include('db/connection.php');
	if(!isset($_SESSION['login_user'])){ 
        header("location: index.php"); // Redirecting To Home Page 
    }
	$id = $_GET['id'];
	$post_id = $_GET['post_id'];
	$image_name = $_GET['name'];
	
	$activate_user = mysqli_query($conn,"DELETE FROM post_image WHERE id='$id'");
	if($activate_user){
	    unlink("postImages/".$image_name);
		header("Location: edit_post.php?id=".$post_id);
	}

?>