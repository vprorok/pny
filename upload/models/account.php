<?php
class Account extends AppModel {

	var $name = 'Account';

	var $belongsTo = array('User', 'Auction');

	function afterFind($results, $primary = false){
		// Parent method redefined
		$results = parent::afterFind($results, $primary);

		if(!empty($results)){
			// Getting rate for current currency
			$rate = $this->_getRate();

			// This for find('all')
			if(!empty($results[0]['Account'])){
				// Loop over find result and convert the price with rate
				foreach($results as $key => $result){
					if(!empty($result['Account']['price'])){
						$results[$key]['Account']['price'] = $result['Account']['price'] * $rate;
					}
				}

			// This for find('first')
			}elseif(!empty($results['Account'])){
				if(!empty($results['Account']['price'])){
					$results['Account']['price'] = $results['Account']['price'] * $rate;
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
		if(!empty($this->data['Account']['price'])){
			$this->data['Account']['price'] = $this->data['Account']['price'] * $rate;
		}

		return true;
	}
}
?>
