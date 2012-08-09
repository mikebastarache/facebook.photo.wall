<?php
// load up your config file  
require_once("config.php");

// load up your database object  
require_once('lib/crud.php'); 
$db = new Database();  
$db->connect();  

// load up Facebook lib  
require_once("lib/facebook/facebook.php");

$facebook = new Facebook(array(
	'appId' => $GLOBALS['config']['fb']['app_id'],
	'secret' => $GLOBALS['config']['fb']['app_secret'],
	'cookie' => true,
));

$uid = $facebook->getUser();

$_SESSION['token'] = NULL;

if ($uid) {
    # Active session, let's try getting the user id (getUser()) and user info (api->('/me'))
    try {
        $user = $facebook->api('/me');
		//get user basic description
    	$userInfo = $facebook->api("/$user");
    } catch (Exception $e) {}
	
    if (!empty($user)) {
		$_SESSION['token'] = $facebook->getAccessToken();
		$_SESSION['thumbnail'] = "http://graph.facebook.com/".$uid."/picture";
		$_SESSION['name'] = $user['name'];
		$_SESSION['oauth_id'] = $uid;
		$_SESSION['current_photo_name'] = $user['name'];
		$_SESSION['current_photo_oauth_id'] = $uid;
		setcookie("oauth_id", $uid, time() + (1 * 365 * 24 * 60 * 60));
		
		//Select all users
		$db->select('users','*','oauth_uid='.$uid);  
		$res = $db->getResult();
		
		if(!$res){
			$db->insert('users', array('facebook', $uid, $_SESSION['token']), 'oauth_provider, oauth_uid, access_token');  		
			$db->getResult(); 
		}
		
		header("Location: grid.php");
		
    } else {
		header("Location: error.php?msg=error");
    }
	
} else {
    # There's no active session, let's generate one
    $login_url = $facebook->getLoginUrl(array("scope" => "offline_access,publish_stream,user_photos,friends_photos"));  
    header("Location: " . $login_url);
}
?>
