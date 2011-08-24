<?
/***************************************************************************
 phpTrafficA @soft.ZoneO.net
 Copyright (C) 2004-2008 ZoneO-soft, Butchu (email: "butchu" with the domain "zoneo.net")

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 More Info About The Licence At http://www.gnu.org/copyleft/gpl.html
****************************************************************************/
?>
<html>
<title>Welcome to phpTrafficA</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<META NAME="AUTHOR" CONTENT="phpTrafficA">
<link rel="stylesheet" href="install.css" type="text/css">
</head>
<body>
<div id="main">
<h1>Upgrading phpTrafficA - step 2</h1>
<?

function diehere($error, $extra="") {
	$txt = "<div class=\"error\">\n$error\n";
	if ($extra != "") $txt .= "<br>&nbsp;<br>$extra\n";
	$txt .= "</div>\n<b>Upgrade failed</b></div>\n</body>\n</html>\n";
	@mysql_close ($c);
	die($txt);
}

include ("../Php/config_sql.php");
include ("../Php/config.php");
include ("./confDB.sql.php");
$c = mysql_connect("$server","$user","$password") or diehere("Can not connect to database in upgrade2.php: ".mysql_error());
$db = mysql_select_db("$base",$c) or diehere("Can not select base in upgrade2.php: ".mysql_error());
if (isset($_POST['upgradeOS'])) {
	echo "<P>Upgrading list of operating systems. ";
	if (!mysql_query($upgradeOS,$c))
		diehere("Error with database: ".mysql_error(), "Was working on $doit.");
	echo " <font color='#35BA4D'>Done</font>.</P>\n";
}
if (isset($_POST['upgradeWB'])) {
	echo "<P>Upgrading list of web browsers. ";
	if (!mysql_query($upgradeWB,$c))
		diehere("Error with database: ".mysql_error(), "Was working on $doit.");
	echo " <font color='#35BA4D'>Done</font>.</P>\n";
}
if (isset($_POST['insertIPBan'])) {
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
			echo "<br>- Inserting range $ipstart - $ipend,";
			$time = date("Y-m-d");
			$sql = "INSERT INTO `${config_table}_ipban` SET ip='$start', `range`='$range', date='$thisdate', last='$thisdate', count=0";
			$res = mysql_query($sql, $c);
		}
	}
	echo "<br><font color='#35BA4D'>Done</font>.</P>\n";
}
@mysql_close ($c);
echo "<P>Upgrade complete.</P><P>Delete the <code>Install</code> directory and enjoy!</P>";
?>
</div>
</body>
</html>