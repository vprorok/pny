<?php
	class Address extends AppModel {

		var $name = 'Address';

		var $actsAs = array('Containable');

		var $belongsTo = array(
			'User' => array(
				'className'  => 'User',
				'foreignKey' => 'user_id'
			),

			'Country' => array(
				'className'  => 'Country',
				'foreignKey' => 'country_id'
			)
		);

		/**
		 * Constructor, redefine to use __() in validate message
		 */
		function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'rule' => array('minLength', 1),
					'message' => __('Name is required.', true)
				),
				'address_1' => array(
					'rule' => array('minLength', 1),
					'message' => __('Address (line 1) is required.', true)
				),
				'city' => array(
					'rule' => array('minLength', 1),
					'message' => __('City / State is required.', true)
				),
				'postcode' => array(
					'custom' => array(
						'rule' => array('custom'),
						'message' => __('Please ensure the postcode follows the correct format.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Post Code / Zip Code is required.', true)
					)
				),
				'country_id' => array(
					'rule' => array('minLength', 1),
					'message' => __('Please select a country from the dropdown.', true)
				),
				'phone' => array(
					'rule' => array('customPhone'),
					'message' => __('Phone number is required.', true)
				)
			);
		}

		/**
		 * This function updates all the users addresses to this one
		 */
		function updateAll($data) {
			$address_types = $this->addressTypes();
			foreach ($address_types as $key=>$address_type) {
				if ($address_type==$data['Address']['user_address_type_id']) {
					unset($address_types[$key]);
				}
			}

			if(!empty($address_types)) {
				foreach($address_types as $key=>$type) {
					$oldAddress = $this->find('first', array('conditions' => array('Address.user_id' => $data['Address']['user_id'], 'Address.user_address_type_id' => $key)));
					if(!empty($oldAddress)) {
						// we need to update an existing record
						$data['Address']['id'] = $oldAddress['Address']['id'];
					} else {
						// we are adding a new record
						$this->create();
					}
					$data['Address']['user_address_type_id'] = $key;
					$this->save($data, false);
				}
			}
		}

		function customPhone($data) {
			if(!empty($this->appConfigurations['phoneRequired'])) {
				if(empty($data['phone'])) {
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		}

		function isCompleted($user_id){
			$types = $this->addressTypes();

			$isCompleted = array();

			foreach($types as $id=>$type){
				$conditions = array(
					'Address.user_id' => $user_id,
					'Address.user_address_type_id' => $id
				);

				$this->contain('Country');
				$address = $this->find('first', array('conditions' => $conditions));
				if(empty($address)){
					$isCompleted = false;
					break;
				}else{
					$address['Address']['country_name'] = $address['Country']['name'];
					$isCompleted[$type] = $address['Address'];
				}
			}

			return $isCompleted;
		}
		
		function addressTypes() {
			return array(	1=>'Billing',
										2=>'Shipping');
		}
	}
?>
