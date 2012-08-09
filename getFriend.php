<?php
	// load up your config file  
    require_once("config.php");  
	
	if (isset($_POST['id'])) { $id = (get_magic_quotes_gpc()) ? $_POST['id'] : addslashes($_POST['id']); } else { $id = 0; }
	if (isset($_POST['name'])) { $name = (get_magic_quotes_gpc()) ? $_POST['name'] : addslashes($_POST['name']); } else { $name = ''; }
	
	$_SESSION['current_photo_name'] = $name;
	$_SESSION['current_photo_oauth_id'] = $id;
	//echo $_SESSION['current_photo_oauth_id'] . " - " . $_SESSION['current_photo_name'] . " - complete";
?>