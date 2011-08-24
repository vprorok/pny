<?php
$start = microtime();

// Include the config file
require_once '../config/config.php';

// Include the functions
require_once '../daemons_functions.php';

// Include some get status functions
require_once '../getstatus_functions.php';

//Set up logging
if ($config['App']['daemons_log']===TRUE) {
	define(LOG_FILE, '../tmp/logs/daemons.log');
} else {
	define(LOG_FILE, '');
}


// lets check for multiversions
$_SERVER['SERVER_NAME'] = str_replace('www.', '', $_SERVER['SERVER_NAME']);

if(!empty($config['App']['serverName']) && !empty($config['App']['multiVersions'])) {
	if($config['App']['serverName'] !== $_SERVER['SERVER_NAME']) {
		foreach($config['App']['multiVersions'] as $url => $details) {
			if($url == $_SERVER['SERVER_NAME']) {
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

//session_start();

if($config['debug'] == 1){
	error_reporting(E_ALL);
}else{
	error_reporting(E_ERROR);
}

// Get the peak
$isPeakNow = isPeakNow();

// Lets check that the site is online
$siteOnline = siteOnline();

if($siteOnline == 'no' or $siteOnline=='0') {
	exit;
}

// just incase the database isn't called yet
require_once '../database.php';

//force autobid setting to database setting, overriding any config.php setting
$config['App']['autobids']=(get('autobidders') ? true : false);


switch($_GET['type']){
	case 'bidbutler':
		if(cacheRead('cake_bidbutler.pid')) {
			return false;
		} else {
			cacheWrite('cake_bidbutler.pid', microtime(), $config['App']['cronTime'] * 50);
		}
		
		logIt('Daemon init');
		
		// Get the bid butler time
		$bidButlerTime = get('bid_butler_time');

		// Get various settings needed
		$data['auction_peak_start'] = get('auction_peak_start');
		$data['auction_peak_end'] 	= get('auction_peak_end');
		$data['isPeakNow']  		= $isPeakNow;

		$expireTime = time() + ($config['App']['cronTime'] * 60);

		while (time() < $expireTime) {
			$bidButlerEndTime = date('Y-m-d H:i:s', time() + $bidButlerTime);
			
			// Find the bidbutler entry
			$sql = mysql_query("SELECT 	b.auction_id, 
							a.price, 
							a.reverse,
							a.free,
							b.id, 
							b.minimum_price, 
							b.maximum_price, 
							b.user_id, 
							p.fixed
							FROM auctions a, bidbutlers b, products p 
							WHERE a.id = b.auction_id 
							   AND a.product_id = p.id
							   AND a.deleted=0
							   AND a.end_time < '$bidButlerEndTime' 
							   AND a.closed = 0 
							   AND a.active = 1 
							   AND b.bids > 0 
							ORDER BY rand()");
			
			$totalRows   = mysql_num_rows($sql);
			if($totalRows > 0) {
				while($bidbutler = mysql_fetch_array($sql, MYSQL_ASSOC)) {
					if(canBidBuddy($bidbutler)) {
						// lets check that this user isn't already leading the bidding
						$bid = lastBid($bidbutler['auction_id']);
						if(!empty($bid['user_id']) && $bid['user_id'] == $bidbutler['user_id']) {
							logIt($bidbutler['auction_id']. '; user '.$bid['user_id'].' already leading');
							continue;
						}
						
						// Add more information
						$data['auction_id']	= $bidbutler['auction_id'];
						$data['user_id']	= $bidbutler['user_id'];
						$data['bid_butler']	= $bidbutler['id'];

						// add the dynamic settings
						$data['bid_debit'] 			= get('bid_debit', $data['auction_id'], 0);
						$data['price_increment'] 	= get('price_increment', $data['auction_id'], 0);

						if($config['App']['bidButlerDeploy'] == 'group') {
							if($config['App']['bidButlerType'] == 'simple') {
								$countBidButlers = mysql_query("SELECT id FROM bidbutlers WHERE auction_id = ".$data['auction_id']);
							} else {
								$auction = mysql_fetch_array(mysql_query("SELECT price FROM auctions WHERE id = ".$data['auction_id']), MYSQL_ASSOC);
								$price = $auction['price'] + $data['price_increment'];
								$countBidButlers = mysql_query("SELECT id FROM bidbutlers WHERE bids > 0 AND maximum_price > $price AND minimum_price <= $price AND auction_id = ".$data['auction_id']." AND user_id != ".$data['user_id']);
							}
							
							$moreBidButlers   = mysql_num_rows($countBidButlers);
							logIt($bidbutler['auction_id']. '; user '.$bid['user_id'].' moreBidButlers: '.$moreBidButlers);
							if($moreBidButlers > 0) {
								$data['time_increment'] 	= 0;
							} else {
								$data['time_increment'] 	= get('time_increment', $data['auction_id'], 0);
							}
						} else {
							$data['time_increment'] 	= get('time_increment', $data['auction_id'], 0);
						}

						// Bid the auction
						//exit(intval($config['App']['bidButlerRapidDeploy']));
						if ($config['App']['bidButlerRapidDeploy']>0) {   //bjw
							//we're in rapiddeploy mode			  //bjw
							
							//are there any other butlers bidding on this auction?
							$res = mysql_query("SELECT	COUNT(*)
												FROM bidbutlers
												WHERE auction_id={$data['auction_id']}
												   AND {$price} >= bidbutlers.minimum_price 
												   AND {$price} <= bidbutlers.maximum_price
												   AND bidbutlers.bids >= ".$config['App']['bidButlerRapidDeploy']);
												   
							echo("SELECT	COUNT(*)
												FROM bidbutlers
												WHERE auction_id={$data['auction_id']}
												   AND {$price} >= bidbutlers.minimum_price 
												   AND {$price} <= bidbutlers.maximum_price
												   AND bidbutlers.bids >= ".$config['App']['bidButlerRapidDeploy']);
							
							if (mysql_result($res,0,0)>1) {
								//we have other bidbutlers, let's rapiddeploy it
								$data['ignore_latest_bid']=true;
								for ($x=1; $x<$config['App']['bidButlerRapidDeploy']; $x++) {
									logIt($bidbutler['auction_id']. '; user '.$bidbutler['user_id'].' bidbuddy (RAPID) is bidding');
									$result = bid($data);
								}
								
							} else {
								//regular bidbutler
								logIt($bidbutler['auction_id']. '; user '.$bidbutler['user_id'].' bidbuddy (RDR) is bidding');
								$result = bid($data);
							}
												   
						
						} else { 							  //bjw
							//we're in regular mode
							logIt($bidbutler['auction_id']. '; user '.$bidbutler['user_id'].' bidbuddy is bidding');
							$result = bid($data);
						}								  //bjw
					}	
				}
			}
			
			// sleep until the next cycle
			$bidButlerSleep=4;
			if (isset($config['App']['bidButlerSleep'])) {
				$bidButlerSleep=intval($config['App']['bidButlerSleep']);
				if ($bidButlerSleep<1 or $bidButlerSleep>30) {
					$bidButlerSleep=4;
				}
			}
			
			sleep($bidButlerSleep);
		}
		
		cacheDelete('cake_bidbutler.pid');
		
	break;
	
	case 'extend':
		if(cacheRead('cake_extend.pid')) {
			return false;
		} else {
			cacheWrite('cake_extend.pid', microtime(), $config['App']['cronTime'] * 50);
		}
		
		//lets extend it by placing an autobid
		$data['auction_peak_start'] = get('auction_peak_start');
		$data['auction_peak_end'] 	= get('auction_peak_end');
		$data['bid_butler_time'] 	= get('bid_butler_time');
		$data['isPeakNow']  		= $isPeakNow;
		$autobid_time 				= get('autobid_time');

		$expireTime = time() + ($config['App']['cronTime'] * 60);

		while (time() < $expireTime) {
			// now check for auto extends
			if($autobid_time > 0) {
				$date = date('Y-m-d H:i:s', time() + $autobid_time);
				$sql = mysql_query("SELECT 	a.id, 
								a.end_time,
								a.autobids, 
								p.autobid_limit, 
								a.random 
								FROM auctions a, products p 
								WHERE p.id = a.product_id 
								   AND (p.autobid = 1) 
								   AND (a.price < a.minimum_price)
								   AND a.deleted=0
								   AND a.closed = 0 
								   AND a.active = 1 
								   AND a.end_time < '$date'");
			} else {
				$sql = mysql_query("SELECT 	a.id, 
								a.end_time, 
								a.autobids, 
								p.autobid_limit, 
								a.random 
								FROM auctions a, products p 
								WHERE p.id = a.product_id
								   AND a.deleted=0
								   AND  (p.autobid = 1) 
								   AND (a.price < a.minimum_price) 
								   AND a.closed = 0 
								   AND a.active = 1");
			}
				
			$total_rows   = mysql_num_rows($sql);

			if($total_rows > 0) {
				while($auction = mysql_fetch_array($sql, MYSQL_ASSOC)) {
					// lets adjust the autobid limit
					$auction['autobid_limit'] = adjustAutobidLimit($auction['id'], $auction['autobid_limit'], $auction['random']);
					
					// add the dynamic settings
					$data['time_increment'] 	= get('time_increment', $auction['id'], 0);
					$data['bid_debit'] 			= get('bid_debit', $auction['id'], 0);
					$data['price_increment'] 	= get('price_increment', $auction['id'], 0);

					if($auction['autobid_limit'] > 0) {
						if($auction['autobids'] <= $auction['autobid_limit']) {
							check($auction['id'], $auction['end_time'], $data, $config['App']['smartAutobids']);
						}
					} else {
						check($auction['id'], $auction['end_time'], $data, $config['App']['smartAutobids']);
					}
				}
			}
			// sleep for 0.5 of a second
			usleep(500000);
		}
		
		cacheDelete('cake_extend.pid');
		
	break;
									
	case 'close':
		if(cacheRead('cake_close.pid')) {
			return false;
		} else {
			cacheWrite('cake_close.pid', microtime(), $config['App']['cronTime'] * 50);
		}
		
		logIt('Daemon init');
		
		$expireTime = time() + ($config['App']['cronTime'] * 60);

		while (time() < $expireTime) {
			// lets start by getting all the auctions that have closed
			$sql = mysql_query("SELECT 	*
							FROM auctions 
							WHERE end_time <= '".date('Y-m-d H:i:s')."' 
							   AND closed = 0 
							   AND active = 1 
							   AND deleted=0");
			
			$total_rows   = mysql_num_rows($sql);
			
			if($total_rows > 0) {
				while($auction = mysql_fetch_array($sql, MYSQL_ASSOC)) {
					// before we declare this user the winner, lets run some test to make sure the auction can definitely close
					if(checkCanClose($auction['id'], $isPeakNow) == false) {
						// lets check to see if the reason we can't close it, is because its now offpeak and this is a peak auction
						if($auction['peak_only'] == 1 && !$isPeakNow) {
							$peak = isPeakNow(true);
							if(strtotime($peak['peak_start']) < time()) {
								$peak['peak_start'] = date('Y-m-d H:i:s', strtotime($peak['peak_start']) + 86400);	
							}
							
							//Calculate how many seconds auction will end after peak end
							$seconds_after_peak = strtotime($auction['end_time']) - strtotime($peak['peak_end']);
							$time = strtotime($peak['peak_start']) + $seconds_after_peak;
							
							$endTime = date('Y-m-d H:i:s', $time);
							
							// lets just make sure the new time is past the current time
							if(strtotime($endTime) < time()) {
								// it's not, so lets adjust the time
								$endTime = date('Y-m-d H:i:s', strtotime($endTime) + 86400);	
							}
										
							mysql_query("UPDATE auctions SET end_time = '$endTime', modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$auction['id']);
						} else {
							logIt($auction['id'].' close extend');
							
							//lets extend it by placing an autobid
							$data['auction_peak_start'] = get('auction_peak_start');
							$data['auction_peak_end'] 	= get('auction_peak_end');
							$data['bid_butler_time'] 	= get('bid_butler_time');
							$data['isPeakNow']  		= $isPeakNow;
	
							// add the dynamic settings
							$data['time_increment'] 	= get('time_increment', $auction['id'], 0);
							$data['bid_debit'] 			= get('bid_debit', $auction['id'], 0);
							$data['price_increment'] 	= get('price_increment', $auction['id'], 0);
	
							placeAutobid($auction['id'], $data, $config['App']['smartAutobids'], 3);
						} 
					} else {
						logIt($auction['id'].' OK to close');
						closeAuction($auction);
					}
				}
			}
			// sleep for 0.5 of a second
			usleep(500000);
			mysql_free_result($sql);
		}
		
		cacheDelete('cake_close.pid');
		
	break;
}
?>
