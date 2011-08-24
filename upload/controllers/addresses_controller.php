<?php
class AddressesController extends AppController {

	var $name = 'Addresses';

	function index() {
		$address_types = $this->Address->addressTypes();
		$data = array();
		foreach($address_types as $key=>$type) {
			$data[$type] = $this->Address->find('first', array('conditions' => array('Address.user_id' => $this->Auth->user('id'), 'Address.user_address_type_id' => $key)));
		}
		$this->set('address', $data);

		$this->pageTitle = __('My Addresses', true);
	}

	function add($name = null) {
		if(empty($name)) {
			$this->Session->setFlash(__('Invalid address type.', true));
			$this->redirect(array('action'=>'index'));
		}
		$address_types = $this->Address->addressTypes();
		$address_type_id=array_search($name, $address_types);
		if(!$address_type_id) {
			$this->Session->setFlash(__('Invalid address type.', true));
			$this->redirect(array('action'=>'index'));
		}
		if($this->Address->find('count', array('conditions' => array('Address.user_id' => $this->Auth->user('id'), 'Address.user_address_type_id' => $address_type_id))) > 0) {
			$this->redirect(array('action'=>'edit', $name));
		}
		$this->set('name', $name);

		if(!empty($this->data)) {
			$this->data['Address']['user_id'] = $this->Auth->user('id');
			$this->data['Address']['user_address_type_id'] = $address_type_id;
			$this->Address->create();
			if($this->Address->save($this->data)) {
				if(!empty($this->data['Address']['update_all'])) {
					$this->Address->updateAll($this->data);
				}
				$this->Session->setFlash(__('The address was successfully added.', true), 'default', array('class' => 'success'));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the address, please correct the errors below.', true));
			}
		}

		$this->set('countries', $this->Address->Country->find('list'));

		$this->pageTitle = __('Add an Address', true);
	}

	function edit($name = null) {
		if(empty($name)) {
			$this->Session->setFlash(__('Invalid address type.', true));
			$this->redirect(array('action'=>'index'));
		}
		$address_types = $this->Address->addressTypes();
		$address_type_id=array_search($name, $address_types);
		if(!$address_type_id) {
			$this->Session->setFlash(__('Invalid address type.', true));
			$this->redirect(array('action'=>'index'));
		}
		if($this->Address->find('count', array('conditions' => array('Address.user_id' => $this->Auth->user('id'), 'Address.user_address_type_id' => $address_type_id))) == 0) {
			$this->redirect(array('action'=>'add', $name));
		}
		$this->set('name', $name);

		if(!empty($this->data)) {
			$this->data['Address']['user_id'] = $this->Auth->user('id');
			$this->data['Address']['user_address_type_id'] = $address_type_id;
			if($this->Address->save($this->data)) {
				if(!empty($this->data['Address']['update_all'])) {
					$this->Address->updateAll($this->data);
				}
				$this->Session->setFlash(__('The address was successfully updated.', true), 'default', array('class' => 'success'));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem updating the address, please correct the errors below.', true));
			}
		} else {
			$this->data = $this->Address->find('first', array('conditions' => array('user_address_type_id' => $address_type_id, 'user_id' => $this->Auth->user('id'))));
		}

		$this->set('countries', $this->Address->Country->find('list'));

		$this->pageTitle = __('Edit an Address', true);
	}
}
?>
