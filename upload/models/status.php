<?php
class Status extends AppModel {

	var $name = 'Status';

	var $hasMany = 'Auction';

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'rule' => array('minLength', 1),
				'message' => __('Name is a required field.', true)
			),
		);
	}
}
?>
