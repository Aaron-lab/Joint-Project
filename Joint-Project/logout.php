<?php
//Aaron Morrissey c00239014

//References
//https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
//https://www.youtube.com/watch?v=LC9GaXkdxF8
//https://www.youtube.com/watch?v=aIsu9SPcGbU
//https://www.youtube.com/watch?v=aIsu9SPcGbU

session_start();
$_SESSION = array();
session_destroy();
header("location: login.php");
exit;
?>
