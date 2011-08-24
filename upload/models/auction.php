<?php

	class Auction extends AppModel {

		var $name = 'Auction';

		var $actsAs = array('Containable');

		var $belongsTo = array(
			'Product' => array(
				'className'  => 'Product',
				'foreignKey' => 'product_id'
			),
			'Status' => array(
				'className'  => 'Status',
				'foreignKey' => 'status_id'
			),
			'Winner' => array(
				'className'  => 'User',
				'foreignKey' => 'winner_id'
			),
			'Leader' => array(
				'className'  => 'User',
				'foreignKey' => 'leader_id'
			)
		);

		var $hasOne = array(
			'Message', 'AuctionEmail'
		);

		var $hasMany = array(
			'Bidbutler'  => array(
				'className'  => 'Bidbutler',
				'foreignKey' => 'auction_id',
				'limit'      => 10,
				'dependent'  => true
			),

			'Bid' => array(
				'className'  => 'Bid',
				'foreignKey' => 'auction_id',
				'order'      => 'Bid.id DESC',
				'limit'      => 10,
				'dependent'  => true
			),

			'Autobid' => array(
				'className'  => 'Autobid',
				'foreignKey' => 'auction_id',
				'limit'      => 10,
				'dependent'  => true
			),

			'Smartbid' => array(
				'className'  => 'Smartbid',
				'foreignKey' => 'auction_id',
				'limit'      => 10,
				'dependent'  => true
			),

			'Credit' => array(
				'className'  => 'Credit',
				'foreignKey' => 'auction_id',
				'limit'      => 10,
				'dependent'  => true
			),

			'Watchlist' => array(
				'className'  => 'Watchlist',
				'foreignKey' => 'auction_id',
				'limit' 	 => 10,
				'dependent'  => true
			),

			'Reminder'
		);


		function getExcludeIDs($auctions) {
			if (!is_array($auctions) || empty($auctions)) {
				return array();
			}
			
			$ids=array();
			foreach ($auctions as $auction) {
				$ids[]=$auction['Auction']['id'];
			}
			return $ids;
		}


		/**
		 * Function to get auctions
		 *
		 * @param array $conditions The conditions
		 * @param int $limit How many auction will be retrieved
		 * @param string $order Ordering string
		 * @return array Auctions array
		 */
		function getAuctions($conditions = null, $limit = null, $order = 'Auction.end_time DESC', $exclude = false, $folder = 'thumbs') {
			$excludeId = array();
			if(!empty($exclude)){
				foreach($exclude as $excludeAuction){
					$excludeId[] = $excludeAuction['Auction']['id'];
				}
			}

			if(!empty($conditions) && !empty($excludeId)){
				if(is_array($conditions)){
					$conditions[] = 'Auction.id NOT IN (' . implode(',', $excludeId) .')';
				}
			}

			$this->contain(array('Product' => array('Image' => 'ImageDefault', 'Limit')), 'Winner', 'Watchlist');
			$auctions = $this->find('all', array('conditions' => $conditions, 'order' => $order, 'limit' => $limit));

			// process any translations
			$auctions = $this->Product->Translation->translate($auctions);

			foreach($auctions as $key => $auction) {
				// Check if auction already started
				if(strtotime($auction['Auction']['start_time']) > time()) {
					$auctions[$key]['Auction']['isFuture'] = true;
				} else {
					$auctions[$key]['Auction']['isClosed'] = $auction['Auction']['closed'];
				}

				// Put it back into the array
				$auctions[$key]['Auction']['end_time'] = strtotime($auction['Auction']['end_time']);
				
				// Get savings
				if($auction['Product']['rrp'] > 0) {
					if(!empty($auction['Product']['fixed'])) {
						if($auction['Product']['fixed_price'] > 0) {
							$auctions[$key]['Auction']['savings']['percentage'] = round(100 - ($auction['Product']['fixed_price'] / $auction['Product']['rrp'] * 100), 2);
						} else {
							$auctions[$key]['Auction']['savings']['percentage'] = 100;
						}
							$auctions[$key]['Auction']['savings']['price']  = $auction['Product']['rrp'] - $auction['Product']['fixed_price'];
					} else {
						$auctions[$key]['Auction']['savings']['percentage'] = round(100 - ($auction['Auction']['price'] / $auction['Product']['rrp'] * 100), 2);
						$auctions[$key]['Auction']['savings']['price']      = $auction['Product']['rrp'] - $auction['Auction']['price'];
					}
				} else {
					$auctions[$key]['Auction']['savings']['percentage'] = 0;
					$auctions[$key]['Auction']['savings']['price']      = 0;
				}

				if(!empty($auction['Product']['Image'])) {
					if(!empty($auction['Product']['Image'][0]['ImageDefault'])) {
						$auctions[$key]['Auction']['image'] = 'default_images/'.Configure::read('App.serverName').'/'.$folder.'/'.$auction['Product']['Image'][0]['ImageDefault']['image'];
					} else {
						$auctions[$key]['Auction']['image'] = 'product_images/'.$folder.'/'.$auction['Product']['Image'][0]['image'];
					}
				}

				$lastBid = $this->Bid->lastBid($auction['Auction']['id']);
				if(!empty($lastBid)) {
					$auctions[$key]['LastBid'] = $lastBid;
				} else {
					$auctions[$key]['LastBid']['username'] = __('No bids placed yet', true);
				}

				$auction['Auction']['serverTimestamp'] = time();

				//$auctions[$key]['Histories'] = $this->Bid->histories($auction['Auction']['id'], $this->appConfigurations['bidHistoryLimit'], $historiesOptions);
			}

			if($limit == 1){
				if(!empty($auctions[0])){
					return $auctions[0];
				}
			}

			return $auctions;
		}

		function afterFind($results, $primary = false){
			// Parent method redefined
			$results = parent::afterFind($results, $primary);

			// Getting rate for current currency
			$rate = $this->_getRate();
			if(!empty($results)){
				// This for find('all')
				if(!empty($results[0]['Auction'])){
					// Loop over find result and convert the price with rate
					foreach($results as $key => $result){
						if(!empty($results[$key]['Auction']['price'])){
							$results[$key]['Auction']['price'] = $result['Auction']['price'] * $rate;
						}

						if(!empty($results[$key]['Auction']['minimum_price'])){
							$results[$key]['Auction']['minimum_price'] = $result['Auction']['minimum_price'] * $rate;
						}
					}

				// This for find('first')
				}elseif(!empty($results['Auction'])){
					if(!empty($results['Auction']['price'])){
						$results['Auction']['price'] = $results['Auction']['price'] * $rate;
					}

					if(!empty($results['Auction']['minimum_price'])){
						$results['Auction']['minimum_price'] = $results['Auction']['minimum_price'] * $rate;
					}
				}
			}

			// Return back the results
			return $results;
		}

		function beforeSave(){
			$this->clearCache();

			// Price currency rate revert back to application default (USD)
			// Get the rate
			$rate = 1 / $this->_getRate();

			// Convert it back to USD
			if(!empty($this->data['Auction']['price'])){
				$this->data['Auction']['price'] = $this->data['Auction']['price'] * $rate;
			}

			if(!empty($this->data['Auction']['minimum_price'])){
				$this->data['Auction']['minimum_price'] = $this->data['Auction']['minimum_price'] * $rate;
			}

			// double bid fix - if the variable double_bids_check is passed then it will check for this
			if(!empty($this->data['Auction']['double_bids_check'])) {
				$doubleBid = $this->Bid->doubleBidsCheck($this->data['Auction']['id'], $this->data['Auction']['leader_id']);
				if($doubleBid == false) {
					return false;
				}
			}

			return true;
		}

		function countAll($types = null){
			if(!empty($types)){
				if(is_array($types)){
					$results = array();

					foreach($types as $type){
						$results[$type] = $this->count($type);
					}
				}else{
					$results = $this->count($types);
				}

				return $results;
			}else{
				return false;
			}
		}

		function count($type = null){
			if(!empty($type)){
				switch($type){
					case 'live':
						$count = $this->find('count', array('recursive'=>-1,
												'conditions' => array(	"start_time <"=>date('Y-m-d H:i:s'),
												"end_time >"=>$this->getEndTime())));
						break;

					case 'comingsoon':
						$count = $this->find('count', array('recursive'=>-1,
												'conditions' => array(	"start_time >"=>date('Y-m-d H:i:s'))));
						break;

					case 'closed':
						$count = $this->find('count', array('recursive'=>-1,
												'conditions' => array(	'closed' => 1)));
						break;

					case 'free':
						$count = $this->find('count', array('contain'=>array('Product'),
												'conditions' => array(	"Auction.free"=>1,
												"start_time <"=>date('Y-m-d H:i:s'),
												"end_time >"=>$this->getEndTime())));
						break;

					default:
						$count = 0;
				}

				return $count;
			}else{
				return false;
			}
		}

		function afterSave($created){
			parent::afterSave($created);
	
			// commented this out as auction records are saved constantly by hits counter
			///$this->clearCache();
			return true;
		}

		function afterDelete(){
			parent::afterDelete();

			$this->clearCache();
			return true;
		}

		function clearCache() {
			if(!empty($this->data['Auction']['id'])) {
				Cache::delete('auction_view_'.$this->data['Auction']['id']);
				Cache::delete('auction_'.$this->data['Auction']['id']);
				Cache::delete('daemons_extend_auctions');
				Cache::delete('last_bid_'.$this->data['Auction']['id']);
			}
			
			Cache::delete('auctions_end_soon');
			Cache::delete('auctions_live');
		}
		
		function beforeFind($queryData) {
			//exclude deleted auctions from all find() requests
			if (is_array($queryData['conditions'])) {
				$queryData['conditions']['Auction.deleted']=0;
			} else {
				$queryData['conditions'].=' AND Auction.deleted=0';
			}
			return $queryData;
		}
		
		function del($id) {
			//*** set the deleted flag on this product record
			$this->save(array('id'=>$id,'deleted'=>1));
			
			return true;
		}
		
		function binPrice($auction_id, $buy_now_price, $user_id) {
			
			//see if buy it now is disabled for this item
			if ($buy_now_price==0) return 0;
			
			//see how many bids this user placed
			$bid_count=$this->Bid->find('count', array('conditions'=>array('Bid.auction_id'=>$auction_id,'Bid.user_id'=>$user_id)));
			
			if (Configure::read('App.buyNow.bid_discount')===true) {
				
				//see if this user has bought it before, if so no discount will be offered
				$prevBought=$this->find('count', array('conditions'=>array(	'winner_id'=>$user_id,
												'closed_status'=>2,
												'parent_id'=>$auction_id)));
				if ($prevBought>0) {
					$discount=0;
				} else {
					$discount=($bid_count*Configure::read('App.buyNow.bid_price'));
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
		
		function canBuyNow($auction, $user_id) {
			if (($this->appConfigurations['buyNow']!==true 
				or $this->appConfigurations['buyNow']['enabled']!==true)
			 	&& !$auction['Product']['buy_now']) {
				return false;
			}
			if (Configure::read('App.buyNow.before_closed')==false) {
				//if the auction isn't closed, we can't buy
				if (!$auction['Auction']['closed']) return false;
			}
			if (Configure::read('App.buyNow.after_closed')==false) {
				//if the auction is closed, we can't buy
				
				if ($auction['Auction']['closed']) return false;
				
			} elseif ($auction['Auction']['closed']) {
				//we can buy after closed, check if we're within the time range
				
				if (Configure::read('App.buyNow.must_bid_before')==true) {
					//if this user hasn't bid before, they can't buy it
					
					$bids=$this->Bid->find('count', array('conditions'=>array('Bid.user_id'=>$user_id, 'Bid.auction_id'=>$auction['Auction']['id'])));
					if ($bids===0) return false;
				}
				
				$hours_after_closed=doubleval(Configure::read('App.buyNow.hours_after_closed'));
				if ($hours_after_closed<0.25) return false;
				
				//end times are sometimes date_format, sometimes timestamp ?
				if (is_numeric($auction['Auction']['end_time'])) {
					$end_time=$auction['Auction']['end_time'];
				} else {
					$end_time=strtotime($auction['Auction']['end_time']);
				}
				
				if (((time() - $end_time)/60/60) > ($hours_after_closed)) {
					//too much time has elapsed
					return false;
				}
				
			}
			
			
			
			//we must be able to buy then
			return true;
			
		}
		
	}
?>