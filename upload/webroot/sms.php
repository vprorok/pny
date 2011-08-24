<?php
// Include the config file
require_once '../config/config.php';

// Include the functions
require_once '../daemons_functions.php';

// Include some get status functions
require_once '../getstatus_functions.php';

// Include some sms functions
require_once '../sms_functions.php';

writeLog('SMS: Get SMS Bidding request');

// lets check for multiversions
$_SERVER['SERVER_NAME'] = str_replace('www.', '', $_SERVER['SERVER_NAME']);

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

writeLog('SMS: Checking Gateway');

// Function to check and select the proper gateway
$data = checkGateway();

// Invalid Gateway just to make sure the provider
// get 200 reply
if(empty($data)){
	writeLog('SMS: No data returned from gateway check, probably invalid gateway');
	die("No data returned from gateway check, probably invalid gateway");
}

// Debugging stuff
if($config['debug'] == 1){
	error_reporting(E_ALL);
}else{
	error_reporting(E_ERROR);
}

// Check the peak
$data['isPeakNow']  = isPeakNow();

writeLog('SMS: Adding bid for sms bidding');

// Adding 1 bid for sms bidding since user pay it differently
$bid['user_id'] 	= $data['user_id'];
$bid['auction_id']  = 0;
$bid['description'] = 'SMS Bid';
$bid['credit'] 		= get('bid_debit', $data['auction_id'], 0);
$bid['debit'] 		= 0;
$smsBid = mysql_query("INSERT INTO bids (user_id, auction_id, description, credit, debit, created, modified) VALUES ('".$bid['user_id']."', '".$bid['auction_id']."', '".$bid['description']."', '".$bid['credit']."', '".$bid['debit']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
if(!$smsBid){
	writeLog('SMS: Cannot insert sms bidding. MySQL message : '.mysql_error($db));
}

writeLog('SMS: Bidding the auction');

// Bid the auction
$auction = bid($data);

// hidden reserve checking
if(!empty($config['App']['hiddenReserve'])) {
	$hiddenReserve = hiddenReserve($auction);
	if($hiddenReserve == true) {
		$auction['Auction']['message'] = __('You just won this auction - your bid was the hidden reserve!', true);
	}
}
writeLog('SMS: '.$auction['Auction']['message']);

// Reply if needed
checkReply($data);

// Print the message
echo $auction['Auction']['message'];
?>