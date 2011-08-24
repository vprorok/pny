<?php

function pr($variable = null){
	echo "<pre>";
	print_r($variable);
	echo "</pre>";
}

function debug($variable = null){
	pr($variable);
}

// this function will need to be updated at some point.
function __($text) {
	return $text;
}

function getStringTime($timestamp){
	$diff 	= strtotime($timestamp) - time();

	if($diff < 0) $diff = 0;

	$day    = floor($diff / 86400);
	if($day < 1){
		$day = '';
	}else{
		$day = $day.'d';
	}

	$diff   -= $day * 86400;
	$hour   = floor($diff / 3600);
	if($hour < 10) $hour = '0'.$hour;
	$diff   -= $hour * 3600;

	$minute = floor($diff / 60);
	if($minute < 10) $minute = '0'.$minute;
	$diff   -= $minute * 60;

	$second = $diff;
	if($second < 10) $second = '0'.$second;

	return trim($day.' '.$hour.':'.$minute.':'.$second);
}

function savings($auction, $product) {
	if($product['rrp'] > 0) {
		if(!empty($product['fixed'])) {
			if($product['fixed_price'] > 0) {
				$data['percentage'] = 100 - ($product['fixed_price'] / $product['rrp'] * 100);
			} else {
				$data['percentage'] = 100;
			}
			$data['price']  = $product['rrp'] - $product['fixed_price'];
		} else {
			$data['percentage'] = 100 - ($auction['price'] / $product['rrp'] * 100);
			$data['price']      = $product['rrp'] - $auction['price'];
		}
	} else {
		$data['percentage'] = 0;
		$data['price'] = 0;
	}

	$data['percentage'] = number_format($data['percentage'], 2);
	$data['price'] 		= $data['price'];

	return $data;
}

function currency($number, $currency = 'USD', $options = array()) {
	global $config;

	$default = array(
		'before'=>'', 'after' => '', 'zero' => '0', 'places' => 2, 'thousands' => ',',
		'decimals' => '.','negative' => '()', 'escape' => true
	);

	$currencies = array(
		'USD' => array(
			'before' => '$', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => ',',
			'decimals' => '.', 'negative' => '()', 'escape' => true
		),
		'GBP' => array(
			'before'=>'&#163;', 'after' => 'p', 'zero' => 0, 'places' => 2, 'thousands' => ',',
			'decimals' => '.', 'negative' => '()','escape' => false
		),
		'EUR' => array(
			'before'=>'&#8364;', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => '.',
			'decimals' => ',', 'negative' => '()', 'escape' => false
		),
		'AUD' => array(
			'before' => '$', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => ',',
			'decimals' => '.', 'negative' => '()', 'escape' => true
		),
		'NZD' => array(
			'before' => '$', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => ',',
			'decimals' => '.', 'negative' => '()', 'escape' => true
		),
		'CAD' => array(
			'before' => 'C$', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => ',',
			'decimals' => '.', 'negative' => '()', 'escape' => true
		),
		'PLN' => array(
			'before'=>'z&#322;', 'after' => '', 'zero' => 0, 'places' => 2, 'thousands' => '',
			'decimals' => ',', 'negative' => '()', 'escape' => false
		),
		'LEI' => array(
			'before'=>'', 'after' => 'LEI', 'zero' => 0, 'places' => 2, 'thousands' => '',
			'decimals' => ',', 'negative' => '()', 'escape' => false
		),
		'NOK' => array(
			'before' => 'kr ', 'after' => '', 'zero' => 0, 'places' => 2, 'thousands' => ',',
			'decimals' => '.', 'negative' => '()', 'escape' => true
		)
	);

	if (isset($currencies[$currency])) {
		$default = $currencies[$currency];
	} elseif (is_string($currency)) {
		$options['before'] = $currency;
	}

	$options = array_merge($default, $options);

	$result = null;

	if ($number == 0 ) {
		if ($options['zero'] !== 0 ) {
			return $options['zero'];
		}
		$options['after'] = null;
	} elseif ($number < 1 && $number > -1 ) {
		if($config['App']['noCents'] == true) {
			$options['after'] = null;
		} else {
			if(!empty($options['after'])){
				$multiply = intval('1' . str_pad('', $options['places'], '0'));
				$number = $number * $multiply;
				$options['before'] = null;
				$options['places'] = null;
			}
		}
	} else {
		$options['after'] = null;
	}

	$abs = abs($number);

	$result = _format($abs, $options);

	if ($number < 0 ) {
		if($options['negative'] == '()') {
			$result = '(' . $result .')';
		} else {
			$result = $options['negative'] . $result;
		}
	}
	return $result;
}

function niceShort($dateString = null, $userOffset = null) {
	global $config;

	$date = $dateString ? _fromString($dateString, $userOffset) : time();

	$y = _isThisYear($date) ? '' : ' Y';
	if(!empty($config['App']['timeFormat'])){
		$timeFormat = $config['App']['timeFormat'];
		if($timeFormat == 12){
			$timeFormat = "h:i:s A";
		}else{
			$timeFormat = "H:i:s";
		}
	}else{
		$timeFormat = "H:i:s";
	}

	if (_isToday($date)) {
		$ret = "Today, " . date($timeFormat, $date);
	} elseif (_wasYesterday($date)) {
		$ret = "Yesterday, " . date($timeFormat, $date);
	} else {
		$ret = date("M jS{$y}, ".$timeFormat, $date);
	}

	return $ret;
}

