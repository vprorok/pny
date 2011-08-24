<?php
if(empty($config)) {
	require 'config/config.php';
}

// Make a connection
if(!$db = @mysql_connect($config['Database']['host'], $config['Database']['login'], $config['Database']['password'])) {
	 die('Unable to connect to the database.');
}

// Select database
if(!@mysql_select_db($config['Database']['database'], $db)) {
	die("Unable to find the database");
}
?>