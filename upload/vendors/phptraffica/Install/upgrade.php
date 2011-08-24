<?php
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2008 ZoneO-soft, Butchu (email: "butchu" with the domain "zoneo.net")

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/
include ("../Php/config_sql.php");
include ("../Php/config.php");

// Testing log-in
if ($_POST['todo'] == "login") {
	$c = @mysql_connect("$server","$user","$password");
	$db = @mysql_select_db("$base",$c);
	$md5 = md5($_POST['parola']);
	$doit = "SELECT value FROM $config_table WHERE variable='adminpassword'";
	$res = mysql_query($doit,$c);
	while ($row = mysql_fetch_object($res)) {
		$value = $row->value;
		if ($value == $md5) {
			setcookie("phpTrafficA_install_pwd", $md5);
			@mysql_close ($c);
			header("Location: upgrade.php");
		}
	}
	$admintxt = "<div class=\"error\">\nWrong password\n</div>";
	@mysql_close ($c);
} else {
	$admintxt = "";
}

?>
<html>
<title>Welcome to phpTrafficA</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<META NAME="AUTHOR" CONTENT="phpTrafficA">
<link rel="stylesheet" href="install.css" type="text/css">
</head>
<body>
<div id="main">
<h1>Upgrading phpTrafficA - step 1</h1>
<?php

function diehere($error, $extra="") {
	$txt = "<div class=\"error\">\n$error\n";
	if ($extra != "") $txt .= "<br>&nbsp;<br>$extra\n";
	$txt .= "</div>\n<b>Upgrade failed</b></div>\n</body>\n</html>\n";
	@mysql_close ($c);
	die($txt);
}

// ****************************************************************************
// 0- Testing connection to database
// ****************************************************************************
echo "\n<P><b>Database</b>";
echo "<br>Connecting to database server. User is <code>$user</code> and server is <code>$server</code>.";
$c = @mysql_connect("$server","$user","$password") or diehere("Can not connect to database in install.php:<br>".mysql_error());
echo " <font color='#35BA4D'>Ok</font>";
echo "<br>Selecting base defined in config_sql.php: <code>$base</code>.";
$db = @mysql_select_db("$base",$c) or diehere("Can not select base in install.php:<br>".mysql_error());
echo " <font color='#35BA4D'>Ok</font>";
echo "<br>Retrieve the MySQL server version: ";
$mysql_version = mysql_get_server_info();
$pos = strpos($mysql_version, '.');
$pos = strpos($mysql_version, '.',$pos+1);
$mysql_version_major = substr($mysql_version, 0, $pos);
echo "$mysql_version\n";
if ($mysql_version_major < 4.2) {
	$engine_or_type = "TYPE";
} else {
	$engine_or_type = "ENGINE";
}

// ****************************************************************************
// 0.1- Testing log-in
// ****************************************************************************

