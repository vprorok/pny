<?php
class Setting extends AppModel {

	var $name = 'Setting';

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'value' => array(
				'rule' => array('minLength', 1),
				'message' => __('Value is required.', true)
			)
		);
	}

	function get($name) {
		if(!empty($name)) {
			if (isset($this->controller->setting[$name])) {			
				return $setting;
			} else {
				return false;
			}
		}
	}

	function beforeSave(){
		if(!empty($this->data)){
			if(!empty($this->data['Setting']['name'])){
				Cache::delete($this->data['Setting']['name']);
				Cache::write($this->data['Setting']['name'], $this->data['Setting']['value'], 'day');
			}
		}
		return true;
	}
	
	function afterSave($model) {
		Cache::delete('settings');
		return true;
	}
	
	function updateQuery($query) {
		$this->query($query);
		$this->log('Query performed: '.$query, 'upgrade');
	}
	
	//*** Return array all database settings to be stored in AppController object for quick
	//*** future reference.
	function load() {
		
		if (($settings = Cache::read('settings', 'day'))===FALSE) {
			$settings=$this->find('list', array('fields'=>array('Setting.name', 'Setting.value')));
			Cache::write('settings', $settings, 'day');
		}
		
		return $settings;
			
	}
	
	function getSettingOptions() {
		return array(	'onoff'=>array(		0=>__('Off', true),
									1=>__('On', true)),
					'yesno'=>array(		0=>__('No', true),
									1=>__('Yes', true)),
					'truefalse'=>array(	0=>__('False', true),
									1=>__('True', true)));
	}
}
?>