<?php
$start = microtime();

// Include the config file
require_once '../config/config.php';

// Include the functions
require_once '../getstatus_functions.php';
require_once '../daemons_functions.php';

// Include JSON libs
require_once '../vendors/fastjson/fastjson.php';

// lets check for multiversions
$_SERVER['SERVER_NAME'] = str_replace('www.', '', $_SERVER['SERVER_NAME']);

//Set up logging
if ($config['App']['daemons_log']===TRUE) {
	define(LOG_FILE, '../tmp/logs/daemons.log');
} else {
	define(LOG_FILE, '');
}


//*** See if we're multiversion
if ($config['App']['serverName'] !== $_SERVER['SERVER_NAME']) {
	if (!empty($config['App']['multiVersions'])) {
		foreach ($config['App']['multiVersions'] as $url => $details) {
			if($url == $_SERVER['SERVER_NAME']) {
				//pr($appConfigurations['multiVersions'][$url]);exit;
				$config['App']['name']=$details['name'];
				$config['App']['timezone']=$details['timezone'];
				$config['App']['language']=$details['language'];
				$config['App']['currency']=$details['currency'];
				$config['App']['noCents']=$details['noCents'];
			}
		}
	}
}

// Setup the timezone
if(!empty($config['App']['timezone'])){
	@putenv("TZ=".$config['App']['timezone']);
}

// Reading session id from cookie
if(!empty($_COOKIE['AUCTION'])){
	$sessionId = $_COOKIE['AUCTION'];
	session_id($sessionId);
}

// Starting session
session_start();

// Reading user id
if(!empty($_SESSION['Auth']['User']['id'])){
	$user_id = $_SESSION['Auth']['User']['id'];
}else{
	$user_id = null;
}

// Turn on/off error reporting based on debug level
if($config['debug'] == 1){
	error_reporting(E_ALL);
}else{
	error_reporting(E_ERROR);
}

// Get the peak
$isPeakNow = isPeakNow();

// Lets check that the site is online
$siteOnline = siteOnline();

// Get the POST data
$data 	   = $_POST;
if(empty($data)){
	if (isset($_REQUEST['testnum'])) {
		$data = array('auction_id' => $_REQUEST['testnum']);
	} else {
		die('No data given');
	}
}

// Result array
$results = array();

// Getting rate from cache
$rate = cacheRead('cake_currency_'.strtolower($config['App']['currency']).'_rate');
if(empty($rate)) {

	// Including database
	require_once '../database.php';

	$currencyRate = mysql_fetch_array(mysql_query("SELECT rate FROM currencies WHERE currency = '".$config['App']['currency']."'"), MYSQL_ASSOC);
	if(!empty($currencyRate['rate'])){
		$rate = $currencyRate['rate'];
	}else{
		$rate = 1;
	}

	// Writing cache for currency
	cacheWrite('cake_currency_'.strtolower($config['App']['currency']).'_rate', $rate);
}

