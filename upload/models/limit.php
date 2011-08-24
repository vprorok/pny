<?php
class Limit extends AppModel {

	var $name = 'Limit';

	var $actsAs = array('Containable');

	var $hasMany = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'limit_id'
		)
	);

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			'name' => array(
				'rule' => array('minLength', 1),
				'message' => __('Name is a required field.', true)
			),
			'limit' => array(
				'rule' => array('minLength', 1),
				'message' => __('Limit is a required field.', true)
			)
		);
	}
}
?>
