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
?>
<html>
<title>Welcome to phpTrafficA</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<META NAME="AUTHOR" CONTENT="phpTrafficA">
<link rel="stylesheet" href="install.css" type="text/css">
</head>
<body>
<div id="main">
<h1>Installing phpTrafficA</h1>
<?php

function diehere($error, $extra="") {
	$txt = "<div class=\"error\">\n$error\n";
	if ($extra != "") $txt .= "<br>&nbsp;<br>$extra\n";
	$txt .= "</div><b>Installation failed</b></div>\n</body>\n</html>\n";
	@mysql_close ($c);
	die($txt);
}

// If entering admin password
if (isset($_POST['todo'])) {
	if ($_POST['todo'] == "newadmin") {
		echo "\n<P><b>Creating new admin</b>";
		$c = @mysql_connect("$server","$user","$password") or diehere("Can not connect to database in install.php:<br>".mysql_error());
		$db = @mysql_select_db("$base",$c) or diehere("Can not select base in install.php:<br>".mysql_error());
		$doit = "INSERT INTO $config_table VALUES ('adminpassword', MD5('".$_POST['parola']."'))";
		if (!mysql_query($doit,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		@mysql_close ($c);
		echo "<br><font color='#35BA4D'>Done</font>";
		echo "<P>Installation complete. Delete the <code>Install</code> directory and enjoy!</P>\n</div>\n</body>\n</html>";
		exit();
	}
}

$ok = true;
echo "\n<P><b>Filesystem test</b>\n<br>Testing creation of a temporary file in tmp directory: ";
$path =  __FILE__;
$path = preg_replace( "'\\\install\.php'", "", $path);
$path = preg_replace( "'/install\.php'", "", $path);
$path = "$path/../$tmpdirectory";
$file = "$path/test.txt";
if (touch($file)) {
	echo "<font color='#35BA4D'>Ok</font>";
} else {
	echo "<font color='#FF0000'>Failed</font>";
	$ok = false;
}
echo "\n<br>Trying to delete the file that was just created: ";
if (unlink($file)) {
	echo "<font color='#35BA4D'>Ok</font>";
} else {
	echo "<font color='#FF0000'>Failed</font>";
	$ok = false;
}
echo "\n<br>End of file system test. Status: ";
if ($ok) {
	echo "<font color='#35BA4D'>Pass</font>.</P>";
} else {
	echo "<font color='#FF0000'>Failed</font>.</P>";
}
if (!$ok) {
	diehere("There was an error in the filesystem test. The installation will stop here. Refer to <a href=\"http://soft.ZoneO.net/phpTrafficA\">phpTrafficA homepage</a> for more information.</P><P>Directory for temporary file was: $path");
}

// PHP Server
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
	echo "<br>- checking for FreeType suppory in GD: ";
	if ($gd_info['FreeType Support']) {
		echo "<font color='#35BA4D'>pass</font>.\n";
	} else {
		echo "<font color='#FF0000'>failed</font>.\n";
		diehere("FreeType support in GD is needed by phpTrafficA. Please install this extension before using phpTrafficA");
	}
}
echo "</P>\n";


echo "\n<P><b>Database</b>";

echo "<br>Connecting to database server. User is <code>$user</code> and server is <code>$server</code>.";
$c = @mysql_connect("$server","$user","$password") or diehere("Can not connect to database in install.php:<br>".mysql_error());
echo " <font color='#35BA4D'>Ok</font>";

echo "<br>Selecting base defined in config_sql.php: <code>$base</code>.";
$db = @mysql_select_db("$base",$c) or diehere("Can not select base in install.php:<br>".mysql_error());
echo " <font color='#35BA4D'>Ok</font>\n";

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
include ("./confDB.sql.php");
$sqllist = explode("\n", $sqlcreate);

echo "<br>Creating and filling configuration table: <code>$config_table</code>.";
foreach($sqllist as $doit) {
	if (!mysql_query($doit,$c))
		diehere("Error with database:<br>".mysql_error(), "Was working on $doit.");
}
echo " <font color='#35BA4D'>Ok</font>";

echo "<br>Creating table for ip banning: <code>${config_table}_ipban</code>.";
$doit = $sqlCreateIPBan;
if (!mysql_query($doit,$c))
	diehere("Error with database:<br>".mysql_error(),"Was working on $doit.");
echo " <font color='#35BA4D'>Ok</font>";
echo "<P>Inserting known spammers into IP ban table. ";
$thisdate = date("Y-m-d");
foreach($ipbanlist as $id=>$iprange) {
	$start = $iprange[0];
	$range = $iprange[1];
	$ipstart = long2ip($start);
	$ipend = long2ip($start+$range);
	$sql = "SELECT count(*) as count FROM ${config_table}_ipban WHERE ((ip=$start) AND (`range`=$range))";
	$res = mysql_query($sql,$c);
	$row = mysql_fetch_object($res);
	if (($row->count == 0) or ($row->count == '')) {
		echo "<br>- Inserting range $ipstart - $ipend, ";
		$time = date("Y-m-d");
		$sql = "INSERT INTO `${config_table}_ipban` SET ip='$start', `range`='$range', date='$thisdate', last='$thisdate', count=0";
		$res = mysql_query($sql, $c);
	}
}
echo "<br><font color='#35BA4D'>Done</font>.</P>\n";

echo "\n<br>Creating table for site list in database: <code>${config_table}_sites</code>.";
$doit = $sqlCreateSite;
if (!mysql_query($doit,$c))
	diehere("Error with database:<br>".mysql_error(),"Was working on $doit.");
echo " <font color='#35BA4D'>Ok</font>";

@mysql_close ($c);

echo "\n<br>End database work. Status: ";
if ($ok) {
	echo "<font color='#35BA4D'>Pass</font>.</P>";
} else {
	echo "<font color='#FF0000'>Failed</font>.</P>";
}

?>

<P><b>Create administration password</b>
<script type="text/javascript">
function validates(theForm) {
if ((theForm.parola.value == "") || (theForm.parola.value.length < 6)){
	alert("Password is too short");
	return false;
}
if (theForm.parola.value != theForm.verparola.value){ 
	alert("Passwords do not match");
	return false;
}
}
</script>
<form name="admindates" method="POST" action="install.php" OnSubmit="return validates(this);">
<table border="0" cellpadding="3" cellspacing="0" width="80%" align="center">
<tr><td width="50%">Administrator password</td><td width="50%"><input type="password" name="parola" size="30"></td></tr>
<tr><td width="50%">Administrator password (again)</td><td width="50%"><input type="password" name="verparola" size="30"></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Submit"><input type="hidden" name="todo" value="newadmin"></td></tr>
</table>
</form>
</div>
</body>
</html>