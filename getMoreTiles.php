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
	
	if (isset($_POST['LastRecord'])) {  $LastRecord = (get_magic_quotes_gpc()) ? $_POST['LastRecord'] : addslashes($_POST['LastRecord']); } else  { $LastRecord = 0; }

	//$app_photos = $facebook->api(array('method' => 'fql.query','query' => 'SELECT modified, src,src_big_width,src_big_height,caption,created,comment_info,like_info,pid,owner,src_small,src_big FROM photo WHERE aid IN (SELECT aid FROM album WHERE owner = '.$_SESSION["current_photo_oauth_id"].') ORDER BY created DESC LIMIT 20 OFFSET ' . $LastRecord));
	
	$app_photos = $facebook->api(array('method' => 'fql.query','query' => 'SELECT owner, aid, modified, src,src_big_width,src_big_height,caption,created,comment_info,like_info,pid,owner,src_small,src_big FROM photo WHERE aid IN (SELECT aid FROM album WHERE owner IN (SELECT uid2 FROM friend WHERE uid1=me())) ORDER BY created DESC LIMIT 20 OFFSET ' . $LastRecord));
	$currentRow = $LastRecord;
	
	foreach ($app_photos as $value) { 
		$currentRow = $currentRow + 1;
		$newHeight = number_format($value['src_big_height'] / ($value['src_big_width'] / 200),0);
		$thumbnail = "http://graph.facebook.com/".$value['owner']."/picture";
		echo '<li class="gallery-item" id="' . $currentRow . '">';

		// If only one row of data is returned
		if(sizeof($value) == 1){ 
			$thumbnail = "http://graph.facebook.com/".$app_photos['owner']."/picture";
			$newHeight = number_format($app_photos['src_big_height'] / ($app_photos['src_big_width'] / 200),0);
			echo '<a data-href="getPhoto.php?pid=' . $app_photos['pid'] . '"><img src="' . $app_photos['src'] . '" width="200" height="' . $newHeight . '"></a>';
			echo '<img src="'.$thumbnail.'" border="0" width="30" height="30" style="float:left; padding:5px 5px 0px 0px;">';
			echo '<table><tr><td valign=top width=40><img src="'.$thumbnail.'" border="0" width="30" height="30" style="padding:5px"></td>';
			echo '<td valign=top style="width:160px; text-align:left;color: #666; font-size: 11px;">';
			echo json_decode(file_get_contents('http://graph.facebook.com/'.$app_photos['owner']))->name. "<br />";
			if($app_photos['caption'] != ''){
				echo  $app_photos['caption'] . '<br />';
			}
			echo date("Y/m/d",$app_photos['created']) . '</td></tr></table>';
			echo '</li>';
			break;
		}
		
		echo '<a data-href="getPhoto.php?pid=' . $value['pid'] . '"><img src="' . $value['src'] . '" width="200" height="' . $newHeight . '"></a>';
		echo '<table><tr><td valign=top width=40><img src="'.$thumbnail.'" border="0" width="30" height="30" style="padding:5px"></td>';
		echo '<td valign=top style="width:160px; text-align:left;color: #666; font-size: 11px;">';
		echo json_decode(file_get_contents('http://graph.facebook.com/'.$value['owner']))->name . "<br />";
		if($value['caption'] != ''){
			echo  $value['caption'] . '<br />';
		}
		echo date("Y/m/d",$value['created']) . '</td></tr></table>';
		echo '</li>';
	}
?>