if ($_POST['todo'] == "newadmin") {
	$md5 = md5($_POST['parola']);
	$doit = "INSERT INTO $config_table VALUES ('adminpassword','$md5')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}

$doit = "SELECT * FROM $config_table WHERE variable LIKE 'adminpassword'";
if (!mysql_query($doit,$c))
	diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
$res = mysql_query($doit,$c);
if (mysql_num_rows($res) < 1) {
	// No admin password in the database, we create it
	echo "\n<P><b>Administration password is now stored in the database</b>
<script type=\"text/javascript\">
function validates(theForm)	{
if ((theForm.parola.value == \"\") || (theForm.parola.value.length < 6)){
	alert(\"Password is too short\");
	return false;
}
if (theForm.parola.value != theForm.verparola.value){ 
	alert(\"Passwords do not match\");
	return false;
}
}
</script>
<form name=\"admindates\" method=\"POST\" action=\"upgrade.php\" OnSubmit=\"return validates(this);\">
<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"80%\" align=\"center\">
<tr><td width=\"50%\">Administrator password</td><td width=\"50%\"><input type=\"password\" name=\"parola\" size=\"30\"></td></tr>
<tr><td width=\"50%\">Administrator password (again)</td><td width=\"50%\"><input type=\"password\" name=\"verparola\" size=\"30\"></td></tr>
<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Submit\"><input type=\"hidden\" name=\"todo\" value=\"newadmin\"></td></tr>
</table>
</form>
</div>
</body>
</html>";
	@mysql_close ($c);
	exit();
} else {
	// Test log-in, if not logged-in, show the log-in form
	$login = false;
	if(isset($_COOKIE["phpTrafficA_install_pwd"])) {
		$md5 = $_COOKIE["phpTrafficA_install_pwd"];
		$doit = "SELECT value FROM $config_table WHERE variable LIKE 'adminpassword'";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		$res = mysql_query($doit,$c);
		while ($row = mysql_fetch_object($res)) {
			$value = $row->value;
			if ($value == $md5) $login = true;
		}
	}
	if (!$login) {
	echo "\n<P><b>Enter administration password</b>$admintxt\n
<form name=\"admindates\" method=\"POST\" action=\"upgrade.php\" >
<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"80%\" align=\"center\">
<tr><td width=\"50%\">Administrator password</td><td width=\"50%\"><input type=\"password\" name=\"parola\" size=\"30\"></td></tr>
<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Submit\"><input type=\"hidden\" name=\"todo\" value=\"login\"></td></tr>
</table>
</form>
</div>
</body>
</html>";
		@mysql_close ($c);
		exit();
	}
}


// ****************************************************************************
// 1- Read and write permission to sites.php or database table with site list
// ****************************************************************************
$ok = false;
echo "<br>Testing database for sites information: <code>${config_table}_sites</code> ";
$sql = @mysql_query("show table status like '${config_table}_sites'",$c) or diehere("Error with database at line ".__LINE__.": ".mysql_error());
$table_exists = mysql_num_rows($sql) == 1;
if ($table_exists) {
	$ok = true;
	$sitesInTable = true;
	echo "<font color='#35BA4D'>Ok</font>";
} else {
	echo "<font color='#FF0000'>Failed</font>";
	echo "<P><b>Testing access to site list</b><br>Checking read permissions to file sites.php\n";
	$ok = true;
	$sitesInTable = false;
	echo "<br>- Access to file: ";
	if (is_file('sites.php')) { 
		echo "<font color='#35BA4D'>Ok</font>";
	} else {
		echo "<font color='#FF0000'>Failed</font>";
		$ok = false;
	}
	echo "<br>- Reading file: ";
	if (is_readable('sites.php')) {
		echo "<font color='#35BA4D'>Ok</font>";
	} else {
		echo "<font color='#FF0000'>Failed</font>";
		$ok = false;
	}
}
if (!$ok) {
	diehere("Can not find sites information.<br>- If upgrading from a version of phpTrafficA older than 2.0, copy the file <code>sites.php</code> from a previous installation to the <code>Install/</code> directory.<br>- Otherwise, make sure that the <code>\$config_table</code> variable in <code>config.php</code> is set properly.");
}

// ****************************************************************************
// 2- Filesystem test
// ****************************************************************************
echo "\n<P><b>Filesystem test</b>";
$path =  __FILE__;
$path = preg_replace( "'\\\upgrade\.php'", "", $path);
$path = preg_replace( "'/upgrade\.php'", "", $path);
$path = "$path/../$tmpdirectory";
echo "\n<br>Path for temporary files: <code>$path</code>";
echo "\n<br>Testing creation of a temporary file in tmp directory: ";
$file = "$path/test.txt";
if (@touch($file)) {
	echo "<font color='#35BA4D'>Ok</font>";
} else {
	echo "<font color='#FF0000'>Failed</font>";
	$ok = false;
}
echo "\n<br>Trying to delete the file that was just created: ";
if (@unlink($file)) {
	echo "<font color='#35BA4D'>Ok</font>";
} else {
	echo "<font color='#FF0000'>Failed</font>";
	$ok = false;
}
echo "\n<br>End of file system test. Status: ";
if ($ok) {
	echo "<font color='#35BA4D'>Pass</font>.";
} else {
	echo "<font color='#FF0000'>Failed</font>.";
}
if (!$ok) {
	diehere ("\nThere was an error in the filesystem test. The upgrade will stop here. Refer to <a href=\"http://soft.ZoneO.net/phpTrafficA\">phpTrafficA homepage</a> for more information.");
}

// ****************************************************************************
// 3.1- PHP Server and extension
// ****************************************************************************
echo "\n<P><b>PHP Server</b>\n";
echo "<br>- PHP version: ".phpversion()."\n";
echo "<br>- Looking for GD extension: ";
if ((!extension_loaded('gd')) || (!function_exists('gd_info'))) {
	echo "<font color='#FF0000'>failed</font>.\n";
	diehere("GD library is needed by phpTrafficA. Please install this extension before using phpTrafficA");
} else {
	echo "<font color='#35BA4D'>pass</font>.\n";
	$gd_info = gd_info();
	echo "<br>- GD version: ".$gd_info['GD Version']."\n";
	echo "<br>- Checking for FreeType support in GD: ";
	if ($gd_info['FreeType Support']) {
		echo "<font color='#35BA4D'>pass</font>.\n";
	} else {
		echo "<font color='#FF0000'>failed</font>.\n";
		diehere("FreeType support in GD is needed by phpTrafficA. Please install this extension before using phpTrafficA");
	}
}
echo "</P>\n";

// ****************************************************************************
// 4- Upgrades based on sites.php
// ****************************************************************************
if (!$sitesInTable) {
	// If the SQL table with sites does not exist, we have an upgrade
	// from a version older than 2.0. We run a specific upgrade based
	// on the file 'sites.php'
	include('upgradeSites.php');
}

// ****************************************************************************
// 5- Adding new parameters to configuration table
// ****************************************************************************
include ("./confDB.sql.php");
echo "<P><b>Upgrading configuration table.</b>\n";
$doit = "SELECT * FROM $config_table";
if (!mysql_query($doit,$c))
	diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
$res = mysql_query($doit,$c);
$config = array("x" =>"x");
while($row = mysql_fetch_array($res)) {
	$config = $config + array($row["variable"] => $row["value"] );
}
mysql_free_result ($res);
if (!isset($config["ntop"])) {
	echo "<br>- Inserting <code>ntop</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('ntop', '$ntop')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["ntoplong"])) {
	echo "<br>- Inserting <code>ntoplong</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('ntoplong', '$ntoplong')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["seref"])) {
	echo "<br>- Inserting <code>seref</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('seref', '$seref')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["visitcutoff"])) {
	echo "<br>- Inserting <code>visitcutoff</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('visitcutoff', '$visitcutoff')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["stringLengthsFactor"])) {
	echo "<br>- Inserting <code>stringLengthsFactor</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('stringLengthsFactor', '$stringLengthsFactor')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["referrerNewDuration"])) {
	echo "<br>- Inserting <code>referrerNewDuration</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('referrerNewDuration', '$referrerNewDuration')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["cleanRefIPKwdPath"])) {
	echo "<br>- Inserting <code>cleanRefIPKwdPath</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('cleanRefIPKwdPath', '$cleanRefIPKwdPath')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["cleanRefIPKwdPathInt"])) {
	echo "<br>- Inserting <code>cleanRefIPKwdPathInt</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('cleanRefIPKwdPathInt', '$cleanRefIPKwdPathInt')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["lastCleanRefIPKwdPath"])) {
	echo "<br>- Inserting <code>lastCleanRefIPKwdPath</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('lastCleanRefIPKwdPath', '1975-01-01')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["cleanAccess"])) {
	echo "<br>- Inserting <code>cleanAccess</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('cleanAccess', '$cleanAccess')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["cleanAccessInt"])) {
	echo "<br>- Inserting <code>cleanAccessInt</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('cleanAccessInt', '$cleanAccessInt')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
if (!isset($config["lastCleanAccess"])) {
	echo "<br>- Inserting <code>lastCleanAccess</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('lastCleanAccess', '1975-01-01')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}

if (!isset($config["version"])) {
	echo "<br>- Inserting <code>version</code> parameter.\n";
	$doit = "INSERT INTO $config_table VALUES ('version', '$version')";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
} else {
	echo "<br>- Updating <code>version</code> parameter.\n";
	$doit = "UPDATE $config_table SET value='$version' WHERE variable='version'";
	if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
}
echo "<br><font color='#35BA4D'>Done</font>.\n";

// ****************************************************************************
// 6- Makeing sure that table for IPBan is available
// ****************************************************************************

echo "<P><b>IP ban table.</b>\n";
echo "<br>Testing database for ip ban information: <code>${config_table}_ipban</code> ";
$sql = @mysql_query("show table status like '${config_table}_ipban'",$c) or diehere("Error with database at line ".__LINE__.": ".mysql_error());
$table_exists = mysql_num_rows($sql) == 1;
if ($table_exists) {
	echo "<font color='#35BA4D'>Ok</font>.\n";
	$doit = "SHOW columns FROM ${config_table}_ipban";
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	$res = mysql_query($doit,$c);
	$cols = array();
	while($elt = mysql_fetch_array($res)) {
		$cols[] = $elt[0];
	}
	if (! in_array("range", $cols) ) {
		echo "<br>- Adding 'range' column to the table ";
		$doit = "ALTER TABLE `${config_table}_ipban` ADD `range` INT DEFAULT '0'";
		$res = mysql_query($doit,$c);
		if (!$res) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		echo "<font color='#35BA4D'>Ok</font>.\n";
	}
	if (! in_array("last", $cols) ) {
		echo "<br>- Adding 'last' column to the table ";
		$doit = "ALTER TABLE `${config_table}_ipban` ADD `last` DATE";
		$res = mysql_query($doit,$c);
		if (!$res) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		echo "<font color='#35BA4D'>Ok</font>.\n";
	}
	// Convert the IP column into bigint, int was too small
	$doit = "SHOW columns FROM ${config_table}_ipban LIKE 'ip'";
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	$res = mysql_query($doit,$c);
	$elt = mysql_fetch_array($res);
	if (strtolower($elt['Type']) == "int(11)") {
		$sql = "ALTER TABLE ${config_table}_ipban CHANGE `ip` `ip` bigint(11) NOT NULL default '0'";
		echo "<br>Your version of <code>${config_table}_ipban</code> has limitations with large IP addresses. Upgrading the <code>IP</code> column type from <code>int</code> to <code>bigint</code>.";
		$res = mysql_query($sql,$c);
		if (!$res) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
		echo " <font color='#35BA4D'>Done</font>.\n";
	}
} else {
	echo "<font color='#FF0000'>Failed</font>.\n";
	echo "<br>Creating missing table ";
	$doit = $sqlCreateIPBan;
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "<br>Was working on $doit.");
	echo "<font color='#35BA4D'>Ok</font>.\n";
}

// ****************************************************************************
// 7- Updating tables with latest hosts to store IP addresses as long
// ****************************************************************************

echo "<P><b>Latest visitors tables.</b>\n";
$sql = "SELECT * FROM ${config_table}_sites";
$res = mysql_query ($sql,$c);
$sites = array();
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$sites = $sites + array($row["id"] => $row );
}
mysql_free_result($res);
foreach($sites as $site) {
	$table = $site['table'];
	$doit = "SHOW columns FROM ${table}_host";
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	$res = mysql_query($doit,$c);
	$cols = array();
	while($elt = mysql_fetch_array($res)) {
		$cols[] = $elt[0];
	}
	if (! in_array("longIP", $cols) ) {
		echo "<br>- Adding 'longIP' column to the table ${table}_host ";
		$doit = "ALTER TABLE `${table}_host` ADD `longIP` bigint(11) NOT NULL default '0'";
		$res = mysql_query($doit,$c);
		if (!$res) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		echo "<font color='#35BA4D'>Ok</font>.\n";
		echo "<br>- Converting all IP addresses from ${table}_host to long ";
		$doit = "SELECT host FROM `${table}_host` GROUP BY host";
		$res = mysql_query($doit,$c);
		while ($row = mysql_fetch_object($res)) {
			$ip = $row->host;
			$long = ip2long($ip);
			$sql = "UPDATE ${table}_host SET longIP=$long WHERE host='$ip'";
			$res2 = mysql_query($sql,$c);
			if (!$res2) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
		}
		echo "<font color='#35BA4D'>Ok</font>.\n";
	}
}
echo "<br>- Done ";

// ****************************************************************************
// 8- Creating tables to track screen resolutions
// ****************************************************************************

echo "<P><b>Tables for screen resolutions.</b>\n";
foreach($sites as $site) {
	$table = $site['table'];
	echo "<br>- Testing table: <code>${table}_resolution</code> ";
	$sql = @mysql_query("show table status like '${table}_resolution'",$c) or diehere("Error with database at line ".__LINE__.": ".mysql_error());
	$table_exists = mysql_num_rows($sql) == 1;
	if (!$table_exists) {
		echo "<font color='#FF0000'>Failed</font>.\n";
		echo "<br>- Creating missing table ";
		$doit = "CREATE TABLE `${table}_resolution` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `label` varchar(50) NOT NULL default '', `date` date NOT NULL default '0000-00-00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "<br>Was working on $doit.");
		echo "<font color='#35BA4D'>Ok</font>.\n";
	} else {
		echo "<font color='#35BA4D'>Ok</font>.\n";
	}
}
echo "<br>- Done ";


// ****************************************************************************
// 9- Converting strings to UTF-8
// ****************************************************************************

echo "<P><b>Converting strings in tables to UTF-8.</b>\n";
if ($mysql_version_major < 4.1) {
	echo "<br>- UTF-8 support supported by mysql 4.1 and above\n";
	echo "<br>- Your version is $mysql_version: skipping UTF-8 support\n";
} else {
	$tableColList = array( "keyword" => array("keyword", "engine"), "pages" => array("name"), "host" => array("page") );
	foreach($sites as $site) {
		$table = $site['table'];
		foreach ($tableColList as $thistable => $cols) {
			$tableall = "${table}_${thistable}";
			$bckp = false;
			foreach ($cols as $thiscol) {
				echo "<br>- Testing column <code>$thiscol</code> in table <code>${tableall}</code>: ";
				$doit = "SHOW FULL columns FROM $tableall LIKE '$thiscol'";
				if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
				$res = mysql_query($doit,$c);
				$elt = mysql_fetch_array($res);
				if (strpos(strtolower($elt['Collation']), "utf8") === false) {
					echo " needs upgrade ";
					$coltype = $elt['Type'];
					if (!$bckp) {
						echo "<br>&nbsp;&nbsp;- Creating a backup <code>${tableall}_bckp</code> ";
						$sql = @mysql_query("show table status like '${tableall}_bckp'",$c) or diehere("Error with database at line ".__LINE__.": ".mysql_error());
						$table_exists = mysql_num_rows($sql) == 1;
						if ($table_exists) {
							$sql = "drop table ${tableall}_bckp";
							if (!mysql_query($sql,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
						}
						$listCreateTable = mysql_query("show create table $tableall");
						$createTable = mysql_fetch_array($listCreateTable);
						$create = $createTable[1];
						$create = preg_replace("/auto_increment=\d+/i", "", $create);
						$create = str_replace($tableall, "${tableall}_bckp", $create);
						if (!mysql_query($create,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $create.");
						$sql = "INSERT INTO `${tableall}_bckp` SELECT * FROM `${tableall}`";
						if (!mysql_query($sql,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
						$bckp = true;
						echo "<font color='#35BA4D'>Ok</font>.\n";
					}
					echo "<br>&nbsp;&nbsp;- Converting column to UTF8 ";
					if ($thiscol == "keyword") {
						$sql = "ALTER TABLE `${tableall}` CHANGE keyword keyword BLOB;";
						if (!mysql_query($sql,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
						$sql = "ALTER TABLE `${tableall}` CHANGE `$thiscol` `$thiscol` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
						if (!mysql_query($sql,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
					} else {
						$sql = "ALTER TABLE `${tableall}` CHANGE `$thiscol` `$thiscol` $coltype CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
						if (!mysql_query($sql,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
					}
					echo "<font color='#35BA4D'>Ok</font>.\n";
				} else {
					echo "<font color='#35BA4D'>Ok</font>.\n";
				}
			}
		}
	}
}
echo "<br>- Done ";

// ****************************************************************************
// 10- Creating tables to track and tag visitors
// ****************************************************************************

echo "<P><b>Tables for visitors tagging.</b>\n";
if ($mysql_version_major > 4.1) {
	$utf = "character set utf8 collate utf8_unicode_ci";
} else {
	$utf = "";
}
foreach($sites as $site) {
	$table = $site['table'];
	echo "<br>- Testing table: <code>${table}_iplist</code> ";
	$sql = @mysql_query("show table status like '${table}_iplist'",$c) or diehere("Error with database at line ".__LINE__.": ".mysql_error());
	$table_exists = mysql_num_rows($sql) == 1;
	if (!$table_exists) {
		echo "<font color='#FF0000'>Failed</font>.\n";
		echo "<br>- Creating missing table ";
		$doit = "CREATE TABLE `${table}_iplist` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `ip` bigint(11) NOT NULL default '0', `label` varchar(50) $utf NOT NULL default '', `first` datetime NOT NULL default '0000-00-00 00:00:00', `last` datetime NOT NULL default '0000-00-00 00:00:00', `count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "<br>Was working on $doit.");
		echo "<font color='#35BA4D'>Ok</font>.\n";
	} else {
		echo "<font color='#35BA4D'>Ok</font>.\n";
	}
}
echo "<br>- Done ";

// ****************************************************************************
// 11- Adding a column to the referrer table to track which one was visited
// ****************************************************************************

echo "<P><b>Updating referrer tables.</b>\n";
foreach($sites as $site) {
	$table = $site['table'];
	echo "<br>- Testing <code>${table}_referrer</code>. ";
	$doit = "SHOW columns FROM ${table}_referrer";
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	$res = mysql_query($doit,$c);
	$cols = array();
	while($elt = mysql_fetch_array($res)) {
		$cols[] = $elt[0];
	}
	if (! in_array("visited", $cols) ) {
		echo "<br>- Adding <code>visited</code> column to the table <code>${table}_referrer</code> ";
		$doit = "ALTER TABLE `${table}_referrer` ADD `visited` BOOL NULL DEFAULT '0'";
		$res = mysql_query($doit,$c);
		if (!$res) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		echo "<font color='#35BA4D'>Ok</font>.\n";
		echo "<br>- Setting the <code>visited</code> tag to 1 for all referrers ";
		$sql = "UPDATE ${table}_referrer SET visited=1";
		$res2 = mysql_query($sql,$c);
		if (!$res2) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
	}
	echo "<font color='#35BA4D'>Ok</font>.\n";
}

@mysql_close ($c);

// ****************************************************************************
// 11- Upgrade of browser and search engine list
// ****************************************************************************
?>
<FORM action="upgrade2.php" method="POST">
<table border="0" align="center">
<tr><td><INPUT type="checkbox" checked name="upgradeOS" value="upgradeOS"></td><td>Upgrade list of operating systems</td></tr>
<tr><td><INPUT type="checkbox" checked name="upgradeWB" value="upgradeWB"></td><td>Upgrade list of web browsers</td></tr>
<tr><td><INPUT type="checkbox" checked name="upgradeSE" value="upgradeSE"></td><td>Upgrade list of search engines</td></tr>
<tr><td><INPUT type="checkbox" checked name="insertIPBan" value="insertIPBan"></td><td>Insert ban IP for known spammers</td></tr>
<tr><td colspan="2" align="center"><INPUT type="submit" value="Proceed"></td></tr>
</table>
</FORM>
</div>
</body>
</html>