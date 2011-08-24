<?php
class Package extends AppModel {
	var $name = 'Package';

	var $hasOne = array('PackagePoint');

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'rule' => array('minLength', 1),
				'message' => __('Package name is a required field.', true)
			),
			'bids' => array(
				'numeric' => array(
					'rule'=> 'numeric',
					'message' => __('The number of bids can be a number only.', true)
				),
				'minLength' => array(
					'rule' => array('minLength', 1),
					'message' => __('Number of bids is required.', true)
				)
			),
			'price' => array(
				'numeric' => array(
					'rule'=> 'numeric',
					'message' => __('Price can be a number only.', true)
				),
				'minLength' => array(
					'rule' => array('minLength', 1),
					'message' => __('Price is required.', true)
				)
			)
		);

	}

	function afterFind($results, $primary = false){
		// Parent method redefined
		$results = parent::afterFind($results, $primary);

		if(!empty($results)){

			//Coupon stuff
			if(Configure::read('App.coupons')){
				$user_id = !empty($_SESSION['Auth']['User']['id']) ? $_SESSION['Auth']['User']['id'] : null;

				// If there is no auth session it probably came from
				// payment gateway notification request
				if(empty($user_id)){
					$user_id = !empty($_SESSION['PaymentGateway']['user_id']) ? $_SESSION['PaymentGateway']['user_id'] : null;
					if(!empty($user_id)){
						unset($_SESSION['PaymentGateway']['user_id']);
					}
				}

				if(!empty($user_id)){
					$coupon = Cache::read('coupon_user_'.$user_id);
				}
			}

			// Getting rate for current currency
			$rate = $this->_getRate();

			// This for find('all')
			if(!empty($results[0]['Package'])){
				// Loop over find result and convert the price with rate
				foreach($results as $key => $result){
					if(!empty($result['Package']['price'])){
						$results[$key]['Package']['price'] = $result['Package']['price'] * $rate;

						if(!empty($coupon)){
							switch($coupon['Coupon']['coupon_type_id']){
								case 1:
									$results[$key]['Package']['price'] -= $results[$key]['Package']['price'] * ($coupon['Coupon']['saving']/100);
									break;
								case 2:
									$results[$key]['Package']['price'] -= $coupon['Coupon']['saving'];
									break;

								case 3:
									$results[$key]['Package']['bids'] += round($coupon['Coupon']['saving']);
									break;

								case 4:
									$results[$key]['Package']['bids'] += $results[$key]['Package']['bids'] * ($coupon['Coupon']['saving']/100);
									break;

								case 5:
									$results[$key]['PackagePoint']['points'] += $coupon['Coupon']['saving'];
									break;
							}

							$results[$key]['Package']['name']  .= ' + '.$coupon['Coupon']['code'];
						}
					}
				}

			// This for find('first')
			}elseif(!empty($results['Package'])){
				if(!empty($results['Package']['price'])){
					$results['Package']['price'] = $results['Package']['price'] * $rate;

					if(!empty($coupon)){
						switch($coupon['Coupon']['coupon_type_id']){
							case 1:
								$results['Package']['price'] -= $results['Package']['price'] * ($coupon['Coupon']['saving']/100);
								break;

							case 2:
								$results['Package']['price'] -= $coupon['Coupon']['saving'];
								break;

							case 3:
								$results['Package']['bids'] += round($coupon['Coupon']['saving']);
								break;

							case 4:
								$results['Package']['bids'] += $results['Package']['bids'] * ($coupon['Coupon']['saving']/100);
								break;

							case 5:
								$results['PackagePoint']['points'] += $coupon['Coupon']['saving'];
								break;

						}

						$results['Package']['name']  .= ' + '.$coupon['Coupon']['code'];
					}
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
		if(!empty($this->data['Package']['price'])){
			$this->data['Package']['price'] = $this->data['Package']['price'] * $rate;
		}

		return true;
	}

	function afterSave($created){
		parent::afterSave($created);

		// Save the point
		if(!empty($this->data['PackagePoint']['points'])){

			// Get the package id
			if(!empty($this->data['Package']['id'])){
				$id = $this->data['Package']['id'];
			}else{
				$id = $this->getLastInsertID();
			}
			// store on the same array as the package point
			$this->data['PackagePoint']['package_id'] = $id;

			// check the point whether it exists or not
			$packagePoint = $this->PackagePoint->findByPackageId($id);
			if(!empty($packagePoint)){
				$this->data['PackagePoint']['id'] = $packagePoint['PackagePoint']['id'];
			}else{
				$this->PackagePoint->create();
			}

			$this->PackagePoint->save($this->data);
		}
	}
	
	function most_expensive_package() {
		// lets assume the cheapest package is the most expensive per bid
		$package = $this->find('first', array('order' => array('Package.price' => 'asc')));
		if(!empty($package)) {
			return $package['Package']['price'] / $package['Package']['bids'];
		} else {
			return null;
		}
	}
}
?>