<?php
function get($name = null, $auction_id = null, $cache = true) {
	global $config;

	if($cache == true) {
		$setting = cacheRead('cake_'.$name);

		if(!empty($setting)) {
			return $setting;
		} else {
			$setting = mysql_fetch_array(mysql_query("SELECT value FROM settings WHERE name = '$name'"), MYSQL_ASSOC);
			if(!empty($setting)) {
				return $setting['value'];
			} else {
				return false;
			}
		}
	}

	// then this is a dynamic setting that we are getting
	if($config['App']['bidIncrements'] == 'single') {
		$settings = mysql_fetch_array(mysql_query("SELECT * FROM setting_increments"), MYSQL_ASSOC);
	} elseif($config['App']['bidIncrements'] == 'dynamic') {
		$auction = mysql_fetch_array(mysql_query("SELECT price FROM auctions where id = ".$auction_id), MYSQL_ASSOC);
		$price = $auction['price'];
		$settings = mysql_fetch_array(mysql_query("SELECT * FROM setting_increments where lower_price <= $price AND upper_price > $price and `product_id`=0"), MYSQL_ASSOC);

		if(empty($settings)) {
			// lets check to see if the price is in the upper region
			$settings = mysql_fetch_array(mysql_query("SELECT * FROM setting_increments where lower_price <= $price AND upper_price = '0.00'  and `product_id`=0"), MYSQL_ASSOC);
		}

		if(empty($settings)) {
			// lets check to see if the price is in the lower region
			$settings = mysql_fetch_array(mysql_query("SELECT * FROM setting_increments where lower_price = '0.00' AND upper_price > $price  and `product_id`=0"), MYSQL_ASSOC);
		}

		if(empty($settings)) {
			// finally if it fits none of them for some strange reason
			$settings = mysql_fetch_array(mysql_query("SELECT * FROM setting_increments"), MYSQL_ASSOC);
		}
	} elseif($config['App']['bidIncrements'] == 'product') {
		$auction = mysql_fetch_array(mysql_query("SELECT product_id FROM auctions where id = ".$auction_id), MYSQL_ASSOC);
		$settings = mysql_fetch_array(mysql_query("SELECT * FROM setting_increments WHERE product_id = ".$auction['product_id']), MYSQL_ASSOC);
	}

	return $settings[$name];
}

function checkCanClose($id, $isPeakNow, $timeCheck = true) {
	global $config;

	$auction = mysql_fetch_array(mysql_query("SELECT a.id, a.end_time, a.max_end_time, a.max_end, a.peak_only, a.price, a.minimum_price, a.reverse, a.free, a.autobids, a.leader_id, p.autobid, p.autobid_limit, a.random FROM auctions a, products p WHERE a.deleted=0 AND a.id = $id AND a.product_id = p.id"), MYSQL_ASSOC);

	if ($config['App']['autobids']===0) {
		$auction['autobid']=0;
	}
	
	if($timeCheck == true) {
		// lets check to see if the end_max_time is on, and if so we HAVE to close the auction
		if(!empty($auction['max_end'])) {
			if(strtotime($auction['max_end_time']) < time()) {
				logIt($auction['id'].' hit max_end_time');
				return true;
			}
		}

		// lets check if it has actually expired
		if(strtotime($auction['end_time']) > time()) {
			logIt($auction['id'].' expired');
			return false;
		}
	}

	// only close a peak auction during the peak times
	if($auction['peak_only'] == 1 && !$isPeakNow) {
		logIt($auction['id'].' not peak');
		return false;
	}
	
	if ($config['App']['autobids']) {
		// lets adjust the autobid limit
		$auction['autobid_limit'] = adjustAutobidLimit($auction['id'], $auction['autobid_limit'], $auction['random']);
		
		// now lets make sure the minimum price has been met checking on the autobid limit too
		if ($auction['reverse']) {
			if ($auction['autobid'] == 1 && ($auction['price'] > $auction['minimum_price'])) {
				if($auction['autobid_limit'] > 0) {
					if($auction['autobids'] <= $auction['autobid_limit']) {
						logIt($auction['id'].' can still autobid (reverse)');
						return false;
					}
				} else {
					logIt($auction['id'].' hasnt hit autobid min (reverse)');
					return false;
				}
			}	
		} else {
			if ($auction['autobid'] == 1 && ($auction['price'] < $auction['minimum_price'])) {
				if($auction['autobid_limit'] > 0) {
					if($auction['autobids'] <= $auction['autobid_limit']) {
						logIt($auction['id'].' can still autobid');
						return false;
					}
				} else {
					logIt($auction['id'].' hasnt hit autobid min');
					return false;
				}
			}
		}
	}
	
	$latest_bid = lastBid($auction['id']);
	if(empty($latest_bid)) {
		$latest_bid['user_id'] = 0;
	} else {
		// this is silly, but needed because sometimes the leader_id !== the highest bidder.
		// this is most likely from a bug but the bug hasn't been worked out yet.
		if($latest_bid['user_id'] !== $auction['leader_id']) {
			logIt($auction['id'].' descrepency user_id!=leader_id');
			return false;
		}
	}

	
	//bid butlers can't extend an auction that's hit zero
	if (!($auction['reverse'] && $auction['price']<=0)) {
		// finally lets check that there are no bid butlers left to be placed
		if($config['App']['bidButlerType'] == 'simple') {
			$sql = mysql_query("SELECT user_id FROM bidbutlers WHERE bids > 0 AND auction_id = ".$auction['id']." AND user_id != ".$latest_bid['user_id']);
		} else {
			if ($auction['reverse']) {
				//reverse auction lacks max price check
				$sql = mysql_query("SELECT user_id FROM bidbutlers WHERE bids > 0 AND minimum_price >= '".$auction['price']."' AND auction_id = ".$auction['id']." AND user_id != ".$latest_bid['user_id']);
			} else {
				$sql = mysql_query("SELECT user_id FROM bidbutlers WHERE bids > 0 AND minimum_price <= '".$auction['price']."' AND maximum_price > '".$auction['price']."' AND auction_id = ".$auction['id']." AND user_id != ".$latest_bid['user_id']);
			}
		}
		
		$totalRows = mysql_num_rows($sql);
		logIt($auction['id'].'; '.$totalRows.' bidbuddies waiting, not closing yet');
		if($totalRows > 0) {
			if($config['App']['bidButlerType'] == 'advanced') {
				return false;
			} else {
				// go through each of them and make sure that they have bids left in their account
				while($bidbutler = mysql_fetch_array($sql, MYSQL_ASSOC)) {
					if(!empty($config['App']['limits']['active'])) {
						$limits_exceeded = limitsCanBid($auction['id'], $bidbutler['user_id'], $auction['product_id'], false);
						if($limits_exceeded == false) {
							//this bidbutler has limits exceeded, don't let it block auction close
							logIt($auction['id'].' '.$data['user_id'].' - bid butler invalid, limits exceeded');
							continue;
						}
					}
					
					if(balance($bidbutler['user_id']) > 0) {
						logIt('bidbuddy '.$bidbutler['id'].' has bids left');
						return false;
					}
				}
			}
		}
	} else {
		logIt($auction['id']. " reverse auction hit 0");
		return true;
	}

	return true;
}

