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


/****************************************************************************/
/***  Upgrade based on file 'sites.php' that was used up to version 1.2   ***/
/***  Quite long and complex so moved out of main upgrade file            ***/
/***  Will not be used after version 2.0 (file 'sites.php' is gone)       ***/
/***  Used to upgrade any install of phpTrafficA older than 2.0 to 2.0    ***/
/***  10/2007                                                             ***/
/****************************************************************************/

echo "<P><b>Upgrades based on sites.php:</b>\n";

// ****************************************************************************
// 1- upgrade sites.php v1.0 (more options, such as trim URL and 
//    public/private)
// ****************************************************************************
include('sites.php');
if (!isset($vsite)) { $vsite = 1.0; }
if ($vsite < 1.1) {
	echo "<br>- your version of phpTrafficA is older than 1.1. Upgrading.\n";
	$newString = "<?php  \n// Sites beging audited\n\$vsite = 1.1;\n\$sites = array( ";
	$i = 0;
	while ($bar=each($sites)) {
		$thisid = $bar[0];
		$thistable = $bar[1][table];
		$thissite = $bar[1][site];
		echo "<br>&nbsp;&nbsp;* Upgrading entry for $thissite\n";
		$thispublic = 1; // Default in phpTrafficA: public
		$thistrim = 0; // Default in phpTrafficA: do trim
		if ($i==0) {
			$newString .= "\n\"$thisid\"=> array(\"table\" =>\"$thistable\", \"site\"=>\"$thissite\", \"public\"=>$thispublic, \"trim\"=>$thistrim)";
		}  else {
			$newString .= "\n,\"$thisid\"=> array(\"table\" =>\"$thistable\", \"site\"=>\"$thissite\", \"public\"=>$thispublic, \"trim\"=>$thistrim)";
		}
		$i+=1;
	}
	$newString .= ");\n?>";
	$temp = fopen ("sites.php", 'w');
	fwrite($temp, $newString);
	fclose($temp);
	echo "<br><font color='#35BA4D'>Pass</font>.\n";
}

// ****************************************************************************
// 2- upgrade sites.php v1.1 (add extra table for each site in sql database 
//       for unique IP and path analysis + more fields in page table)
// ****************************************************************************
include('sites.php');
if ($vsite < 1.2) {
	echo "<br>- your version of phpTrafficA is older than 1.2. Upgrading.\n";
	$newString = "<?php  \n// Sites beging audited\n\$vsite = 1.2;\n\$sites = array( ";
	$i = 0;
	while ($bar=each($sites)) {
		$thisid = $bar[0];
		$thistable = $bar[1][table];
		$thissite = $bar[1][site];
		$thispublic = $bar[1]['public'];
		$thistrim = $bar[1][trim];
		echo "<br>&nbsp;&nbsp;* Upgrading entry for $thissite\n";
		if ($i==0) {
			$newString .= "\n\"$thisid\"=> array(\"table\" =>\"$thistable\", \"site\"=>\"$thissite\", \"public\"=>$thispublic, \"trim\"=>$thistrim)";
		}  else {
			$newString .= "\n,\"$thisid\"=> array(\"table\" =>\"$thistable\", \"site\"=>\"$thissite\", \"public\"=>$thispublic, \"trim\"=>$thistrim)";
		}
		echo "<br>&nbsp;&nbsp;* Creating tables ${thistable}_uniq and ${thistable}_path in database\n";
		$doit = "CREATE TABLE `${thistable}_uniq` ( `id` mediumint(9) unsigned NOT NULL auto_increment, `label` mediumint(9) unsigned default '0', `date` date NOT NULL default '0000-00-00', `count` mediumint(9) unsigned default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		$doit = "CREATE TABLE `${thistable}_path` ( `id` mediumint(9) unsigned NOT NULL auto_increment,  `first` datetime NOT NULL default '0000-00-00 00:00:00', `last` datetime NOT NULL default '0000-00-00 00:00:00', `entry` mediumint(9) unsigned default '0', `exit` mediumint(9) unsigned default '0', `path` tinytext NOT NULL, `length` mediumint(9) unsigned NOT NULL default '0',`count` mediumint(9) unsigned NOT NULL default '0', PRIMARY KEY (`id`) ) TYPE=MyISAM";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		echo "<br>&nbsp;&nbsp;* Upgrading ${thistable}_pages in database\n";
		$doit = "ALTER TABLE `${thistable}_pages` ADD `ref` MEDIUMINT DEFAULT '0', ADD `se` MEDIUMINT DEFAULT '0', ADD `internal` MEDIUMINT DEFAULT '0', ADD `other` MEDIUMINT DEFAULT '0'";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		$i+=1;
	}
	$newString .= ");\n?>";
	$temp = fopen ("sites.php", 'w');
	fwrite($temp, $newString);
	fclose($temp);
	echo "<br><font color='#35BA4D'>Pass</font>.\n";
}

