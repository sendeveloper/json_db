<?php
	if (!defined('SERVERNAME'))
    define('SERVERNAME', 'localhost');
	if (!defined('USERNAME'))
	    define('USERNAME', 'root');
	if (!defined('PASSWORD'))
	    define('PASSWORD', '');
	if (!defined('DBNAME'))
	    define('DBNAME', 'adult_pharmacist');

	$mysqli = new mysqli(SERVERNAME,USERNAME,PASSWORD,DBNAME);	
	// Check connection	
	if ($mysqli->connect_errno)	{	  	
	    echo "Failed to connect to MySQL: " . $mysqli->connect_error;	  	
	    exit();	
	}
?>