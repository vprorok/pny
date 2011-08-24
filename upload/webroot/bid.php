<?php
// Include the config file
require_once '../config/config.php';

// Include the functions
require_once '../daemons_functions.php';

// Include some get status functions
require_once '../getstatus_functions.php';

// lets check for multiversions
$_SERVER['SERVER_NAME'] = str_replace('www.', '', $_SERVER['SERVER_NAME']);

//Set up logging
if ($config['App']['daemons_log']===TRUE) {
	define(LOG_FILE, '../tmp/logs/daemons.log');
} else {
	define(LOG_FILE, '');
}

if(!empty($config['App']['serverName']) && !empty($config['App']['multiVersions'])) {
	if($config['App']['serverName'] !== $_SERVER['SERVER_NAME']) {
		foreach($config['App']['multiVersions'] as $url => $details) {
			if($url == $_SERVER['SERVER_NAME']) {
				$config['App']['name'] = $config['App']['multiVersions'][$url]['name'];
				$config['App']['currency'] = $config['App']['multiVersions'][$url]['currency'];
				$config['App']['noCents'] = $config['App']['multiVersions'][$url]['noCents'];
				$config['App']['timezone'] = $config['App']['multiVersions'][$url]['timezone'];
			}
		}
	}
}

// Setup the timezone
if(!empty($config['App']['timezone'])){
	putenv("TZ=".$config['App']['timezone']);
}

// just incase the database isn't called yet
require_once '../database.php';

// NORMAL BIDDING
// Reading session id from cookie
if(!empty($_COOKIE['AUCTION'])){
	$sessionId = $_COOKIE['AUCTION'];
	session_id($sessionId);
}

// Starting session
session_start();

// Reading user id
if(!empty($_SESSION['Auth'])){
	$data['user_id'] = $_SESSION['Auth']['User']['id'];
}else{
	$data['user_id'] = null;
}

if(!empty($_GET['id'])) {
	$data['auction_id']	= $_GET['id'];

	$data['time_increment'] 	= get('time_increment', $data['auction_id'], 0);
	$data['bid_debit'] 			= get('bid_debit', $data['auction_id'], 0);
	$data['price_increment'] 	= get('price_increment', $data['auction_id'], 0);
}

// Debugging stuff
if($config['debug'] == 1){
	error_reporting(E_ALL);
}else{
	error_reporting(E_ERROR);
}

$data['isPeakNow']  = isPeakNow();

// Bid the auction
$auction = bid($data);

// hidden reserve checking
if(!empty($config['App']['hiddenReserve'])) {
	$hiddenReserve = hiddenReserve($auction);
	if($hiddenReserve == true) {
		$auction['message'] = __('You just won this auction - your bid was the hidden reserve!', true);
	}
}

if(empty($_GET['mobile'])){
	// Include JSON libs
	require_once '../vendors/fastjson/fastjson.php';

	// Set the header
	header('Content-type: text/json');

	$json = new FastJSON();
	echo $json->convert($auction);
}else{
	if($config['Mobile']['replyOnSuccess']){
		//reply sms here
	}
}
?>