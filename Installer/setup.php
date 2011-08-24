<?php

define('FROM_SETUP', true);

session_start();

// if (!is_writable('./')) {
	// exit('The working directory is not writeable. Please change its permissions (for example <b>chmod a+rw ./</b>) and try again.');
// }
                   
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpPennyAuction Installer (v2.4.2)</title>
<script type="text/javascript" src="install-data/js/jquery.js"></script>
<script type="text/javascript" src="install-data/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="install-data/js/tooltip.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="install-data/css/screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="install-data/css/print.css" type="text/css" media="print">
<link rel="stylesheet" href="install-data/css/blueprint/typography.css" type="text/css" media="screen, projection, print">
<link rel="stylesheet" href="install-data/css/blueprint/forms.css" type="text/css" media="screen, projection, print">
<link rel="stylesheet" href="install-data/css/jquery-ui.css" type="text/css" media="screen, projection">
<!--[if lt IE 8]><link rel="stylesheet" href="install-data/css/ie.css" type="text/css" media="screen, projection"><![endif]-->
</head>
<body>
	<div class="container">
		<div class="span-24 last colborder box" style="background-color:#395584; text-align:center;">
        <a href="/setup.php"><img src="install-data/img/logo.png" alt="Install phpPennyAuction" /></a>
		</div>
		
		<div class="span-24">
		<?php
		
		require('install-data/include/main.inc.php');
		
		?>
		</div>
		
		<div class="last span-24 quiet box prepend-top">
		All contents &copy; 2011 Scriptmatix Ltd. <a href="http://www.phppennyauction.com" target="_blank">phpPennyAuction v2.4.2 Installer</a>
		</div>
	</div>
</body>
</html>