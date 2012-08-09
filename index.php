<?php
	// load up your config file  
    require_once("config.php");  
	
	if (!isset($_SESSION['oauth_id']) && isset($_COOKIE['oauth_id']) && $_COOKIE['oauth_id']!= '') { 
		$_SESSION['oauth_id'] = $_COOKIE['oauth_id']; 
	}
	
	//check if user is logged in 
	if (isset($_SESSION['oauth_id']) && $_SESSION['oauth_id']!= '') { 
		header("Location: login-facebook.php");
	} 
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
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="#"><?php echo $GLOBALS['config']['params']['sitename']; ?></a>
        </div>
      </div>
    </div>

    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <img src="img/sample.jpg" width="267" height="300" align="right" style="margin-top:-50px; padding-left:50px;">
        <h1>Welcome. </h1>
        <p>This website will connect to your Facebook photo albums and display them in a dynamic photo grid.</p>
        <p>Connect to Facebook now by clicking 'login'.</p>
        <p><a class="btn btn-primary btn-large" href="login-facebook.php"><img src="img/fb.png" width="20" height="24">&nbsp;Facebook Login</a></p>
      </div>

      <hr>

      <footer>
        <p>&copy; <?php echo $GLOBALS['config']['params']['sitename']; ?> 2012</p>
      </footer>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>

  </body>
</html>
