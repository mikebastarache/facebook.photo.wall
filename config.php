<?php 
$GLOBALS["config"] = array(  
    "db" => array(
            "dbname" => "sthsport_facebookphotowall",  
            "username" => "sthsport",  
            "password" => "SimHL2007",  
            "host" => "localhost"  
    ),  
    "urls" => array(  
        "baseUrl" => "http://fb.digitalmagic.ca"  
    ),    
    "params" => array(  
        "adminmail" => "mike@digitalmagic.ca" ,
        "sitename" => "Facebook Photo Wall"
    ), 
	"fb" => array(  
        "app_id" => "273631972720847" ,
        "app_secret" => "bd7754ef5b1680a46217c02bc7dfc43b"
    ),  
    "paths" => array(  
        "resources" => "/path/to/resources",  
        "images" => $_SERVER["DOCUMENT_ROOT"] . "/img/layout" 
    )  
);  
  
defined("LIBRARY_PATH")  
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/lib'));  

if (!isset($_SESSION)) {
  session_start();
}
?>  