<?php
class Affiliate extends AppModel {

	var $name = 'Affiliate';

	var $belongsTo = array(
			'User' => array(
				'className'  => 'User',
				'foreignKey' => 'user_id'
			), 
			'Affiliater' => array(
				'className'  => 'User',
				'foreignKey' => 'affiliate_id'
			)
		);
	
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
				),
				'balance' => array(
					'withdrawLimit' => array(
						'rule'=> array('withdrawLimit'),
						'message' => __('The amount to withdraw must be the same or less than your balance.', true)
					),
					'comparison' => array(
						'rule'=> array('comparison', 'isgreater', 0),
						'message' => __('The withdraw amount must be greater than zero.', true)
					),
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('The withdraw amount can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Withdraw amount is required.', true)
					)
				),
				'email' => array(
					'email' => array(
						'rule' => 'email',
						'message' => __('The email address you entered is not valid.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Email address is required.', true)
					)
				),
			);
		}
	
	/**
	 * Function to get affiliate balance
	 *
	 * @param int $user_id User id
	 * @return int Balance of user's bid
	 */
	function balance($user_id) {
		$credit = $this->find('all', array('conditions' => array('Affiliate.affiliate_id' => $user_id), 'fields' => "SUM(Affiliate.credit) as 'credit'"));
		if(empty($credit[0][0]['credit'])) {
			$credit[0][0]['credit'] = 0;
		}

		$debit  = $this->find('all', array('conditions' => array('Affiliate.affiliate_id' => $user_id), 'fields' => "SUM(Affiliate.debit) as 'debit'"));
		if(empty($debit[0][0]['debit'])) {
			$debit[0][0]['debit'] = 0;
		}

		return $credit[0][0]['credit'] - $debit[0][0]['debit'];
	}
	
	/**
	 * Function to check that the users balance withdraw is less than or equal to their balance
	 *
	 * @return true is pass, false otherwise
	 */
	function withdrawLimit() {
		if(!empty($this->data['Affiliate']['balance'])) {
			if($this->data['Affiliate']['balance'] > $this->data['Affiliate']['affiliateBalance']) {
				return false;
			}
		}
		
		return true;
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