// Loop through the posted data
foreach($data as $key => $value){
	// Get the auction data
	if(!empty($_GET['histories'])) {
		$result = cacheRead('cake_auction_view_'.$value);
	} else {
		$result = cacheRead('cake_auction_'.$value);
	}
	

	if(empty($result)) {
		// Including database
		require_once '../database.php';

		// Get the auction data
		$auction = mysql_fetch_array(mysql_query("SELECT id, product_id, start_time, end_time, price, peak_only, closed FROM auctions WHERE id = $value"), MYSQL_ASSOC);

		// Get the product related to auction
		$product = mysql_fetch_array(mysql_query("SELECT id, rrp, fixed, fixed_price, buy_now FROM products WHERE id = ".$auction['product_id']), MYSQL_ASSOC);

		// Get the latest bid for auction
		//$bid = mysql_fetch_array(mysql_query("SELECT b.id, b.user_id, b.description, b.created, u.username, u.email, u.mobile FROM bids b, users u WHERE b.credit = 0 AND b.user_id = u.id AND b.auction_id = ".$value." ORDER BY b.id DESC LIMIT 1"), MYSQL_ASSOC);
		$bid = mysql_fetch_array(mysql_query("SELECT b.id, b.user_id, b.description, b.created, u.username FROM bids b, users u WHERE b.credit = 0 AND b.user_id = u.id AND b.auction_id = ".$value." ORDER BY b.id DESC LIMIT 1"), MYSQL_ASSOC);

		if(!empty($config['App']['flashMessage'])){
			// Get the message
			$message = mysql_fetch_array(mysql_query("SELECT * FROM messages WHERE auction_id = ".$auction['id']." LIMIT 1"), MYSQL_ASSOC);
			if(empty($message)){
				$message = false;
			}
		}

		// Calculate the auction and product savings
		$saving  = savings($auction, $product);
		
		//get bid increment
		$priceIncrement=get('price_increment', $auction['id'], 0);
		//if penny auction or bid_increment table is corrupt, make default increment one penny
		if ($auction['penny'] || $priceIncrement==0) {
			$priceIncrement=.01;
		}
		$auction['price_increment']=$priceIncrement;
		
		// For histories
		$lastPrice = $auction['price'] * $rate;

		// Formatting auction array
		$auction['closes_on'] 			  = niceShort($auction['end_time']);
		$auction['element']				  = $key;
		if(!empty($_GET['histories']) || !empty($_GET['savings'])) {
			$auction['savings']['percentage'] = round($saving['percentage'], 2) . ' %';
			$auction['savings']['price']      = currency($saving['price'] * $rate, $config['App']['currency']);
			//$auction['uniqueBids']            = uniqueBids($auction['id']);
		}

		// Formatting last result
		$result = array();
		$result['Product'] = $product;

		// Getting the last bid
		if(!empty($bid['username'])) {
			$bid['username'] = utf8_encode($bid['username']);
			//if(empty($bid['email']) && !empty($bid['mobile'])){
			//	$bid['username'] = substr($bid['username'], 0, (strlen($bid['username']) - 4)) . 'xxxx';
			//}
			$result['LastBid'] = $bid;
		}else{
			$result['LastBid']['username'] = 'No bids yet';
		}

		
		// If requested, include the bid histories
		if(!empty($_GET['histories'])) {
			
			// Get the bid histories
			//$bidHistories = mysql_query("SELECT b.id, b.user_id, b.description, b.debit, b.created, u.username, u.email, u.mobile FROM bids b, users u WHERE b.credit = 0 AND b.user_id = u.id AND b.auction_id = ".$auction['id']." ORDER BY b.id DESC LIMIT ".$config['App']['bidHistoryLimit']);
			$bidHistories = mysql_query("SELECT b.id, b.user_id, b.description, b.debit, b.created, u.username FROM bids b, users u WHERE b.credit = 0 AND b.user_id = u.id AND b.auction_id = ".$auction['id']." ORDER BY b.id DESC LIMIT ".$config['App']['bidHistoryLimit']);

			// Get the total rows
			$total_rows   = mysql_num_rows($bidHistories);

			// If there is a result
			if($total_rows > 0) {
				$bidHistoriesResult = array();

				// Loop through results
				while($history = mysql_fetch_array($bidHistories, MYSQL_ASSOC)) {
					$bidHistory['Bid']['id']          = $history['id'];
					$bidHistory['Bid']['created']     = niceShort($history['created']);
					$bidHistory['Bid']['description'] = $history['description'];
					$bidHistory['User']['username']   = utf8_encode($history['username']);
					//if(empty($history['email']) && !empty($history['mobile'])){
					//	$bidHistory['User']['username'] = substr($bidHistory['User']['username'], 0, (strlen($bidHistory['User']['username']) - 4)) . 'xxxx';
					//}

					if(!empty($priceIncrement)) {
						if(!empty($product['fixed'])){
							$bidHistory['Bid']['amount']  = currency($product['fixed_price'], $config['App']['currency']);
						}else{
							$bidHistory['Bid']['amount']  = currency($lastPrice, $config['App']['currency']);
						}

						if(!empty($auction['penny'])) {
						    $lastPrice -= 0.01;
						} else {
						    $lastPrice -= $priceIncrement;
						}
					}

					$bidHistoriesResult[] = $bidHistory;
				}

				// Put the formatted history to result
				$result['Histories'] = $bidHistoriesResult;
			}
		}

		// Put it in cache since the flash message only shown when
		// price changed (bid placed)
		if(!empty($config['App']['flashMessage'])){
			$result['Message'] = $message;
		}

		$result['Auction'] = $auction;

		// lets cache this query
		if (preg_match('/^auction_([0-9]+)$/', $result['Auction']['element'])) {
			if(!empty($_GET['histories'])) {
				$auction = cacheWrite('cake_auction_view_'.$value, $result);
			} else {
				$auction = cacheWrite('cake_auction_'.$value, $result);
			}
		}
	}

	// Balance reading
	if(!empty($user_id)){
		$balance = cacheRead('cake_bids_balance_'.$user_id);
		if(empty($balance)){
			require_once '../database.php';

			if(empty($config['App']['simpleBids'])){
				$balance = mysql_fetch_array(mysql_query("SELECT SUM(credit) - SUM(debit) AS balance FROM bids WHERE user_id = ".$user_id), MYSQL_ASSOC);
			}else{
				$balance = mysql_fetch_array(mysql_query("SELECT bid_balance AS balance FROM users WHERE id = ".$user_id), MYSQL_ASSOC);
			}
		}

		// Bring the balance in
		if(!empty($balance)){
			$result['Balance'] = $balance;
			cacheWrite('cake_bids_balance_'.$user_id, $balance);
		}
	}

	// some settings we can't cache

	// currencies converted
	$result['Auction']['price']   	    = currency($result['Auction']['price'] * $rate, $config['App']['currency']);
	$result['Auction']['buy_it_now']    = currency(binPrice($result['Auction']['id'], $result['Product']['buy_now'], $user_id) * $rate, $config['App']['currency']);
	$result['Product']['rrp'] 	  		= $result['Product']['rrp'] * $rate;
	$result['Product']['fixed_price']   = $result['Product']['fixed_price'] * $rate;

	// Get the bid left if this an authenticated user

	$result['Auction']['serverTimestamp'] 	= time();
	if(!empty($_GET['tf'])){
		$tf = strip_tags($_GET['tf']);
		$result['Auction']['serverTimeString'] 	= date($tf);
	}else{
		$result['Auction']['serverTimeString'] 	= date('M jS, h:i:s');
	}

	$result['Auction']['time_left']	= strtotime($result['Auction']['end_time']) - time();
	if($result['Auction']['time_left'] <= 0 && $result['Auction']['closed'] == 0){
		$result['Auction']['time_left'] = 1;
	}

	// now lets check that the site is online
	if($siteOnline == 'no' or $siteOnline=='0') {
		$result['Auction']['isPeakNow'] 	= 0;
		$result['Auction']['peak_only'] 	= 1;
	} else {
		$result['Auction']['isPeakNow'] 	= $isPeakNow;
	}
	
	if(!empty($config['App']['delayedStart'])) {
		if($result['LastBid']['username'] == 'No bids yet') {
			$result['Auction']['end_time_string'] = 'Bid Required';
		} else {
			$result['Auction']['end_time_string']   = getStringTime($result['Auction']['end_time']);
		}
	} else {
		$result['Auction']['end_time_string']   = getStringTime($result['Auction']['end_time']);
	}

	$result['Auction']['end_time'] 		  	= strtotime($result['Auction']['end_time']); // keep it last

	$result['Auction']['price_increment']=currency($result['Auction']['price_increment'], $config['App']['currency']);
	//$result['Auction']['price_increment']=$auction['price-increment'];
	
	// Put the result to main results array
	$results[] = $result;
}


//*** Shorten usernames
foreach ($results as &$result) {
	$result['LastBid']['username']=shortenName($result['LastBid']['username']);
	if (isset($result['Histories']) && !empty($result['Histories'])) {
		foreach ($result['Histories'] as &$history) {
			$history['User']['username']=shortenName($history['User']['username']);
		}
	}
}
unset($history, $result);



if(empty($_GET['test'])){
	// Set the header
	//header('Content-type: text/json');
	$json = new FastJSON();
	echo $json->convert($results);
}else{
	$end = microtime() - $start;
	echo round($end * 1000) . 'ms';
}
?>