function lastBid($auction_id = null) {
	// Use contain user only and get bid.auction_id instead of auction.id
	// cause it needs the auction included in result array
	$res=mysql_query("SELECT 	id, 
					debit, 
					description, 
					user_id, 
					created 
					FROM bids
					WHERE auction_id = $auction_id 
					ORDER BY id DESC
					LIMIT 1");
	$lastBid = mysql_fetch_array($res, MYSQL_ASSOC);
	mysql_free_result($res);
	
								
	$bid = array();

	if(!empty($lastBid)) {
		//*** Performance is better to do a simple query here than to do a JOIN in the query above
		$res=mysql_query("SELECT 	autobidder,
						username
						FROM users
						WHERE id='".$lastBid['user_id']."'");
		$user=mysql_fetch_array($res, MYSQL_ASSOC);
		mysql_free_result($res);
		
		
		$bid = array(
			'debit'       => $lastBid['debit'],
			'created'     => $lastBid['created'],
			'username'    => $user['username'],
			'description' => $lastBid['description'],
			'user_id'     => $lastBid['user_id'],
			'autobidder'  => $user['autobidder']
		);
	}
	return $bid;
}

function check($auction_id, $end_time, $data, $smartAutobids = false) {
	// lets check to see if there are no bids in the que already
	$autobid = mysql_fetch_array(mysql_query("SELECT * FROM autobids WHERE auction_id = $auction_id"), MYSQL_ASSOC);

	if(!empty($autobid)) {
		if($autobid['end_time'] == $end_time) {
			if($autobid['deploy'] <= date('Y-m-d H:i:s')) {
				// lets place the bid!
				placeAutobid($auction_id, $data, $smartAutobids, time() - $end_time);
				mysql_query("DELETE FROM autobids WHERE auction_id = $auction_id");

				$auction = mysql_fetch_array(mysql_query("SELECT end_time FROM auctions WHERE id = $auction_id"), MYSQL_ASSOC);
				$end_time = $auction['end_time'];
			} else {
				return false;
			}
		} else {
			mysql_query("DELETE FROM autobids WHERE auction_id = $auction_id");
		}
	}

	$str_end_time = strtotime($end_time);
	$timeDifference = $str_end_time - time();
	$randomTime = rand(3, $timeDifference);
	$deploy = date('Y-m-d H:i:s', $str_end_time - $randomTime);

	mysql_query("INSERT INTO autobids (deploy, end_time, auction_id) VALUES ('$deploy', '$end_time', '$auction_id')");

	return $data;
}

function placeAutobid($id, $data = array(), $smartAutobids = false, $timeEnding = 0) {
	
	global $config;
	
	if ($config['App']['autobids']==false) {
		return null;	
	}
	
	
	$data['auction_id']	= $id;

	$bid = lastBid($id);

	if(!empty($bid)) {
		$bidder = $bid['user_id'];

		if($smartAutobids == true) {
			if(mt_rand(1, 12) < 12) {
				// we are selecing a bidder who has already bid
				$user = mysql_fetch_array(mysql_query("SELECT u.id, s.message FROM smartbids s, users u WHERE s.auction_id = $id AND s.user_id != $bidder AND s.user_id = u.id ORDER BY rand()"), MYSQL_ASSOC);
				$data['user_id']	= $user['id'];
			}
		}

		if(empty($user)) {
			// Updated: 18/2/2009 - this has been updated to "group" bids.  It will take a bidder that has not bid for a while.
			$user = mysql_fetch_array(mysql_query("SELECT id FROM users WHERE active = 1 AND autobidder = 1 AND id != $bidder ORDER BY modified asc"), MYSQL_ASSOC);
			$data['user_id']	= $user['id'];
		}
	} else {
		$user = mysql_fetch_array(mysql_query("SELECT id FROM users WHERE active = 1 AND autobidder = 1 ORDER BY modified asc"), MYSQL_ASSOC);
		$data['user_id']	= $user['id'];
	}

	if(!empty($user)) {
		// lets check to see if its a nail bitter
		$auction = mysql_fetch_array(mysql_query("SELECT nail_bitter FROM auctions WHERE id = $id"), MYSQL_ASSOC);

		if(!empty($auction['nail_bitter'])) {
			$message = __('Single Bid', true);
		} elseif(!empty($user['message'])) {
			$message = $user['message'];
		} else {
			// we need to work out a message
			if($data['bid_butler_time'] <= $timeEnding) {
				if(rand(1, 2) == 2) {
					$message = __('Bid Buddy', true);
				} else {
					$message = __('Single Bid', true);
				}
			} else {
				$message = __('Single Bid', true);
			}
		}

		// Bid the auction
		$auction = bid($data, true, $message);

		if($smartAutobids == true && !empty($auction['Bid']['user_id'])) {
			// lets give history to the fact that the autobidder has bid on this auction
			bidPlaced($auction['Auction']['id'], $auction['Bid']['user_id'], $auction['Bid']['description']);
		}
	} else {
		return null;
	}
}

function bid($data = array(), $autobid = false, $bid_description = null) {
	global $config;

	$canBid = true;
	$message = '';
	$flash = '';

	if(empty($data['user_id'])) {
		$message = __('You are not logged in.', true);
		$canBid = false;
		$data['user_id'] = 0;
	}

	// Get the auctions
	$auction_id = $data['auction_id'];
	$auction = mysql_fetch_array(mysql_query("SELECT id, product_id, start_time, end_time, price, peak_only, closed, minimum_price, autobids, max_end, max_end_time, penny, free, reverse, beginner FROM auctions WHERE deleted=0 AND id = $auction_id"), MYSQL_ASSOC);

	if ($auction['reverse'] && $autobid) {
		//on reverse auctions, don't autobid below minimum_price
		if ($auction['price']<=$auction['minimum_price']) {
			logIt("bid($auction_id): reverse, autobid below minimum");
			return true;
		}
	}
	
	if(!empty($auction)){
		// check to see if this is a free auction
		if(!empty($auction['free'])) {
			$data['bid_debit'] = 0;
		}

		// see if we've exceeded our bidding limit
		if(!empty($config['App']['limits']['active'])) {
			$limits_exceeded = limitsCanBid($data['auction_id'], $data['user_id'], $auction['product_id'], $autobid);
			if($limits_exceeded == false) {
				logIt($auction['id'].' '.$data['user_id'].' - cantBid: exceeded bidding limit');
				$message = __('You cannot bid on this auction as your have exceeded your bidding limit.', true);
				$canBid = false;
			}
		}
		
		// beginner auctions only let new users bid
		if ($auction['beginner'] && !$autobid) {
			$res=mysql_query("SELECT COUNT(*) FROM `auctions` WHERE `winner_id`='".intval($data['user_id'])."'");
			$won_auctions_count=mysql_result($res,0,0);
			mysql_free_result($res);
			
			if ($won_auctions_count>0) {
				logIt($auction['id'].' '.$data['user_id'].' - cantBid: beginner only');
				$message = __('Sorry, this auction is only open to new bidders.', true);
				$canBid = false;
			}
			
		}

		// Check if the auction has ended - this only applies to NON autobidders
		if((!empty($auction['closed']) || strtotime($auction['end_time']) <= time()) && $autobid == false && !$data['bid_butler']) {
			$message = __('Auction has been closed', true);
			logIt($auction['id'].' '.$data['user_id'].' - cantBid: already closed');
			$canBid = false;
		}
		
		// Don't let reverse auctions drop below 0.00
		if ($auction['reverse']==1 && $auction['price']<=0) {
			$message = __('Auction has been closed', true);
			logIt($auction['id'].' '.$data['user_id'].' - cantBid: already closed (reverseneg)');
			$canBid = false;
		}

		// Check if the auction has been not started yet
		if(!empty($auction['start_time'])) {
			if(strtotime($auction['start_time']) > time()){
				$message = __('Auction has not started yet', true);
				logIt($auction['id'].' '.$data['user_id'].' - cantBid: notStarted');
				$canBid = false;
			}
		}

		// Check if the auction is peak only and if the now is peak time
		if(!empty($auction['peak_only'])){
			if(empty($data['isPeakNow'])){
				$message = __('This is a peak auction', true);
				logIt($auction['id'].' '.$data['user_id'].' - cantBid: peakOnly');
				$canBid = false;
			}
		}

		// Get user balance
		if($autobid == true || ($config['App']['bidButlerType'] == 'advanced' && !empty($data['bid_butler']))) {
			$balance = $data['bid_debit'];
		} else {
			$balance = balance($data['user_id']);
		}

		$latest_bid = lastBid($data['auction_id']);
		if(!empty($latest_bid) && !isset($data['ignore_latest_bid']) && $latest_bid['user_id'] == $data['user_id']) {
			$message = __('You cannot bid as you are already the latest bidder', true);
			logIt($auction['id'].' '.$data['user_id'].' - cantBid: alreadylatest');
			$canBid = false;
		}

		if($canBid == true) {
			// Checking if user has enough bid to place
			if($balance >= $data['bid_debit']) {
				// Formatting auction time and price increment
				if(!empty($auction['penny'])) {
					if ($auction['reverse']) {
						$auction['price'] 	-= 0.01;
						logIt('Bid is: penny reverse');
					} else {
						$auction['price'] 	+= 0.01;
						logIt('Bid is: penny');
					}
				} else {
					if ($auction['reverse']) {
						logIt('Bid is: reg reverse');
						$auction['price'] 	-= $data['price_increment'];
					} else {
						logIt('Bid is: reg');
						$auction['price'] 	+= $data['price_increment'];
					}
				}
				
				//*** don't let bid drop below 0.00
				if ($auction['price']<0) {
					logIt('Cantdrop triggered :S');
					$auction['price']=0;
				}

                                 
				//*** if maxCounterTime is turned on, don't allow timer to get past end_time + X seconds 
				$invoke_max_time=$max_time_on=false; 
				if(!empty($config['App']['maxCounterTime']) && $config['App']['maxCounterTime']>0) { 
				  $maxCounterTime = time() + intval($config['App']['maxCounterTime']); 
				   
				  if(strtotime($auction['end_time']) < $maxCounterTime) { 
				          $invoke_max_time=true; 
				  } 
				   
				  $max_time_on=true; 
				} 
				
				//*** Only increment time if maxCounterTime is off or maxCounterTime is on and we're below X seconds 
				if (!$max_time_on or ($max_time_on && $invoke_max_time)) { 
					if(!empty($config['App']['delayedStart'])) { 
						// lets see if any bids have been placed on the auction yet 
						if(empty($latest_bid)) {
							// lets work out the difference between the start time and now 
							$seconds = time() - strtotime($auction['start_time']); 
							$auction['end_time'] = date('Y-m-d H:i:s', strtotime($auction['end_time']) + $seconds); 
						} else {
							//*** bump up end_time 
							$auction['end_time'] = date('Y-m-d H:i:s', strtotime($auction['end_time']) + $data['time_increment']); 
						}
					} else {
						$auction['end_time'] = date('Y-m-d H:i:s', strtotime($auction['end_time']) + $data['time_increment']);
					}
				}
				
				// lets make sure the auction time is not less than now
				if(strtotime($auction['end_time']) < time()) {
					$auction['end_time'] = date('Y-m-d H:i:s', time() + $data['time_increment']);
				}

				// lets check the max end time to see if the end_time is greater than the max_end_time
				if(!empty($auction['max_end'])) {
					if(strtotime($auction['end_time']) > strtotime($auction['max_end_time'])) {
						$auction['end_time'] = $auction['max_end_time'];
					}
				}

					
				//*** maxCounterTime needs to be invoked 
				if ($invoke_max_time) { 					
					if(strtotime($auction['end_time']) > $maxCounterTime) {
						logIt($auction['id'].' bid exceeded maxCounterTime, bumping down');
						$auction['end_time'] = date('Y-m-d H:i:s', $maxCounterTime);
					}
				}
				
				// lets extend the minimum price if its an auto bidder
				if($autobid == true) {
					if (!$auction['reverse']) {
						if(!empty($auction['Auction']['penny'])) {
							if ($auction['reverse']) {
								logIt('Bid is: auto penny reverse');
								$auction['minimum_price'] -= 0.01;
							} else {
								logIt('Bid is: auto penny');
								$auction['minimum_price'] += 0.01;
							}
						} else {
							if ($auction['reverse']) {
								logIt('Bid is: auto reg reverse');
								$auction['minimum_price'] -= $data['price_increment'];
							} else {
								logIt('Bid is: auto reg');
								$auction['minimum_price'] += $data['price_increment'];
							}
						}
					}

					$auction['autobids'] += 1;
				} else {
					$auction['autobids'] = 0;
				}

				// Formatting user bid transaction
				$bid['user_id']    = $data['user_id'];
				$bid['auction_id'] = $auction['id'];
				$bid['credit']     = 0;
				$bid['debit'] = $data['bid_debit'];

				// Insert proper description, bid or bidbutler
				if(!empty($bid_description)) {
					$bid['description'] = $bid_description;
				} elseif(!empty($data['bid_butler'])){
					$bid['description'] = __('Bid Buddy', true);
				} else {
					$bid['description'] = __('Single Bid', true);
				}

				// Check if it's bidbutler call
				if(!empty($data['bid_butler'])) {
					logIt('Bid is: bidbutler');
					$bidbutler = mysql_fetch_array(mysql_query("SELECT * FROM bidbutlers WHERE id = ".$data['bid_butler']), MYSQL_ASSOC);

					// If bidbutler found
					if(!empty($bidbutler)){
						if($bidbutler['bids'] >= $data['bid_debit']) {
							// Decrease the bid butler bids
							$bidbutler['bids'] -= $data['bid_debit'];

							// Save it
							mysql_query("UPDATE bidbutlers SET bids = '".$bidbutler['bids']."', modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$bidbutler['id']);
						} else {
							// Get out of here, the bids on bidbutler was empty
							logIt($auction['id'].' '.$data['user_id'].' - cantBid: bidbutler depleted');
							return $auction;
						}
					}
				}

				logIt('Auction new leader: '.$data['user_id']);
				$auction['leader_id'] = $data['user_id'];

				if ($auction['reverse'] && $auction['price']==0) {
					//reverse auction just hit zero, make it close
					$auction['end_time'] = date('Y-m-d H:i:s');
					logIt('Auction hit zero: closing!');
				}
				
				// Save the auction and bid
				$success = mysql_query("UPDATE auctions SET end_time = '".$auction['end_time']."', price = '".$auction['price']."', minimum_price = '".$auction['minimum_price']."', autobids = ".$auction['autobids'].", leader_id = ".$auction['leader_id'].", modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$auction['id']);

				if($success == 1) {
					logIt('User id '.$bid['user_id']." bidding on auction id ".$bid['auction_id']);
					mysql_query("INSERT INTO bids (user_id, auction_id, description, credit, debit, created, modified) VALUES ('".$bid['user_id']."', '".$bid['auction_id']."', '".$bid['description']."', '".$bid['credit']."', '".$bid['debit']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
					if($config['App']['simpleBids'] == true) {
						$user = mysql_fetch_array(mysql_query("SELECT bid_balance FROM users WHERE id = ".$data['user_id']), MYSQL_ASSOC);
						$user['bid_balance'] -= $bid['debit'];
						mysql_query("UPDATE users SET bid_balance = '".$user['bid_balance']."', modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$data['user_id']);
					} elseif($autobid == true) {
						mysql_query("UPDATE users SET modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$data['user_id']);
					}
					// this is normally in afterSave but need this here
					clearCache($auction['id'], $data['user_id']);

					$message = __('Your bid was placed', true);
				} else {
					logIt($auction['id'].' '.$data['user_id'].' - cantBid: unknown bid error: '.mysql_error());
					$message = __('There was a problem placing the bid please contact us', true);
				}

				// Adding flash message if enabled
				if(!empty($config['App']['flashMessage'])) {
					// New flash message
					if(!empty($data['bid_butler'])){
						if(!empty($data['bid_butler_count'])){
							$flash = sprintf(__('%d Bid Butler + %s + %s seconds', true), $data['bid_butler_count'], currency($data['price_increment'], $config['App']['currency']), $data['time_increment']);
						}else{
							$flash = sprintf(__('1 Bid Butler + %s + %s seconds', true), currency($data['price_increment'], $config['App']['currency']), $data['time_increment']);
						}
					}else{
						$flash = sprintf(__('1 Single bid + %s + %s seconds', true), currency($data['price_increment'], $config['App']['currency']), $data['time_increment']);
					}
					logIt('Addflash: '.$flash);
				}

				$auction['Auction']['success'] = true;
				$auction['Bid']['description'] = $bid['description'];
				$auction['Bid']['user_id'] = $bid['user_id'];

				// lets add in the bid information for smartBids - we need this
				$result['Bid'] = $bid;
			} else {
				$message = __('You have no more bids in your account', true);
				logIt($auction['id'].' '.$data['user_id'].' - cantBid: account bids depleted');
			}
		}

		$result['Auction']['id']      = $auction['id'];
		$result['Auction']['message'] = $message;
		$result['Auction']['element'] = 'auction_'.$auction['id'];

		if(!empty($config['App']['flashMessage'])){
			// this needs to be updated still
			$result['Auction']['flash']  = $flash;
			$auctionMessage = mysql_fetch_array(mysql_query("SELECT * FROM messages WHERE auction_id = ".$auction['id']), MYSQL_ASSOC);

			if(!empty($auctionMessage['id'])){
				mysql_query("UPDATE messages SET message = '".$flash."' WHERE auction_id = '".$auction['id']."'");
				logIt('Message update ');
			}else{
				logIt('Message insert ');
				mysql_query("INSERT INTO messages (auction_id, message, created, modified) VALUES ('".$auction['id']."', '".$flash."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
			}
		}

		// now lets refund any bid credits not used before returning the data IF advanced mode is on
		if($config['App']['bidButlerType'] == 'advanced') {
			refundBidButlers($auction['id'], $auction['price']);
		}

 		// finally, lets just check that there was no double bid placed and if so delete it
        	if (!isset($data['ignore_latest_bid'])) {
			fixDoubleBids($auction['id']);
		}
		return $result;
	} else {
		return false;
	}
}

function fixDoubleBids($auction_id = null) {
	$bidHistories = mysql_query("SELECT * FROM bids WHERE credit = 0 AND auction_id = ".$auction_id." ORDER BY id DESC LIMIT 2");
	$totalBids    = mysql_num_rows($bidHistories);

	if($totalBids > 0) {
		$user_id = 0;
		while($bid = mysql_fetch_array($bidHistories, MYSQL_ASSOC)) {
			if(empty($user_id)) {
				// this is the first bid checking
				$user_id = $bid['user_id'];
			} else {
				// lets compare this to the second bid
				if($bid['user_id'] == $user_id) {
					logIt('Triggering fixDoubleBids() on auctionid: '.$auction_id);
					mysql_query("DELETE FROM bids WHERE {$bid['id']} = id");
					clearCache($auction_id, $bid['user_id']);
				}
			}
		}
	}
}

function balance($user_id) {
	global $config;

	if(!empty($config['App']['simpleBids']) && $config['App']['simpleBids'] == true) {
		$user = mysql_fetch_array(mysql_query("SELECT bid_balance FROM users WHERE id = $user_id"), MYSQL_ASSOC);
		return $user['bid_balance'];
	} else {
		$credit = mysql_fetch_array(mysql_query("SELECT SUM(credit) as credit FROM bids WHERE user_id = $user_id"), MYSQL_ASSOC);
		$debit = mysql_fetch_array(mysql_query("SELECT SUM(debit) as debit FROM bids WHERE user_id = $user_id"), MYSQL_ASSOC);

		return $credit['credit'] - $debit['debit'];
	}
}

function bidPlaced($auction_id = null, $user_id = null, $message = 'Single Bid') {
	$smartbid = mysql_fetch_array(mysql_query("SELECT * FROM smartbids WHERE auction_id = $auction_id AND user_id = $user_id"), MYSQL_ASSOC);

	if(empty($smartbid)) {
		mysql_query("INSERT INTO smartbids (auction_id, user_id, message) VALUES ('$auction_id', '$user_id', '$message')");
	} elseif(rand(1, 10) == 10) {
		// lets delete a bidder every so often
		mysql_query("DELETE FROM smartbids WHERE id = ".$smartbid['id']);
	}
}

function limitsCanBid($auction_id = null, $user_id = null, $product_id = null, $autobidder = false) {
	global $config;

	if($autobidder == true) {
		return true;
	}

	$expiry_date = date('Y-m-d H:i:s', time() - ($config['App']['limits']['expiry'] * 24 * 60 * 60));

	$sql = mysql_query("SELECT 	`leader_id`
						FROM `auctions`
						WHERE `leader_id` = '$user_id'
						   AND end_time > '$expiry_date'");
	$total   = mysql_num_rows($sql);

	if($total >= $config['App']['limits']['limit']) {
		return false;
	}

	// now lets check the individual product
	$sql = mysql_query("SELECT 	`leader_id` 
						FROM auctions a, 
						   products p, 
						   limits l 
						WHERE a.product_id = $product_id 
						   AND a.leader_id = $user_id 
						   AND end_time > '$expiry_date' 
						   AND p.id = a.product_id 
						   AND l.id = p.limit_id");
	$productTotal   = mysql_num_rows($sql);

	$limit = mysql_fetch_array(mysql_query("SELECT 	l.limit, 
									l.id 
									FROM limits l,
									   products p 
									WHERE l.id = p.limit_id 
									   AND p.id = $product_id"), MYSQL_ASSOC);

	if(empty($limit['limit'])) {
		return true;
	}

	if($productTotal >= $limit['limit']) {
		return false;
	} else {
		return true;
	}
}

function refundBidButlers($auction_id = null, $price = null) {
	/*if(!empty($price)) {
		$conditions = "auction_id = $auction_id AND bids > 0 AND maximum_price < '$price'";
	} else {
		$conditions = "auction_id = $auction_id AND bids > 0";
	}

	$sql = mysql_query("SELECT * FROM bidbutlers WHERE $conditions");
	$totalRows   = mysql_num_rows($sql);

	if($totalRows > 0) {
		while($bidbutler = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			$bid['user_id'] 	= $bidbutler['user_id'];
			$bid['description'] = __('Bid Butler Refunded Bids', true);
			$bid['credit']      = $bidbutler['bids'];

			mysql_query("INSERT INTO bids (user_id, description, credit, created, modified) VALUES ('".$bid['user_id']."', '".$bid['description']."', '".$bid['credit']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");

			mysql_query("UPDATE bidbutlers SET bids = 0, modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$bidbutler['id']);
		}
	}*/
}

function closeAuction($auction = array()) {
	global $config;
	
	// it is OK to close this auction
	mysql_query("UPDATE auctions SET closed = 1, end_time = '".date('Y-m-d H:i:s')."', modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$auction['id']);

	// now sleep the auction for a quarter of a second to give it time for any final bids to process
	usleep(250000);

	// lets get the winner
	$bid = lastBid($auction['id']);

	if(!empty($bid)) {
		if($bid['autobidder'] == 0 && $auction['price']>0) {
			// add the auction_id into auction_emails for sending
			mysql_query("INSERT INTO auction_emails (auction_id) VALUES ('".$auction['id']."')");
		}

		mysql_query("UPDATE auctions SET winner_id = ".$bid['user_id'].", status_id = 1, modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$auction['id']);
		
	}

	clearCache($auction['id']);

	// lets remove any autobids from the system
	mysql_query("DELETE FROM autobids WHERE auction_id = ".$auction['id']);

	// and lets do the same for any smartbids
	if($config['App']['smartAutobids'] == true) {
		mysql_query("DELETE FROM smartbids WHERE auction_id = ".$auction['id']);
	}

	// and lets see if the credit system is on and add credits to the users that didn't win
	if(!empty($config['App']['credits']['active'])) {
		$losersSql = mysql_query("SELECT 	DISTINCT b.user_id 
							FROM bids b, users u 
							WHERE b.auction_id = ".$auction['id']." 
							   AND u.autobidder = 0 
							   AND u.id != '".$auction['winner_id']."' 
							   AND b.debit > 0 AND u.id = b.user_id");
		$totalLosers   = mysql_num_rows($losersSql);
		if($totalLosers > 0) {
			while($loser = mysql_fetch_array($losersSql, MYSQL_ASSOC)) {
				$bidsSql = mysql_query("SELECT 	id 
								FROM bids 
								WHERE auction_id = ".$auction['id']." 
								   AND debit > 0 
								   AND user_id = ".$loser['user_id']);
				
				$numberOfBids   = mysql_num_rows($bidsSql);
				if($numberOfBids > 0) {
					$credit['user_id'] = $loser['user_id'];
					$credit['auction_id'] = $auction['id'];
					$credit['credit'] = $numberOfBids * $config['App']['credits']['value'];

					mysql_query("INSERT INTO credits (user_id, auction_id, credit, created, modified) VALUES ('".$credit['user_id']."', '".$credit['auction_id']."', '".$credit['credit']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
				}
			}
		}
	}

	// now lets refund any bid credits not used before returning the data IF advanced mode is on
	if($config['App']['bidButlerType'] == 'advanced') {
		refundBidButlers($auction['id']);
	}

	// now check if we need to relist this auction
	if(!empty($auction['autolist'])) {
		$product = mysql_fetch_array(mysql_query("SELECT stock, stock_number FROM products WHERE id = ".$auction['product_id']), MYSQL_ASSOC);
		if(!empty($product['stock'])) {
			if($product['stock_number'] > 0) {
				if($bid['autobidder'] == 0) {
				// lets make the stock number smaller
					$product['stock_number'] -= 1;
					mysql_query("UPDATE products SET stock_number = ".$product['stock_number'].", modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$product['id']);
				}

				autoRelist($auction);
			}
		} else {
			autoRelist($auction);
		}
	}
}

//*** Called by bidbutler cronjob, tests if it's OK to invoke a bid buddy
function canBidBuddy($bidbutler) {
	global $config;
	
	if ($bidbutler['reverse']) {
		//reverse auctions work differently
		if ($bidbutler['price'] <= $bidbutler['minimum_price'] ||
				   $config['App']['bidButlerType'] == 'simple' ||
				   $bidbutler['fixed'] == 1) {
			logIt($bidbutler['auction_id']. '; canBidBuddy: true');
			return true;
		}
				   
	} else {
		//standard auction
		
		if ($bidbutler['price'] >= $bidbutler['minimum_price'] &&
			   ($bidbutler['price'] < $bidbutler['maximum_price']) ||
			   $config['App']['bidButlerType'] == 'simple' ||
			   $bidbutler['fixed'] == 1) {
			logIt($bidbutler['auction_id']. '; canBidBuddy: true');
			return true;
		}
	}
}

function autorelist($auction = array()) {
	// lets make sure there are no other active auctions with this product_id
	$productSql = mysql_query("SELECT id FROM auctions WHERE deleted = 0 AND closed = 0 AND active = 1 AND product_id = ".$auction['product_id']);
	if(mysql_num_rows($productSql) == 0) {
		// check for a delayed start time
		$delayed_start = get('autolist_delay_time');

		if(!empty($delayed_start)) {
			$auction['start_time'] = date('Y-m-d H:i:s', time() + $delayed_start * 60);
			$auction['end_time'] = date('Y-m-d H:i:s', time() + (get('autolist_expire_time') + $delayed_start) * 60);
		} else {
			$auction['start_time'] = date('Y-m-d H:i:s');
			$auction['end_time'] = date('Y-m-d H:i:s', time() + get('autolist_expire_time') * 60);
		}

		if($auction['max_end_time'] < $auction['end_time']) {
			$auction['max_end_time'] = $auction['end_time'];
		}

		$product = mysql_fetch_array(mysql_query("SELECT start_price, minimum_price FROM products WHERE id = ".$auction['product_id']), MYSQL_ASSOC);

		$auction['price'] 			= $product['start_price'];
		$auction['minimum_price'] 	= $product['minimum_price'];

		mysql_query("INSERT INTO auctions (product_id, start_time, end_time, max_end, max_end_time, price, autolist, featured, peak_only, nail_bitter, penny, hidden_reserve, minimum_price, active, bid_debit, created, modified) VALUES ('".$auction['product_id']."', '".$auction['start_time']."', '".$auction['end_time']."', '".$auction['max_end']."', '".$auction['max_end_time']."', '".$auction['price']."', '".$auction['autolist']."', '".$auction['featured']."', '".$auction['peak_only']."', '".$auction['nail_bitter']."', '".$auction['penny']."', '".$auction['hidden_reserve']."', '".$auction['minimum_price']."', '".$auction['active']."', '".$auction['bid_debit']."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
	}
}

function hiddenReserve($data) {
	if(!empty($data['Bid']['user_id'])) {
		$auction = mysql_fetch_array(mysql_query("SELECT * FROM auctions WHERE id = ".$data['Bid']['auction_id']), MYSQL_ASSOC);
		if(($auction['hidden_reserve'] > 0) && ($auction['hidden_reserve'] == $auction['price'])) {
			closeAuction($auction);
			return true;
		}
	} else {
		return false;
	}
}

function clearCache($auction_id = null, $user_id = null) {
	if(!empty($auction_id)) {
		cacheDelete('cake_auction_view_'.$auction_id);
		cacheDelete('cake_auction_'.$auction_id);
		cacheDelete('cake_daemons_extend_auctions');
		cacheDelete('cake_last_bid_'.$auction_id);
	}

	if(!empty($user_id)) {
		cacheDelete('cake_bids_balance_'.$user_id);
	}
}

function adjustAutobidLimit($auction_id, $autobid_limit, $random) {
	if($random == '0.00') {
		// lets create a ranom digit
		$digit = rand(0, 100);
		$random = $digit / 100 + 1;
		mysql_query("UPDATE auctions SET random = '$random' WHERE id = $auction_id");
	}
	return (ceil($autobid_limit * $random));
}

function logIt($msg) {
	if (!defined('LOG_FILE') or !LOG_FILE) return false;
	
	$pf=fopen(LOG_FILE, 'a');
	fwrite($pf, date('Y-m-d H:i:s').' '.strtoupper($_GET['type']).': '.$msg."\n");
	fclose($pf);
}
?>