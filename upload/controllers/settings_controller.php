<?php
class SettingsController extends AppController {

	var $name = 'Settings';

	var $uses = array('Setting', 'SettingIncrement', 'Auction');

	function beforeFilter() {
		parent::beforeFilter();
		if(isset($this->Auth)){
			$this->Auth->allow('get', 'timestamp', 'isPeakNow', 'offline');
		}
	}

	/**
	 * Function to get setting from everywhere, including ajax
	 *
	 * @param string $name The name of string
	 * @param string $auction_id
	 * @param string $cache
	 * @return mixed The value of setting
	 */
	function get($name = null, $auction_id = null, $cache = true) {
		if($cache == true) {
			$setting = $this->Setting->get($name);
		}
		if(!empty($setting)) {
			return $setting;
		} else {
			// lets check our dynamic settings
			if(Configure::read('App.bidIncrements') == 'single') {
				$settings = $this->SettingIncrement->find('first');
			} elseif(Configure::read('App.bidIncrements') == 'dynamic') {
				$auction = $this->SettingIncrement->Product->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => '', 'fields' => 'Auction.price'));
				$settings = $this->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.lower_price <=' => $auction['Auction']['price'], 'SettingIncrement.upper_price >' => $auction['Auction']['price'])));

				if(empty($settings)) {
					// lets check to see if the price is in the upper region
					$settings = $this->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.lower_price <=' => $auction['Auction']['price'], 'SettingIncrement.upper_price' => '0.00')));
				}
				
				if(empty($settings)) {
					// lets check to see if the price is in the upper region
					$settings = $this->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.lower_price' => '0.00', 'SettingIncrement.upper_price >' => $auction['Auction']['price'])));
				}
				
				if(empty($settings)) {
					// finally if it fits none of them for some strange reason
					$settings = $this->SettingIncrement->find('first');
				}
			} elseif(Configure::read('App.bidIncrements') == 'product') {
				$auction = $this->SettingIncrement->Product->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => '', 'fields' => 'Auction.product_id'));
				$settings = $this->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.product_id' => $auction['Auction']['product_id']), 'recursive' => -1));
			}

			if(!empty($this->params['requested'])) {
				return $settings['SettingIncrement'][$name];
			} else {
				$this->layout = 'js/ajax';
				echo $settings['SettingIncrement'][$name];
			}
		}
	}

	function timestamp(){
		Configure::write('debug', 0);
		$this->layout = 'js/ajax';
		$this->set('timestamp', time());
	}

	function offline() {
		$this->set('message', $this->Setting->get('offline_message'));
	}

	function admin_index() {
		$this->paginate = array(	'limit' => Configure::read('App.adminPageLimit'), 
							'order' => array('name' => 'asc'));
		$this->set('settings', $this->paginate('Setting', array('Setting.name <>'=>'phppa_version')));
		
		$this->set('setting_options', $this->Setting->getSettingOptions());
	}

	function admin_edit($id = null) {
		if (!is_numeric($id)) {
			//must be by key
			$record=$this->Setting->findByName($id);
			$id=$record['Setting']['id'];
		}
		
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Setting', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Setting->save($this->data)) {
				$this->Session->setFlash(__('The setting has been successfully updated.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem updating the setting please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
//			$this->data = $this->Setting->read(null, $id);
			$this->data = $this->Setting->find('first', array('conditions'=>array('id'=>$id),'fields'=>array('*')));
		}
		
		$this->set('setting_options', $this->Setting->getSettingOptions());
	}
	
}
?>