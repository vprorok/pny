<?php
class AffiliateCode extends AppModel {

	var $name = 'AffiliateCode';

	var $belongsTo = 'User';
	
	function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'user_id' => array(
					'rule' => array('minLength', 1),
					'message' => __('Please select a username from the drop down.', true)
				),
				'code' => array(
					'rule' => array('minLength', 1),
					'message' => __('Code is a required field.', true)
				),
				'credit' => array(
					'comparison' => array(
						'rule'=> array('comparison', 'not equal', 0),
						'message' => __('The credit cannot be zero.', true)
					),
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('The credit can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Credit is required.', true)
					)
				)
			);
		}

	function afterFind($results, $primary = false){
		// Parent method redefined
		$results = parent::afterFind($results, $primary);

		if(!empty($results)){
			// Getting rate for current currency
			$rate = $this->_getRate();

			// This for find('all')
			if(!empty($results[0]['AffiliateCode'])){
				// Loop over find result and convert the price with rate
				foreach($results as $key => $result){
					if(!empty($result['AffiliateCode']['credit'])){
						$results[$key]['AffiliateCode']['credit'] = $result['AffiliateCode']['credit'] * $rate;
					}
				}

			// This for find('first')
			}elseif(!empty($results['AffiliateCode'])){
				if(!empty($results['AffiliateCode']['credit'])){
					$results['AffiliateCode']['credit'] = $results['AffiliateCode']['credit'] * $rate;
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
		if(!empty($this->data['AffiliateCode']['credit'])){
			$this->data['AffiliateCode']['credit'] = $this->data['AffiliateCode']['credit'] * $rate;
		}

		return true;
	}
}
?>