function uniqueBids($auction_id) {
	$bid = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT user_id) AS 'count' FROM bids WHERE auction_id = ".$auction_id), MYSQL_ASSOC);
	return $bid['count'];
}

function isPeakNow($returnDates = false) {
	if($returnDates == false) {
		$isPeakNow = cacheRead('cake_peak');
	} else {
		$isPeakNow = null;
	}

	if(strlen($isPeakNow) == 0) {
		$data = array();
		$isPeakNow = 0;

		$auction_peak_start = cacheRead('cake_auction_peak_start');
		if(!empty($auction_peak_start)) {
			$data['auction_peak_start'] = $auction_peak_start;
		} else {
			require_once '../database.php';
			$auction_peak_start = mysql_fetch_array(mysql_query("SELECT value FROM settings WHERE name = 'auction_peak_start'"), MYSQL_ASSOC);
			$auction = cacheWrite('cake_auction_peak_start', $auction_peak_start['value']);
			$data['auction_peak_start'] = $auction_peak_start['value'];
		}

		$auction_peak_end = cacheRead('cake_auction_peak_end');
		if(!empty($auction_peak_end)) {
			$data['auction_peak_end'] = $auction_peak_end;
		} else {
			require_once '../database.php';
			$auction_peak_end = mysql_fetch_array(mysql_query("SELECT value FROM settings WHERE name = 'auction_peak_end'"), MYSQL_ASSOC);
			echo mysql_error();
			$auction = cacheWrite('cake_auction_peak_end', $auction_peak_end['value']);
			$data['auction_peak_end'] = $auction_peak_end['value'];
			
		}
		
		$peak_start_hour   = $data['auction_peak_start'];
		$peak_end_hour   = $data['auction_peak_end'];

		$peak_length = intval($peak_end_hour) - intval($peak_start_hour);

		if($peak_length <= 0) {
			$peak_start = date('Y-m-d') . ' ' . $data['auction_peak_start'] . ':00';
			$peak_end   = date('Y-m-d', time() + 86400) . ' ' . $data['auction_peak_end'] . ':00';
		} else {
			$peak_start = date('Y-m-d') . ' ' . $data['auction_peak_start'] . ':00';
			$peak_end   = date('Y-m-d') . ' ' . $data['auction_peak_end'] . ':00';
		}

		// 19/02/2009 - Michael - lets do some adjustments on the peak times
		if($peak_end > date('Y-m-d H:i:s', time() + 86400)) {
			$peak_end   = date('Y-m-d', time()) . ' ' . $data['auction_peak_end'] . ':00';
		}
		if($peak_start > date('Y-m-d H:i:s')) {
			$peak_start   = date('Y-m-d', time() - 86400) . ' ' . $data['auction_peak_start'] . ':00';
		}
		if($peak_start < date('Y-m-d H:i:s', time() - 86400)) {
			$peak_start   = date('Y-m-d') . ' ' . $data['auction_peak_start'] . ':00';
		}

		// peak start and end time should never be more than 24 hours apart
		if(strtotime($peak_end) - strtotime($peak_start) > 86400) {
			// lets adjust peak end back to where it should be
			$peak_end   = date('Y-m-d') . ' ' . $data['auction_peak_end'] . ':00';
		}

		// lets check to see if the different is STILL more than 24 hours
		if(strtotime($peak_end) - strtotime($peak_start) > 86400) {
			// lets adjust peak end back to where it should be - back 1 day
			$peak_end   = date('Y-m-d H:i:s', strtotime($peak_end) - 86400);
		}

		if($returnDates == true) {
			$data['peak_end']   = $peak_end;
			$data['peak_start'] = $peak_start;
			return $data;
		}

		$now = time();

		if($now > strtotime($peak_start) && $now < strtotime($peak_end)) {
			$isPeakNow = 1;
		}

		cacheWrite('cake_peak', $isPeakNow, 60);
	}

	return $isPeakNow;
}

function siteOnline() {
	$site_live = cacheRead('cake_site_live');

	if(!empty($site_live)) {
		return $site_live;
	} else {
		require_once '../database.php';
		$site_live = mysql_fetch_array(mysql_query("SELECT value FROM settings WHERE name = 'site_live'"), MYSQL_ASSOC);
		$auction = cacheWrite('cake_site_live', $site_live['value']);
		return $site_live['value'];
	}
}

function cacheRead($file) {
	if(file_exists('../tmp/cache/'.$file)) {
		$data = file('../tmp/cache/'.$file);
		if(time() < $data[0]) {
			return unserialize($data[1]);
		} else {
			unlink('../tmp/cache/'.$file);
			return null;
		}
	} else {
		return null;
	}
}

