<?php
class Department extends AppModel {

	var $name = 'Department';

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'rule' => array('minLength', 1),
				'message' => __('Name is a required field.', true)
			),
			'email' => array(
				'rule' => array('email'),
				'message' => __('The email address you entered is not valid.', true),
				'allowEmpty' => true
			),
		);
	}
}
?>
