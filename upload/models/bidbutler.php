<?php
	class Bidbutler extends AppModel {

		var $name = 'Bidbutler';

		var $belongsTo = array('User', 'Auction');

		var $actsAs = array('Containable');

		function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);
			$this->validate = array(
				'minimum_price' => array(
					'advancedPrice' => array(
						'rule'=> array('advancedPrice'),
						'message' => __('The minimum price must be set higher.', true)
					),
					'reversePrice' => array(
						'rule'=> array('reversePrice'),
						'message' => __('The minimum price must be set lower.', true)
					),
					'highLow' => array(
						'rule'=> array('highLow'),
						'message' => __('The minimum price must be higher than the maximum price.', true)
					),
					'comparison' => array(
						'rule'=> array('comparison', 'not equal', 0),
						'message' => __('The minimum price cannot be zero.', true)
					),
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('The minimum price can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Minimum price is required.', true)
					)
				),
				'maximum_price' => array(
					'custom'=>array( 
						'rule'=>array('checkMaxPrice'), 
						'message'=> __('Please enter a valid maximum price.', true)
					)
				),
				'bids' => array(
					'advancedBids' => array(
						'rule'=> array('advancedBids'),
						'message' => __('Please enter in at least 2 bids.', true)
					),
					'balanceCheck' => array(
						'rule'=> array('balanceCheck'),
						'message' => __('This number exceeds the number of bids you have in your account.', true)
					),
					'comparison' => array(
						'rule'=> array('comparison', 'is greater', 0),
						'message' => __('The number of bids cannot be zero or a negative number.', true)
					),
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('The number of bids can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Number of bids is required.', true)
					)
				)
			);
		}

        /**
		 * Function to check that the number of bids is less than what is in their account
		 *
		 * @param array $data User id
		 * @return true if passed, false otherwise
		 */
        function balanceCheck($data) {
        	if(isset($this->data['Bidbutler']['balance'])) {
	        	if(!empty($this->data['Bidbutler']['balance'])) {
		        	if($this->data['Bidbutler']['balance'] < $data['bids']) {
		        		return false;
		        	}
	        	} else {
	        		return false;
	        	}
        	}

        	return true;
        }

        /**
		* Function for the advanced bid butler to check that the price is at least 1.00 higher than the current price
		*
		* @param array $data User id
		* @return true if passed, false otherwise
		*/
        function advancedPrice($data) {
        	if(Configure::read('App.bidButlerType') == 'advanced') {
        		$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $this->data['Bidbutler']['auction_id']), 'contain' => '', 'fields' => array('Auction.price','Auction.reverse')));
        		if ($auction['Auction']['reverse']) {
        			return true;
        		} else {
				if($data['minimum_price'] - $auction['Auction']['price'] < 0.01) {
					return false;
				} else {
					return true;
				}
			}
        	} else {
        		return true;
        	}
        }
        
        //*** Reverse auctions only: minimum price must be below current price
        function reversePrice($data) {
        	$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $this->data['Bidbutler']['auction_id']), 'contain' => '', 'fields' => array('Auction.price','Auction.reverse')));
		if ($auction['Auction']['reverse']) {
			//minimum price must be below current price
			if ($data['minimum_price']>$auction['Auction']['price']) {
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
        }

        /**
		* Function for the advanced bid butler to check that the number of bids is at least 2
		*
		* @param array $data User id
		* @return true if passed, false otherwise
		*/
        function advancedBids($data) {
        	if(Configure::read('App.bidButlerType') == 'advanced') {
        		if($data['bids'] < 2) {
        			return false;
        		} else {
        			return true;
        		}
        	} else {
        		return true;
        	}
        }

        /**
		* Function to check that the min price is less than the max price.
		*
		* @param array $data
		* @return true if passed, false otherwise
		*/
        function highLow($data) {
        	$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $this->data['Bidbutler']['auction_id']), 'contain' => '', 'fields' => array('Auction.price','Auction.reverse')));
		if ($auction['Auction']['reverse'] or Configure::read('App.bidButlerType')=='simple') {
			return true;
		}
		
        	if(!empty($this->data['Bidbutler']['minimum_price']) && !empty($this->data['Bidbutler']['maximum_price'])) {
        		if($this->data['Bidbutler']['minimum_price'] < $this->data['Bidbutler']['maximum_price']) {
        			return true;
        		} else {
        			return false;
        		}
        	} else {
        		return true;
        	}
        }

        /**
		* Function used to group the bid butlers together
		*
		* @param array $bidbutler the current bid butler
		* @param array $data the grouped data being gathered
		* @return true if passed, false otherwise
		*/
        function group($bidbutler, $data = array(), $settings = array()) {
        	if(!empty($bidbutler)) {
        		if(empty($data)) {
        			$data[0] = $bidbutler;
        		}

        		$bidbutler['Auction']['price'] = $bidbutler['Auction']['price'] + $settings['price_increment'];
        		$conditions = array(
					'Bidbutler.user_id <>' => $bidbutler['Bidbutler']['user_id'],
					'Bidbutler.auction_id' => $bidbutler['Auction']['id'],
					'Bidbutler.minimum_price <' => $bidbutler['Auction']['price'],
					'Bidbutler.maximum_price >=' => $bidbutler['Auction']['price'],
					'Bidbutler.bids >' => 0
				);

        		$newBidbutler = $this->Auction->Bidbutler->find('first', array('conditions' => $conditions, 'contain' => '', 'order' => 'rand()'));

        		if(!empty($newBidbutler)) {
        			$newBidbutler['Auction'] = $bidbutler['Auction'];
        			$lastKey = $this->endKey($data);
        			$data[$lastKey + 1] = $newBidbutler;

	        		return $this->group($newBidbutler, $data, $settings);
        		} else {
        			return $data;
        		}
        	} else {
        		return null;
        	}
        }

        function endKey($array){
 			end($array);
 			return key($array);
		}

		function afterFind($results, $primary = false){
			// Parent method redefined
			$results = parent::afterFind($results, $primary);

			// Getting rate for current currency
			$rate = $this->_getRate();

			if(!empty($results)){
				// This for find('all')
				if(!empty($results[0]['Bidbutler'])){
					// Loop over find result and convert the price with rate
					foreach($results as $key => $result){
						if(!empty($results[$key]['Bidbutler']['minimum_price'])){
							$results[$key]['Bidbutler']['minimum_price'] = $result['Bidbutler']['minimum_price'] * $rate;
						}

						if(!empty($results[$key]['Bidbutler']['maximum_price'])){
							$results[$key]['Bidbutler']['maximum_price'] = $result['Bidbutler']['maximum_price'] * $rate;
						}
					}

				// This for find('first')
				}elseif(!empty($results['Bidbutler'])){
					if(!empty($results['Bidbutler']['minimum_price'])){
						$results['Bidbutler']['minimum_price'] = $results['Auction']['minimum_price'] * $rate;
					}

					if(!empty($results['Bidbutler']['maximum_price'])){
						$results['Bidbutler']['maximum_price'] = $results['Auction']['maximum_price'] * $rate;
					}
				}
			}

			// Return back the results
			return $results;
		}

		function beforeSave(){
			// Price currency rate revert back to application default (USD)
			// Get the rate
			$rate = 1 / $this->_getRate();

			// Convert it back to USD
			if(!empty($this->data['Bidbutler']['minimum_price'])){
				$this->data['Bidbutler']['minimum_price'] = $this->data['Bidbutler']['minimum_price'] * $rate;
			}

			if(!empty($this->data['Bidbutler']['maximum_price'])){
				$this->data['Bidbutler']['maximum_price'] = $this->data['Bidbutler']['maximum_price'] * $rate;
			}

			return true;
		}
		
		function checkMaxPrice() {
			if (Configure::read('App.bidButlerType')=='simple') { 
				//don't require maxprice for simple bidbutlers 
				return true; 
			} 
			
			if (!is_numeric($this->data['Bidbutler']['maximum_price']) 
				|| $this->data['Bidbutler']['maximum_price']<0) { 
				return false; 
			} 
			
			//make sure we're over the current auction price 
			$this->Auction->recursive=-1; 
			$auction=$this->Auction->findById($this->data['Bidbutler']['auction_id']); 
			if ($auction['Auction']['price']>$this->data['Bidbutler']['maximum_price']) { 
				return false; 
			}
			
			return true; 
		} 
	}
?>
