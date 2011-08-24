<?php
class Autobid extends AppModel {

	var $name = 'Autobid';

	var $actsAs = array('Containable');

	var $belongsTo = 'Auction';

	/**
	* Function to create a random autobid
	*
	* @param int Auction id
	* @param date end_time 
	* @return $data posted data
	*/
	function check($auction_id, $end_time, $data, $smartAutobids = false) {
		// lets check to see if there are no bids in the que already
		$autobid = $this->find('first', array('conditions' => array('Autobid.auction_id' => $auction_id), 'contain' => ''));
		
		if(!empty($autobid)) {
			if($autobid['Autobid']['end_time'] == $end_time) {
				if($autobid['Autobid']['deploy'] <= date('Y-m-d H:i:s')) {
					// lets place the bid!
					$this->placeAutobid($auction_id, $data, $smartAutobids, time() - $end_time);
					$this->deleteAll(array('auction_id' => $auction_id));
					$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => '', 'fields' => 'end_time'));
					$end_time = $auction['Auction']['end_time'];
				} else {
					return false;
				}
			} else {
				$this->deleteAll(array('auction_id' => $auction_id));
			}
		}
		
		$str_end_time = strtotime($end_time);
		$timeDifference = $str_end_time - time();
		$randomTime = rand(3, $timeDifference);
			
		$newAutobid['Autobid']['deploy'] = date('Y-m-d H:i:s', $str_end_time - $randomTime);
		$newAutobid['Autobid']['end_time'] = $end_time;
		$newAutobid['Autobid']['auction_id'] = $auction_id;
		$this->create();	
		$this->save($newAutobid);
		
		return $data;
	}
	
	/**
	* Function used to place an autobid
	*
	* @param INT $id
	* @return this bid info
	*/
	function placeAutobid($id, $data = array(), $smartAutobids = false, $timeEnding = 0) {		
		$data['auction_id']	= $id;

		$bid = $this->Auction->Bid->lastBid($id);	

		if(!empty($bid)) {
			if($smartAutobids == true) {
				if(rand(1, 12) < 12) {
					// we are selecing a bidder who has already bid
					$user = $this->Auction->Smartbid->find('first', array('contain' => 'User', 'conditions' => array('Smartbid.auction_id' => $id, 'Smartbid.user_id <>' => $bid['user_id']), 'order' => 'rand()'));
					$data['user_id']	= $user['User']['id'];		
				}
			} 
			if(empty($user)) {
				// Updated: 18/2/2009 - this has been updated to "group" bids.  It will take a bidder that has not bid for a while.
				$user = $this->Auction->Winner->find('first', array('recursive' => -1, 'conditions' => array('Winner.active' => 1, 'Winner.autobidder' => 1, 'Winner.id <>' => $bid['user_id']), 'order' => array('Winner.modified' => 'asc')));
				$data['user_id']	= $user['Winner']['id'];	
			}				
		} else {
			$user = $this->Auction->Winner->find('first', array('recursive' => -1, 'conditions' => array('Winner.active' => 1, 'Winner.autobidder' => 1), 'order' => 'rand()'));
			$data['user_id']	= $user['Winner']['id'];
		}

		if(!empty($user)) {					
			// lets check to see if its a nail bitter
			$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $id), 'contain' => '', 'fields' => 'nail_bitter'));

			if(!empty($auction['Auction']['nail_bitter'])) {
				$message = __('Single Bid', true);
			} elseif(!empty($user['Smartbid']['message'])) {
				$message = $user['Smartbid']['message'];
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
			$auction = $this->Auction->bid($data, true, $message);

			if($smartAutobids == true && !empty($auction['Bid']['user_id'])) {
				// lets give history to the fact that the autobidder has bid on this auction
				$this->Auction->Smartbid->bidPlaced($auction['Auction']['id'], $auction['Bid']['user_id'], $auction['Bid']['description']);
			}
		} else {
			return null;
		}
	}
}
?>