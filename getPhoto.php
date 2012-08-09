<style media="all" type="text/css">
html, body {
font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
font-weight:normal;
background-color:#fff;
}
td {font-size: 0.8em;}
.loading{
display:none;
width:50px;
height:50px;
background-image:url('img/spinner.gif');
float:right;
}

</style>
<br />
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
	
	if (isset($_GET['pid'])) { $pid = (get_magic_quotes_gpc()) ? $_GET['pid'] : addslashes($_GET['pid']); } else { $pid = 0; }
	
	try {
		$app_photo = $facebook->api(array('method' => 'fql.query','query' => 'SELECT object_id, src,src_big_width,src_big_height,caption,created,comment_info,like_info,pid,owner,src_small,src_big FROM photo WHERE  pid = "'.$pid . '"' ));
		
	} catch (FacebookApiException $e) {
        var_dump($e->getResult());
    }
	
	
	foreach ($app_photo as $value) { 
		if(sizeof($value) == 1){ 
			echo '<div align="center"><img src="' . $app_photo['src_big'] . '" width="' . $app_photo['src_big_width'] . '" height="' . $app_photo['src_big_height'] . '" border="0"><p>" ' . $app_photo['caption'] . ' "</p></div>';			
			$PHOTO_ID = $app_photo['object_id'];

		}
		echo '<div align="center"><img src="' . $value['src_big'] . '" width="' . $value['src_big_width'] . '" height="' . $value['src_big_height'] . '" border="0"><p>" ' . $value['caption'] . ' "</p></div>';
		$PHOTO_ID = $value['object_id'];
	}
	
	echo "<div style='padding:20px; text-align:left;'>";
	echo "<hr><h4>Comments</h4><div id='view_comments'>";

	$app_photo_comments = $facebook->api(array('method' => 'fql.query','query' => 'SELECT post_id, fromid, time, text, id FROM comment WHERE object_id IN (SELECT object_id FROM photo WHERE pid = "'.$pid.'")'));

	foreach ($app_photo_comments as $value) { 
		// If only one row of data is returned
		if(sizeof($value) == 1){ 
			$thumbnail = "http://graph.facebook.com/".$app_photo_comments['fromid']."/picture";
			echo '<div class="commentPanel" align="left" style=" clear:all; background-color:#eceff5; margin-bottom:5px; padding:5px;">';
			echo '<img src="'.$app_photo_comments.'" width="40" height="40" border="0"  style="float:left; padding-right:5px;">';
			echo '<label class="postedComments" style="margin-left:43px">' . $app_photo_comments['text'] . '</label>';
			echo '<span style=" color:#666666; font-size:11px">' . date("Y/m/d",$app_photo_comments['time']) . '</span></div>';			break;
		}
		
		$thumbnail = "http://graph.facebook.com/".$value['fromid']."/picture";
		echo '<div class="commentPanel" align="left" style=" clear:all; background-color:#eceff5; margin-bottom:5px; padding:5px;">';
		echo '<img src="'.$thumbnail.'" width="40" height="40" border="0"  style="float:left; padding-right:5px;">';
		echo '<label class="postedComments" style="margin-left:43px">' . $value['text'] . '</label>';
		echo '<span style=" color:#666666; font-size:11px">' . date("Y/m/d",$value['time']) . '</span></div>';
	}
	echo '</div>';
	echo '<div class="comment_ui" id="comment_ui"><div class="loading"></div><input type="text" maxlength="255" id="'. $PHOTO_ID .'" class="comment_box" style="font-size:11px;" value="Write a comment..." /></div>';

?>
</div>

<script type="text/javascript">
$(function(){  
	$(".comment_box").focus(function(){
	$(this).filter(function(){
	return $(this).val() == "" || $(this).val() == "Write a comment..."
	}).val("").css("color","#000000");
	});
	$(".comment_box").blur(function(){
	$(this).filter(function(){
	return $(this).val() == ""
	}).val("Write a comment...").css("color","#808080");
	});
	
	$(".comment_box").keypress(function(e) {
		var ID = $(this).attr("id");
		code= (e.keyCode ? e.keyCode : e.which);
		if (code == 13) {
			$(".loading").show();
			var status=$(this).val();
			if(status == "Write a comment..."){
				$(".loading").hide();
			}else{
				var DATA = 'status=' + status + '&pid=' + ID;
				$.ajax({
					type: "POST",
					url: "post_comment.php",
					data: DATA,
					cache: false,
					success: function(data){
						$(".loading").hide();
						$(".comment_box").val("Write a comment...").css("color","#808080").css("height","15px").blur();
						$("#view_comments").append(data);
					}
				});
			}
			return false;
		}
	});


});
</script>