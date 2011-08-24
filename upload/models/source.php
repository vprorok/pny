<?php
class Source extends AppModel {

	var $name = 'Source';

	var $hasMany = array('User');

	function beforeSave(){
		if(!empty($this->data)){
			// Give new order number if it's add only, no need for edit
			if(empty($this->data['Source']['id'])){
				$this->data['Source']['order'] = $this->getLastOrderNumber();
			}
		}

		return true;
	}

	/**
	* Function to get last order number
	*
	* @return int Return last order number
	*/
	function getLastOrderNumber() {
		$this->recursive = -1;
		$lastItem = $this->find('first', array('order' => array('order' => 'desc')));
		if(!empty($lastItem)) {
			return $lastItem['Source']['order'] + 1;
		} else {
			return 0;
		}
	}

}
?>
