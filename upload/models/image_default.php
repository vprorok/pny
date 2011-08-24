<?php
class ImageDefault extends AppModel {

	var $name = 'ImageDefault';


	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'rule' => array('minLength', 1),
				'message' => __('Name is a required field.', true)
			),
			'image' => array(
				'rule' => array('minLength', 1),
				'message' => __('Image is a required field.', true)
			),
		);

	}
}
?>
