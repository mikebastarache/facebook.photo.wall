<?php 
// load up your config file  
require_once("config.php");

unset($_SESSION['token']);
unset($_SESSION['oauth_id']);
// set the expiration date to one hour ago
setcookie ("oauth_id", "", time() - 3600);
session_destroy(); 
header("Location: index.php");
?>

