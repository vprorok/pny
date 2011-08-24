<?php
class Currency extends AppModel {
	var $name = 'Currency';

	function afterSave($created){
		parent::afterSave($created);

		$currency = strtolower($this->appConfigurations['currency']);
		if(!empty($this->data['Currency']['rate'])){
			// Updating cache
			Cache::delete('currency_'.$currency.'_rate');
			Cache::write('currency_'.$currency.'_rate', $this->data['Currency']['rate']);
		}
	}
}
?>
