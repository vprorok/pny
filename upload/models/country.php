<?php
	class Country extends AppModel {

		var $name = 'Country';

		var $hasMany = array(
			'Address' => array(
				'className'  => 'Address',
				'foreignKey' => 'country_id',
				'dependent'  => false
			)
		);

		function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'rule' => array('minLength', 1),
					'message' => __('Country name is a required field.', true)
				),
			);
		}
	}
?>
