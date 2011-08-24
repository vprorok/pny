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
<h1>Upgrading phpTrafficA - Converting google images keywords</h1>
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

echo "<P><b>Converting google images searches that have been missed.</b>\n";
// Getting site list
$sql = "SELECT * FROM ${config_table}_sites";
$res = mysql_query ($sql,$c);
$sites = array();
while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	$sites = $sites + array($row["id"] => $row );
}
mysql_free_result($res);
// Are search engines stored as referrer?
$sql = "SELECT value FROM ${config_table} WHERE variable LIKE 'seref'";
$res = mysql_query ($sql,$c);
$row = mysql_fetch_object($res);
$seRef = $row->value;
// Investigating each site's referrer table
foreach($sites as $site) {
	echo "<br>- Looking at ".$site['site']."\n";
	$table = $site['table'];
	$kwdTable = $table."_keyword";
	$sql = "SELECT id,first,last,page,address,count FROM ${table}_referrer WHERE address LIKE '%images.google%'";
	$res = mysql_query($sql,$c);
	$index = 0;
	while ($row = mysql_fetch_object($res)) {
		$index += 1;
		$n += 1;
		$refid = $row->id;
		$ref = $row->address;
		$count = $row->count;
		$pageid = $row->page;
		$first = strtotime($row->first);
		$last = strtotime($row->last);
		echo "<br>&nbsp;&nbsp;&nbsp;- $index: $ref - $count times";
		// Extract actual search keywords
		$parse = parse_url($ref);
		$refhost = "" . $parse["scheme"] . "://" . $parse["host"] . $parse["path"];
		$refquery = $parse["query"];
		if (preg_match('/\bprev=(.*)(?:&|$)/Ui',$refquery,$regs)) {
			$engine = "Google Images";
			$searched=urldecode($regs[1]);
			$key = "q";
			preg_match('/\b'.$key.'=(.*)(?:&|$)/Ui',$searched,$regs);
			$searched = urldecode($regs[1]);
			$keywords = trim(preg_replace('/\s\s+/', ' ', $searched))." "; // Space for backwards compatibility 
			$keywords = str_replace('\'', '\\\'', $keywords);
			echo "<br>&nbsp;&nbsp;&nbsp;This matches searches for $keywords.";
			// We have it, keywords are in the $searched parameter, now try to put it into the search engines table
			$sql2 = "SELECT * FROM  ${table}_keyword WHERE STRCMP(engine,'$engine')=0 AND STRCMP(keyword,'$keywords')=0 AND page=$pageid";
			$res2 = mysql_query($sql2,$c);
			if (mysql_num_rows($res2) > 0) { // It is already in the keyword table
				$row = mysql_fetch_object($res2);
				$kwdid = $row->id;
				$kwdcount = $row->count;
				$kwdfirst = strtotime($row->first);
				$kwdlast = strtotime($row->last);
				if ($seRef) {
					// If search engines are saved in the referrer table, we can just use the referrer table values
					$firstDate = date("Y-m-d H:i:s",$first);
					$lastDate = date("Y-m-d H:i:s",$last);
					$sql3 = "UPDATE ${table}_keyword SET count=$count,last='$lastDate', first='$firstDate' WHERE id=$kwdid";
					//echo "<br>Want to $sql3";
					if (!mysql_query($sql3,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql3.");
				} else {
					// If search engines are not referrers, we have to make a mix
					$thisfirst = min($kwdfirst, $first);
					$thislast = max($kwdlast, $last);
					$thiscount = $count + $kwdcount;
					$firstDate = date("Y-m-d H:i:s",$thisfirst);
					$lastDate = date("Y-m-d H:i:s",$thislast);
					$sql3 = "UPDATE ${table}_keyword SET count=$count,last='$lastDate', first='$firstDate' WHERE id=$kwdid";
					// echo "<br>Want to $sql3";
					if (!mysql_query($sql3,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql3.");
					// We also need to remove it from the referrer table
					$sql3 = "DELETE FROM ${table}_referrer WHERE id=$refid";
					// echo "<br>Want to $sql3";
					if (!mysql_query($sql3,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql3.");
				}
			} else {
				// It is not in the keyword table. Making a new entry
				$firstDate = date("Y-m-d H:i:s",$first);
				$lastDate = date("Y-m-d H:i:s",$last);
				$sql3 ="INSERT INTO ${table}_keyword SET engine='$engine', keyword='$keywords', page='$pageid', first='$firstDate', last='$lastDate', count=$count";
				// echo "<br>Want to $sql3";
				if (!mysql_query($sql3,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql3.");
				if (!$seRef) {
					// We also need to remove it from the referrer table
					$sql3 = "DELETE FROM ${table}_referrer WHERE id=$refid";
					// echo "<br>Want to $sql3";
					if (!mysql_query($sql3,$c)) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql3.");
				}
			}
		}
		echo "<br>&nbsp;&nbsp;&nbsp;";
	}
}
@mysql_close ($c);
?>
</div>
</body>
</html>