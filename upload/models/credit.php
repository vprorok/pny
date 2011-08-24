<?php
class Credit extends AppModel {

	var $name = 'Credit';

	var $actsAs = array('Containable');

	var $belongsTo = array('Auction', 'User');

	/**
	 * Function to get bid balance
	 *
	 * @param int $user_id User id
	 * @return int Balance of user's bid
	 */
	function balance($user_id, $expiry) {
		$expiry_date = date('Y-m-d H:i:s', time() - ($expiry * 24 * 60 * 60));

		$credit = $this->find('all', array('conditions' => array('Credit.user_id' => $user_id, 'Credit.created >=' => $expiry_date), 'fields' => "SUM(Credit.credit) as 'credit'"));
		if(empty($credit[0][0]['credit'])) {
			$credit[0][0]['credit'] = 0;
		}

		$debit  = $this->find('all', array('conditions' => array('Credit.user_id' => $user_id), 'fields' => "SUM(Credit.debit) as 'debit'"));
		if(empty($debit[0][0]['debit'])) {
			$debit[0][0]['debit'] = 0;
		}


		return $credit[0][0]['credit'] - $debit[0][0]['debit'];
	}

	function afterFind($results, $primary = false){
		// Parent method redefined
		$results = parent::afterFind($results, $primary);

		if(!empty($results)){
			// Getting rate for current currency
			$rate = $this->_getRate();

			// This for find('all')
			if(!empty($results[0]['Credit'])){
				// Loop over find result and convert the price with rate
				foreach($results as $key => $result){
					if(!empty($result['Credit']['credit'])){
						$results[$key]['Credit']['credit'] = $result['Credit']['credit'] * $rate;
					}

					if(!empty($result['Credit']['debit'])){
						$results[$key]['Credit']['debit'] = $result['Credit']['debit'] * $rate;
					}
				}

			// This for find('first')
			}elseif(!empty($results['Credit'])){
				if(!empty($results['Credit']['credit'])){
					$results['Credit']['credit'] = $results['Credit']['credit'] * $rate;
				}

				if(!empty($results['Credit']['debit'])){
					$results['Credit']['debit'] = $results['Credit']['debit'] * $rate;
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
		if(!empty($this->data['Credit']['credit'])){
			$this->data['Credit']['credit'] = $this->data['Credit']['credit'] * $rate;
		}

		if(!empty($this->data['Credit']['debit'])){
			$this->data['Credit']['debit'] = $this->data['Credit']['debit'] * $rate;
		}

		return true;
	}
}
?>