function cacheDelete($file) {
	if(file_exists('../tmp/cache/'.$file)) {
		unlink('../tmp/cache/'.$file);
		return true;
	} else {
		return false;
	}
}

function cacheWrite($file, $data, $duration = '86400') {
	$lineBreak = "\n"; // this function will NOT work on a windows server without further modification

	$data = serialize($data);

	$expires = time() + $duration;
	$contents = $expires . $lineBreak . $data . $lineBreak;

	$cache_dir='../tmp/cache/';
	
	if (!is_dir($cache_dir)) {
		mkdir($cache_dir);
	}
	
	$write = $cache_dir.$file;
	$result = fopen($write, 'w');

	if (is_writable($write)) {
		if (!$handle = fopen($write, 'a')) {
			return false;
		}
		if (fwrite($result, $contents) === false) {
			return false;
		}
		fclose($result);
		return true;
	} else {
	     return false;
	}
}

function _isThisYear($dateString, $userOffset = null) {
	$date = _fromString($dateString, $userOffset);
	return  date('Y', $date) == date('Y', time());
}

function _isToday($dateString, $userOffset = null) {
	$date = _fromString($dateString, $userOffset);
	return date('Y-m-d', $date) == date('Y-m-d', time());
}

function _wasYesterday($dateString, $userOffset = null) {
	$date = _fromString($dateString, $userOffset);
	return date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'));
}

function _fromString($dateString, $userOffset = null) {
	if (is_integer($dateString) || is_numeric($dateString)) {
		$date = intval($dateString);
	} else {
		$date = strtotime($dateString);
	}

	return $date;
}

function _format($number, $options = false) {
	$places = 0;
	if (is_int($options)) {
		$places = $options;
	}

	$separators = array(',', '.', '-', ':');

	$before = $after = null;
	if (is_string($options) && !in_array($options, $separators)) {
		$before = $options;
	}
	$thousands = ',';
	if (!is_array($options) && in_array($options, $separators)) {
		$thousands = $options;
	}
	$decimals = '.';
	if (!is_array($options) && in_array($options, $separators)) {
		$decimals = $options;
	}

	$escape = true;
	if (is_array($options)) {
		$options = array_merge(array('before'=>'$', 'places' => 2, 'thousands' => ',', 'decimals' => '.'), $options);
		extract($options);
	}

	$out = $before . number_format($number, $places, $decimals, $thousands) . $after;
	if ($escape) {
		return _h($out);
	}
	return $out;
}

function _h($text, $charset = null) {
	global $config;

	if (is_array($text)) {
		return array_map('h', $text);
	}
	if (empty($charset)) {
		$charset = $config['App']['encoding'];
	}
	if (empty($charset)) {
		$charset = 'UTF-8';
	}
	return htmlspecialchars($text, ENT_QUOTES, $charset);
}

function writeLog($message, $file = 'error.log'){
	global $config;

	if($config['debug'] != 0){
		$lineBreak = "\n"; // this function will NOT work on a windows server without further modification

		$contents = date('Y-m-d H:i:s') . ' ' . $message. $lineBreak;

		$write = '../tmp/logs/'.$file;
		$result = fopen($write, 'a');

		if (is_writable($write)) {
			if (!$handle = fopen($write, 'a')) {
				return false;
			}
			if (fwrite($result, $contents) === false) {
				return false;
			}
			fclose($result);
			return true;
		} else {
			return false;
		}
	}
}


//*** Returns the current buy-it-now price, adjusted according to config settings
function binPrice($auction_id, $buy_now_price, $user_id = 0) {
	global $config;
	
	//see if buy it now is disabled for this item
	if ($buy_now_price==0) return 0;
	
	//calculate the adjusted buy-it-now price
	require_once('../database.php');
//	echo "sql: "."SELECT COUNT(*) FROM `bids` WHERE `auction_id`=$auction_id AND `user_id`=$user_id"." <br/>\n";
	
	
	if (isset($config['App']['buyNow']['bid_discount']) && $config['App']['buyNow']['bid_discount']===true) {
		//see if this user has bought it before, if so no discount will be offered
		list($prevBought)=mysql_fetch_array(mysql_query("SELECT COUNT(*) 
									FROM `auctions`
									WHERE `deleted`=0
									   AND `winner_id`='$user_id'
									   AND `closed_status`=2
									   AND `parent_id`='$auction_id'"));
		
		if ($prevBought>0)
		{
			$discount=0;
		}
		elseif($user_id>0)
		{
			list($bid_count)=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `bids` WHERE `auction_id`=$auction_id AND `user_id`=$user_id"));
			$discount=($bid_count*$config['App']['buyNow']['bid_price']);
		}
		
		if ($discount>=$buy_now_price) {
			//don't know why this would ever happen, but good to have a handler for it
			return 0.01;
		} else {
			return  $buy_now_price - $discount;
		}
	} else {
		return $buy_now_price;
	}
}


//*** Shortens the username for getstatus.php output
function shortenName($name, $maxChars=10, $ellipse='...') {
	if (strlen($name) - strlen($ellipse)>$maxChars) {
		return substr($name, 0, $maxChars).$ellipse;
	} else {
		return $name;
	}
}
?>