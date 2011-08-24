<?php
// mySQL configuration
// used for accessing database
global  $config_table, $server, $user, $password, $base;
//$server = "localhost";   // replace by you mySQL server, could be something like 'localhost:3306' or 'localhost', or 'mysql.myhost.com'
//$user = "mysqluser";     // replace by your login to mySQL server
//$password = "mysqlpass"; // replace by your password

//$base = "phptraffica";     // replace by the database where you want to create tables
$config_table = "phpTrafficA_conf";


if (file_exists('../config/config.php')) {
	require('../config/config.php');
} else {
	require('../../config/config.php');
}


$server=$config['Database']['host'];
$user=$config['Database']['login'];
$password=$config['Database']['password'];
$base=$config['Database']['database'];


?>
