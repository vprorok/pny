<?php
class Newsletter extends AppModel {

	var $name = 'Newsletter';



	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'subject' => array(
				'rule' => array('minLength', 1),
				'message' => __('Subject is required', true)
			),

			'body' => array(
				'rule' => array('minLength', 1),
				'message' => __('Body is required', true)
			)
		);
	}
}
?>