// ****************************************************************************
// 3- Fix a bug introduced in 1.2beta1 that forgot columns in some SQL tables
// ****************************************************************************
include('sites.php');
echo "<br>- Testing an error introduced in 1.2beta1.\n";
while ($bar=each($sites)) {
	$thistable = $bar[1][table];
	$thissite = $bar[1][site];
	$doit = "SHOW columns FROM ${thistable}_pages";
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	$res = mysql_query($doit,$c);
	$count = 0;
	while ($elt = mysql_fetch_array ( $res )) {
		$count += 1;
	}
	if ($count == 3) {
		echo "<br>&nbsp;&nbsp;* Upgrading ${thistable}_pages in database\n";
		$doit = "ALTER TABLE `${thistable}_pages` ADD `ref` MEDIUMINT DEFAULT '0', ADD `se` MEDIUMINT DEFAULT '0', ADD `internal` MEDIUMINT DEFAULT '0', ADD `other` MEDIUMINT DEFAULT '0'";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	}
}
echo "<br><font color='#35BA4D'>Pass</font>.\n";

// ****************************************************************************
// 4- Add a new column in pages table, new since 1.3
//     This column is used for old sites only that did not record accesses in 
//     the se, ref, internal, or other columns
// ****************************************************************************
reset($sites);
echo "<br>- Testing upgrades of tables to 1.3.\n";
while ($bar=each($sites)) {
	$thistable = $bar[1][table];
	$thissite = $bar[1][site];
	$doit = "SHOW columns FROM ${thistable}_pages";
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	$res = mysql_query($doit,$c);
	$count = 0;
	while ($elt = mysql_fetch_array ( $res )) {
		$count += 1;
	}
	if ($count == 7) {
		echo "<br>&nbsp;&nbsp;* Your version of the tables $thistable is <= 1.2. Table with pages needs to be upgraded.\n";
		$doit = "ALTER TABLE `${thistable}_pages` ADD `old` MEDIUMINT DEFAULT '0'";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
		// Now we want to fill up this old column...
		$req = "SELECT SUM(count) as count, label FROM ${thistable}_acces GROUP BY label";
		$res = mysql_query($req,$c);
		$pageCount = array();
		while ($row = mysql_fetch_object($res)) {
			$pageCount[$row->label] = $row->count;
		}
		$req = "SELECT id,ref,se,internal,other FROM ${thistable}_pages";
		$res = mysql_query($req,$c);
		$pageCount2 = array();
		while ($row = mysql_fetch_object($res)) {
			$pageCount2[$row->id] = $row->ref+$row->se+$row->internal+$row->other;
			$pageName[$row->id] = $row->name;
		}
		foreach($pageCount2 AS $id => $count2){
			$correction = $pageCount[$id]-$count2;
			$sql = "UPDATE `${thistable}_pages` SET old=$correction WHERE id=$id";
			$res = mysql_query($sql,$c);
		}
		echo " <font color='#35BA4D'>Done</font>.";
		// Calculating totals for each day
		echo "<br>&nbsp;&nbsp;* Acces table for $thistable needs to be upgraded as well...";
		$req = "SELECT date ,SUM(count) as count FROM ${thistable}_acces GROUP BY date";
		$res = mysql_query($req,$c);
		while($row = mysql_fetch_object($res)) {
			$date = $row->date;
			$count = $row->count;
			$req2 ="INSERT INTO `${thistable}_acces` VALUES ('', 0, '$date','$count')";
			// $req2 = "UPDATE `${thistable}_acces` SET count=$count WHERE label=0 AND date='$date'";
			$res2 = mysql_query($req2,$c);
		}
		echo " <font color='#35BA4D'>Done</font>.\n";
	}
}
echo "<br><font color='#35BA4D'>Done</font>.\n";

