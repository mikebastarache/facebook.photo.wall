<?php
	// load up your config file  
    require_once("config.php");  
	
	// load up Facebook lib  
	require_once("lib/facebook/facebook.php");

	$facebook = new Facebook(array(
		'appId' => $GLOBALS['config']['fb']['app_id'],
		'secret' => $GLOBALS['config']['fb']['app_secret'],
		'cookie' => true,
	));
	
	$permissions = $facebook->api("/me/permissions");
	
	if (isset($_POST['pid'])) {  $pid = (get_magic_quotes_gpc()) ? $_POST['pid'] : addslashes($_POST['pid']); } else  { $pid = 0; }
	if (isset($_POST['status'])) {  $status = (get_magic_quotes_gpc()) ? $_POST['status'] : addslashes($_POST['status']); } else  { $status = ''; }

	if( array_key_exists('publish_stream', $permissions['data'][0]) ) {
		try {
			$location = "/" . $pid . "/comments";
			$post_id = $facebook->api($location, 'post', array('message'=> $status));
			
			$thumbnail = "http://graph.facebook.com/".$_SESSION["oauth_id"]."/picture";
			echo '<div class="commentPanel" align="left" style=" clear:all; background-color:#eceff5; margin-bottom:5px; padding:5px;">';
			echo '<img src="'.$thumbnail.'" width="40" height="40" border="0"  style="float:left; padding-right:5px;">';
			echo '<label class="postedComments" style="margin-left:43px">' . $status . '</label>';
			echo '<span style=" color:#666666; font-size:11px"></span></div>';
			
		} catch (Exception $e) {
			var_dump($e);	
		}
	}
	
?>