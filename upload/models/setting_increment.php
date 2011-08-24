<?php
class SettingIncrement extends AppModel {

	var $name = 'SettingIncrement';

	var $belongsTo = array(
		'Product' => array(
			'className'  => 'Product',
			'foreignKey' => 'product_id'
		)
	);

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'lower_price' => array(
				'numeric' => array(
					'rule'=> 'numeric',
					'message' => __('Lower price can be a number only.', true)
				),
				'minLength' => array(
					'rule' => array('minLength', 1),
					'message' => __('Lower price is required.', true)
				)
			),
			'upper_price' => array(
				'numeric' => array(
					'rule'=> 'numeric',
					'message' => __('Upper price can be a number only.', true)
				),
				'minLength' => array(
					'rule' => array('minLength', 1),
					'message' => __('Upper price is required.', true)
				)
			),
			'bid_debit' => array(
				'comparison' => array(
						'rule'=> array('comparison', 'is greater', 0),
						'message' => __('Bid debit must be greater than zero.', true)
				),
				'numeric' => array(
					'rule'=> 'numeric',
					'message' => __('Bid debit can be a number only.', true)
				),
				'minLength' => array(
					'rule' => array('minLength', 1),
					'message' => __('Bid debit is required.', true)
				)
			),
			'price_increment' => array(
				'comparison' => array(
						'rule'=> array('comparison', 'is greater', 0),
						'message' => __('Price increment must be greater than zero.', true)
				),
				'numeric' => array(
					'rule'=> 'numeric',
					'message' => __('Price increment can be a number only.', true)
				),
				'minLength' => array(
					'rule' => array('minLength', 1),
					'message' => __('Price increment is required.', true)
				)
			),
			'time_increment' => array(
				'numeric' => array(
					'rule'=> 'numeric',
					'message' => __('Time increment can be a number only.', true)
				),
				'minLength' => array(
					'rule' => array('minLength', 1),
					'message' => __('Time increment is required.', true)
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
			if(!empty($results[0]['SettingIncrement'])){
				// Loop over find result and convert the price with rate
				foreach($results as $key => $result){
					if(!empty($result['SettingIncrement']['lower_price'])){
						$results[$key]['SettingIncrement']['lower_price'] = $result['SettingIncrement']['lower_price'] * $rate;
					}

					if(!empty($result['SettingIncrement']['upper_price'])){
						$results[$key]['SettingIncrement']['upper_price'] = $result['SettingIncrement']['upper_price'] * $rate;
					}

					if(!empty($result['SettingIncrement']['price_increment'])){
						$results[$key]['SettingIncrement']['price_increment'] = $result['SettingIncrement']['price_increment'] * $rate;
					}
				}

			// This for find('first')
			}elseif(!empty($results['SettingIncrement'])){
				if(!empty($results['SettingIncrement']['lower_price'])){
					$results['SettingIncrement']['lower_price'] = $results['SettingIncrement']['lower_price'] * $rate;
				}

				if(!empty($results['SettingIncrement']['upper_price'])){
					$results['SettingIncrement']['upper_price'] = $results['SettingIncrement']['upper_price'] * $rate;
				}

				if(!empty($results['SettingIncrement']['price_increment'])){
					$results['SettingIncrement']['price_increment'] = $results['SettingIncrement']['price_increment'] * $rate;
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
		if(!empty($this->data['SettingIncrement']['lower_price'])){
			$this->data['SettingIncrement']['lower_price'] = $this->data['SettingIncrement']['lower_price'] * $rate;
		}

		if(!empty($this->data['SettingIncrement']['upper_price'])){
			$this->data['SettingIncrement']['upper_price'] = $this->data['SettingIncrement']['upper_price'] * $rate;
		}

		if(!empty($this->data['SettingIncrement']['price_increment'])){
			$this->data['SettingIncrement']['price_increment'] = $this->data['SettingIncrement']['price_increment'] * $rate;
		}


		return true;
	}

}
?>
