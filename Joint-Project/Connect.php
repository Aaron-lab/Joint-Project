<?php

//Aaron Morrissey c00239014

//References
//https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
//https://www.youtube.com/watch?v=LC9GaXkdxF8
//https://www.youtube.com/watch?v=aIsu9SPcGbU
//https://www.youtube.com/watch?v=aIsu9SPcGbU


$host = 'localhost';
$user = 'root';
$pass = 'beep';
$db = 'projecttest';

$conn = mysqli_connect($host, $user, $pass, $db);

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
