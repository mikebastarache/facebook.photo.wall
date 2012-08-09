<?php
	// load up your config file  
    require_once("config.php");  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $GLOBALS['config']['params']['sitename']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Digital Magic">

    <!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/wall.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-image-gallery.min.css">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
		height:100%;
      }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-11.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72.png">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="#"><?php echo $GLOBALS['config']['params']['sitename']; ?></a>
           <ul class="nav" style="float:right;">
           	<li style="padding-top:6px;color:#FFF;"><?php echo '<img src="'.$_SESSION['thumbnail'].'" width="30" height="30" border="0" style="padding-right:5px;">';?><?php echo $_SESSION['name'];?></li>
            <li><a href="logout.php" class="nav_btn" style="color:#fff;">Log Out</a></li>
           </ul>
        </div>
      </div>
    </div>

    <div class="container">
     <ul class="nav nav-tabs">
        <li class="tab-btn active" id="TabPhoto"><a href="#pane1" data-toggle="tab" id="currentPhotoTab">Friends Recent Photos</a></li>
        <li class="tab-btn" id="TabFriends"><a href="#pane2" data-toggle="tab">Choose Another Photoset</a></li>
      </ul>
      
      <div id="myTabContent" class="tab-content">
      <div id="pane1" class="tab-pane active">
      
      <div id="main" role="main" data-toggle="modal-gallery" data-target="#modal-gallery" data-selector="li.gallery-item">      

	  <ul id="tiles">
      <!-- These are our grid blocks -->
      <?php
	  	// load up Facebook lib  
		require_once("lib/facebook/facebook.php");

		$facebook = new Facebook(array(
			'appId' => $GLOBALS['config']['fb']['app_id'],
			'secret' => $GLOBALS['config']['fb']['app_secret'],
			'cookie' => true,
		));

  		//$app_photos = $facebook->api(array('method' => 'fql.query','query' => 'SELECT modified, src,src_big_width,src_big_height,caption,created,comment_info,like_info,pid,owner,src_small,src_big FROM photo WHERE aid IN (SELECT aid FROM album WHERE owner = '.$_SESSION["current_photo_oauth_id"].') ORDER BY created DESC LIMIT 20 OFFSET 0'));
		
		$app_photos = $facebook->api(array('method' => 'fql.query','query' => 'SELECT owner, modified, src,src_big_width,src_big_height,caption,created,comment_info,like_info,pid,owner,src_small,src_big, aid FROM photo WHERE aid IN (SELECT aid FROM album WHERE owner IN (SELECT uid2 FROM friend WHERE uid1=me())) ORDER BY created DESC LIMIT 20 OFFSET 0'));
		$currentRow = 0;

		foreach ($app_photos as $value) { 
			$currentRow = $currentRow + 1;
			$thumbnail = "http://graph.facebook.com/".$value['owner']."/picture";
			$newHeight = number_format($value['src_big_height'] / ($value['src_big_width'] / 200),0);
			$app_photo_likes = "http://graph.facebook.com/".$value['aid']."/likes";
		
			echo '<li class="gallery-item" id="' . $currentRow . '">';
        	
			// If only one row of data is returned
			if(sizeof($value) == 1){ 
				$thumbnail = "http://graph.facebook.com/".$app_photos['owner']."/picture";
				$newHeight = number_format($app_photos['src_big_height'] / ($app_photos['src_big_width'] / 200),0);
				$app_photo_likes = "http://graph.facebook.com/".$app_photos['aid']."/likes";
				echo '<a data-href="getPhoto.php?pid=' . $app_photos['pid'] . '"><img src="' . $app_photos['src'] . '" width="200" height="' . $newHeight . '"></a>';
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
			echo '<a data-href="getPhoto.php?pid=' . $value['pid'] . '" data-toggle="modal"><img src="' . $value['src'] . '" width="200" height="' . $newHeight . '"></a>';
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
      	<!-- End of grid blocks -->
      	</ul>
	  </div>  
      
      <!-- LOADING -->
      <div id="loading" style="text-align:center; width:100%; position:inherit; clear:both;"><img src="img/loadingAnimation.gif" width="208" height="13" border="0"></div>
  	  
	  </div>     
      
      <div id="pane2" class="tab-pane">
        <h3>Photosets</h3>
		<?php
		echo '<div class="friendPanel" id="'.$_SESSION['oauth_id'].'" name="' . $_SESSION['name']  . '" align="left" style="cursor:pointer; clear:all; background-color:#eceff5; margin:5px; padding:5px; display:inline; float:left; width:175px;">';
		echo '<img src="'.$_SESSION['thumbnail'] .'" border="0"  style="float:left; padding-right:5px;">';
		echo '<label class="postedComments" style="cursor:pointer; margin-left:43px">' . $_SESSION['name'] . '</label></div>';

		$friends = $facebook->api('/me/friends');
		foreach ($friends as $value) {
			foreach ($value as $v) { 
				
				$thumbnail = "http://graph.facebook.com/".$v['id']."/picture";
				echo '<div class="friendPanel" id="'.$v['id'].'" name="' . $v['name'] . '" align="left" style="cursor:pointer; clear:all; background-color:#eceff5; margin:5px; padding:5px; display:inline; float:left; width:175px;">';
				echo '<img src="'.$thumbnail.'" border="0"  style="float:left; padding-right:5px;">';
				echo '<label class="postedComments" style="cursor:pointer; margin-left:43px">' . $v['name'] . '</label></div>';
			}
		}
		
		?>
      </div>
      
	  </div>
      
      <hr>

      <footer>
        <p>&copy; <?php echo $GLOBALS['config']['params']['sitename']; ?> 2012</p>
      </footer>

    </div> <!-- /container -->
    
    <!-- this is the placeholder for the modal box -->
    <div id="modal-photo" class="modal hide">
        <!-- content will go here -->
    </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/jquery.imagesloaded.js"></script>
	<script src="js/load-image.min.js"></script>
    <script src="js/jquery.wookmark.min.js"></script>
    
    <!-- Once the images are loaded, initalize the Wookmark plug-in. -->
    <script type="text/javascript">
	 $(function () {
		  $('.tabs a:last').tab('show');
		  $("#loading").hide();
	  })
	$('#tiles').imagesLoaded(function() {
      // Prepare layout options.
      var options = {
        autoResize: true, // This will auto-update the layout when the browser window is resized.
        container: $('#main'), // Optional, used for some extra CSS styling
        offset: 20, // Optional, the distance between grid items
        itemWidth: 210 // Optional, the width of a grid item
      };
      
      // Get a reference to your grid items.
      var handler = $('#tiles li');
      
      // Call the layout function.
      handler.wookmark(options);
      
      // Capture clicks on grid items.
      handler.click(function(){
      });
	  
	  $(".friendPanel").click(function(){
		var FriendID = $(this).attr("id");
		var FriendName = $(this).attr("name");
		var DATA = 'id=' + FriendID + '&name=' + FriendName;
		//alert(DATA);
		
		$.ajax({
			type: "POST",
			url: "getFriend.php",
			data: DATA,
			cache: false,
			success: function(data){			
				$('#currentPhotoTab').html(FriendName + ' Photos');
				$('.gallery-item').remove();
				
				//remove active class from all links and buttons
				$(".tab-btn").removeClass("active");
				$(".tab-pane").removeClass("active");
				
				//add active and fade in class to tab link and content pane
				$("#TabPhoto").addClass("active");
				$("#pane1").addClass("active in");
				
				$('#main').css("height","auto");
				$('#main').css("padding","30px 0 30px 0");
				lastPostFunc();
			}
		});	 
		
	  });
	  
	  function lastPostFunc() {
		$("#loading").show();
		var LastRecord = $(".gallery-item:last").attr("id");
		if(!$(".gallery-item:last").attr("id")) { 
			LastRecord = 0; 
		}
		var DATA = 'LastRecord=' + LastRecord;
		$.ajax({
			type: "POST",
			url: "getMoreTiles.php",
			data: DATA,
			cache: false,
			success: function(data){
				$("#loading").hide();	
				var items = $('#tiles li');
				var firstTen = items.slice(0, 10);
				$('#tiles').append(data);
				
				// Create a new layout handler.
				handler = $('#tiles li');
				handler.wookmark(options);					
			}
		});	
	  };
		
		$(window).scroll(function(){
			if  ($(window).scrollTop() == $(document).height() - $(window).height()){
			   lastPostFunc();
			}
		}); 
		
		$('li a[data-href]').live('click', function() {
			// copy the data-href value to the modal for later use
			$('#modal-photo').attr('data-href',$(this).attr('data-href'));
			// show the modal window
			$('#modal-photo').modal({show: true , backdrop : true , keyboard: true});
		}).find('a').hover( function() {
			// unbind it in case I put some a tags in the table row eventually
			$(this).parents('li').unbind('click');
		}, function() {
			$(this).parents('li').live('click', function() {
				// rebind it
				$('#modal-photo').attr('data-href',$(this).attr('data-href'));
				$('#modal-photo').modal({show: true , backdrop : true , keyboard: true});
			});
		});
		
		$('#modal-photo').live('show', function() {
			$('#modal-photo').html('');
			$('#modal-photo').css({"width":"800px","height":"80%","margin":"-400px 0 0 -400px","overflow-y":"scroll"});
			$(this).load($(this).attr('data-href'));
		});
		
    });
  </script>

  </body>
</html>