// ****************************************************************************
// 5- upgrade sites.php v1.2 (add extra configuration options: 
// counter and crawler stats that were introduced in 1.4)
// ****************************************************************************
include('sites.php');
reset($sites);
if ($vsite < 1.4) {
	echo "<br>- Your version of sites.php is <= 1.4. It needs to be upgraded.\n";
	$newString = "<?php  \n// Sites beging audited\n\$vsite = 1.4;\n\$sites = array( ";
	$i = 0;
	while ($bar=each($sites)) {
		$thisid = $bar[0];
		$thistable = $bar[1][table];
		$thissite = $bar[1][site];
		$thispublic = $bar[1]['public'];
		$thistrim = $bar[1]['trim'];
		$thiscrawler = 1; // by default, we record crawlers
		$thiscounter = 0; // by default, phpTrafficA is not a counter
		echo "<br>&nbsp;&nbsp;* Upgrading entry for $thissite\n";
		if ($i==0) {
			$newString .= "\n\"$thisid\"=> array(\"table\" =>\"$thistable\", \"site\"=>\"$thissite\", \"public\"=>$thispublic, \"trim\"=>$thistrim, \"crawler\"=>$thiscrawler, \"counter\"=>$thiscounter)";
		}  else {
			$newString .= "\n,\"$thisid\"=> array(\"table\" =>\"$thistable\", \"site\"=>\"$thissite\", \"public\"=>$thispublic, \"trim\"=>$thistrim, \"crawler\"=>$thiscrawler, \"counter\"=>$thiscounter)";
		}
		$i+=1;
	}
	$newString .= ");\n?>";
	$temp = fopen ("sites.php", 'w');
	fwrite($temp, $newString);
	fclose($temp);
	echo "<br><font color='#35BA4D'>Done</font>.\n";
}
include('sites.php');
reset($sites);

// ****************************************************************************
// 6- Change column type of column 'address' in referrer tables. It was tinytext but turned out to be too small for some URL. Change it to text type after 1.4beta3
// ****************************************************************************
while ($bar=each($sites)) {
	$thistable = $bar[1][table];
	$thissite = $bar[1][site];
	$doit = "SHOW columns FROM ${thistable}_referrer LIKE 'address'";
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	$res = mysql_query($doit,$c);
	$elt = mysql_fetch_array($res);
	if (strtolower($elt['Type']) == "tinytext") {
		$sql = "ALTER TABLE ${thistable}_referrer CHANGE `address` `address` TEXT NOT NULL";
		echo "<br>- Your version of <code>${thistable}_referrer</code> has limitations with long URLs. Upgrading the <code>address</code> column type from <code>tinytext</code> to <code>text</code>.";
		$res = mysql_query($sql,$c);
		if (!$res) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
		echo " <font color='#35BA4D'>Done</font>.\n";
	}
}
reset($sites);

// ****************************************************************************
// 7- Change column type of column 'ref' in latest hosts tables. It was tinytext but turned out to be too small for some URL. Change it to text type after 1.4beta4
// ****************************************************************************
while ($bar=each($sites)) {
	$thistable = $bar[1][table];
	$thissite = $bar[1][site];
	$doit = "SHOW columns FROM ${thistable}_host LIKE 'ref'";
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	$res = mysql_query($doit,$c);
	$elt = mysql_fetch_array($res);
	if (strtolower($elt['Type']) == "tinytext") {
		$sql = "ALTER TABLE ${thistable}_host CHANGE `ref` `ref` TEXT NOT NULL";
		echo "<br>- Your version of <code>${thistable}_host</code> has limitations with long URLs. Upgrading the <code>ref</code> column type from <code>tinytext</code> to <code>text</code>.";
		$res = mysql_query($sql,$c);
		if (!$res) diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $sql.");
	}
	echo " <font color='#35BA4D'>Done</font>.\n";
}
reset($sites);

// ****************************************************************************
// 8- Create a new sql table to get rid of the sites.php file
//      sites information will now be stored in the db
//      09/2007 v-2.0
// ****************************************************************************
$doit = "show table status like '${config_table}_sites'";
$sql = mysql_query($doit,$c) or diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit");
$table_exists = mysql_num_rows($sql) == 1;
if (!$table_exists) {
	echo "<br>- Your configuration is from version <=1.4. Creating SQL table ${config_table}_sites to hold sites informations.";
	// Columns in sites:
	//   id, table, site, public, trim, crawler, counter
	include('confDB.sql.php');
	$doit = $sqlCreateSite;
	if (!mysql_query($doit,$c))
		diehere("Error with database at line ".__LINE__.": ".mysql_error(), "<br>Was working on $doit.");
	while ($bar=each($sites)) {
		$thisid = $bar[0];
		$thistable = $bar[1]['table'];
		$thissite = $bar[1]['site'];
		$thistrim = $bar[1]['trim'];
		$thispublic = $bar[1]['public'];
		$thiscrawler = $bar[1]['crawler'];
		$thiscounter = $bar[1]['counter'];
		$doit = "INSERT INTO `${config_table}_sites` VALUES ('$thisid', '$thistable', '$thissite', '$thispublic', '$thistrim', '$thiscrawler', '$thiscounter', '0')";
		if (!mysql_query($doit,$c))
			diehere("Error with database at line ".__LINE__.": ".mysql_error(), "Was working on $doit.");
	}
	echo " <font color='#35BA4D'>Done</font>.\n";
}
reset($sites);
echo "<BR>Done with all upgrades based on <code>sites.php</code>. This file will not be used anymore.</P>\n";

?>