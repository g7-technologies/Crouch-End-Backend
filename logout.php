<?php
include_once('db/connection.php');// mysqli_connect() function opens a new connection to the MySQL server. 
if(!isset($_SESSION['login_user'])){ 
    header("location: index.php"); // Redirecting To Home Page 
}
//session_start();


mysqli_close($conn);

session_destroy();

header('Location: index.php');


?>