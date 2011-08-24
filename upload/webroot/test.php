<?php

// Include the config file
require_once '../config/config.php';
require_once '../database.php';

$auction_id=36;

//fishING
bid($auction_id, 12);

//lolsy
bid($auction_id, 13);

//mr2923
bid($auction_id, 14);

//CRAXX
bid($auction_id, 15);



function bid($id, $user_id) {
	mysql_query("INSERT INTO `bids` (user_id,auction_id,description,created,modified)
	       VALUES ($user_id, $id, 'test', now(), now())");
	
	$res=mysql_query("SELECT `end_time` FROM `auctions` WHERE id=$id");
	$end_time=strtotime(mysql_result($res,0,0));
	
	$end_time+=10;
	
	$end_time=date('Y-m-d H:i:s', $end_time);
		
	mysql_query("UPDATE `auctions` SET `end_time`='$end_time' WHERE id=$id");
	
}

?>