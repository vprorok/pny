<?php
	class Product extends AppModel {

		var $name = 'Product';

		var $actsAs = array('Containable');

		var $belongsTo = array(
			'Category' => array(
				'className'  => 'Category',
				'foreignKey' => 'category_id'
			),

			'Limit' => array(
				'className' => 'Limit',
				'foreignKey' => 'limit_id'
			)
		);

		var $hasMany = array(
			'Auction'  => array(
				'className'  => 'Auction',
				'foreignKey' => 'product_id',
				'dependent'  => true
			),
			'Image' 	 => array(
				'className'  => 'Image',
				'foreignKey' => 'product_id',
				'order' 	 => array('order' => 'asc'),
				'dependent'  => true,
			),
			'SettingIncrement' 	 => array(
				'className'  => 'SettingIncrement',
				'foreignKey' => 'product_id',
				'order' 	 => array('lower_price' => 'asc'),
				'dependent'  => true,
			),
			'Translation' 	 => array(
				'className'  => 'Translation',
				'foreignKey' => 'product_id',
				'dependent'  => true,
			)
		);



		function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'title' => array(
					'rule' => array('minLength', 1),
					'message' => __('Product title is a required field.', true)
				),
				'category_id' => array(
					'rule' => array('minLength', 1),
					'message' => __('Please select a category from the dropdown.', true)
				),
				'rrp' => array(
					'rule'=> 'numeric',
					'message' => __('RRP can be a number only.', true),
					'allowEmpty' => true
				),
				'buy_now' => array(
					'rule'=> 'numeric',
					'message' => __('Buy Now can be a number only.', true),
					'allowEmpty' => true
				),
				'start_price' => array(
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('Start price can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Start price is required.', true)
					)
				),
				'fixed_price' => array(
					'rule'=> 'numeric',
					'message' => __('Fixed price can be a number only.', true),
					'allowEmpty' => true
				),
				'stock_number' => array(
					'comparison' => array(
							'rule'=> array('comparison', 'is greater', -1),
							'message' => __('Stock Number must be greater or equal to zero.', true)
					),
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('Stock Number can be a number only.', true)
					)
				),
				'minimum_price' => array(
					'rule'=> 'numeric',
					'message' => __('Minumum price can be a number only.', true),
					'allowEmpty' => true
				),
				'start_price' => array(
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('Start price can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Start price is required.', true)
					)
				),
				'cost_price' => array(
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('Cost price can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Cost price is required.', true)
					)
				),
				'profit' => array(
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('Profit can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Profit is required.', true)
					)
				),
				'bid_cost' => array(
					'comparison' => array(
						'rule'=> array('comparison', 'is greater', 0),
						'message' => __('Cost price per bid must be greater than zero.', true)
					),
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('Cost price per bid can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Cost price per bid is required.', true)
					)
				),
				'bid_increment' => array(
					'comparison' => array(
						'rule'=> array('comparison', 'is greater', 0),
						'message' => __('Bid increment must be greater than zero.', true)
					),
					'numeric' => array(
						'rule'=> 'numeric',
						'message' => __('Bid increment can be a number only.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Bid increment is required.', true)
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
				if(!empty($results[0]['Product'])){
					// Loop over find result and convert the price with rate
					foreach($results as $key => $result){
						if(!empty($results[$key]['Product']['rrp'])){
							$results[$key]['Product']['rrp'] = $result['Product']['rrp'] * $rate;
						}

						if(!empty($results[$key]['Product']['start_price'])){
							$results[$key]['Product']['start_price'] = $result['Product']['start_price'] * $rate;
						}

						if(!empty($results[$key]['Product']['delivery_cost'])){
							$results[$key]['Product']['delivery_cost'] = $result['Product']['delivery_cost'] * $rate;
						}

						if(!empty($results[$key]['Product']['fixed_price'])){
							$results[$key]['Product']['fixed_price'] = $result['Product']['fixed_price'] * $rate;
						}

						if(!empty($results[$key]['Product']['minimum_price'])){
							$results[$key]['Product']['minimum_price'] = $result['Product']['minimum_price'] * $rate;
						}
					}

				// This for find('first')
				}elseif(!empty($results['Product'])){
					if(!empty($results['Product']['rrp'])){
						$results['Product']['rrp'] = $results['Product']['rrp'] * $rate;
					}

					if(!empty($results['Product']['start_price'])){
						$results['Product']['start_price'] = $results['Product']['start_price'] * $rate;
					}

					if(!empty($results['Product']['delivery_cost'])){
						$results['Product']['delivery_cost'] = $results['Product']['delivery_cost'] * $rate;
					}

					if(!empty($results['Product']['fixed_price'])){
						$results['Product']['fixed_price'] = $results['Product']['fixed_price'] * $rate;
					}

					if(!empty($results['Product']['minimum_price'])){
						$results['Product']['minimum_price'] = $results['Product']['minimum_price'] * $rate;
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
			if(!empty($this->data['Product']['rrp'])){
				$this->data['Product']['rrp'] = $this->data['Product']['rrp'] * $rate;
			}

			if(!empty($this->data['Product']['start_price'])){
				$this->data['Product']['start_price'] 	= $this->data['Product']['start_price'] * $rate;
			}

			if(!empty($this->data['Product']['delivery_cost'])){
				$this->data['Product']['delivery_cost'] = $this->data['Product']['delivery_cost'] * $rate;
			}

			if(!empty($this->data['Product']['fixed_price'])){
				$this->data['Product']['fixed_price'] 	= $this->data['Product']['fixed_price'] * $rate;
			}

			if(!empty($this->data['Product']['minimum_price'])){
				$this->data['Product']['minimum_price'] = $this->data['Product']['minimum_price'] * $rate;
			}

			return true;
		}
		
		function del($id) {
			//*** set the deleted flag on this product record
			$this->save(array('id'=>$id,'deleted'=>1));
			
			//*** set the deleted flag on all auctions using this product
			$auctions=$this->Auction->find('all', array('conditions'=>array('product_id'=>$id)));
			foreach ($auctions as $auction) {
				$auction['Auction']['deleted']=1;
				$this->Auction->create();
				$this->Auction->save($auction);
			}
			return true;
		}
		
		function beforeFind($queryData) {
			//exclude deleted products from all find() requests
			if (is_array($queryData['conditions'])) {
				$queryData['conditions']['Product.deleted']=0;
			} else {
				$queryData['conditions'].=' AND Product.deleted=0';
			}
			return $queryData;
		}
		
		function soldStock($product_id, $count=1) {
			//on stock control products, reduce the in-stock count by $count
			$product=$this->findById($product_id);
			
			if ($product['Product']['stock']) {
				$product['Product']['stock_number']--;
				
				$this->Save($product);
			}
		}

	}
?>