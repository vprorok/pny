<?php

session_name('AUCTION');
session_start();

if (!isset($_SESSION['Auth']['User']['admin']) or $_SESSION['Auth']['User']['admin']!=1) {
	header("Location: /users/login");
	exit;
}



$vendor_path=realpath('../vendors/phptraffica/');

chdir($vendor_path);
require($vendor_path.'/plotStatBar.php');

?>
