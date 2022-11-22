<?php

$url_post_images = 'http://g7technologies.com/crouch_end/postImages/';
$url_profile_images = 'http://g7technologies.com/crouch_end/profileImages/';

$servername = "localhost";
$db="mjpesbno_crouch_end";
$username = "mjpesbno_super";
$password = "v8turbO64672+";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

?>