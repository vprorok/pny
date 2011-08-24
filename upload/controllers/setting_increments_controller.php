<?php
class SettingIncrementsController extends AppController {

	var $name = 'SettingIncrements';
	
	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('lower_price' => 'asc'));
		$this->set('settings', $this->paginate('SettingIncrement', array('product_id'=>0)));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->SettingIncrement->create();
			if ($this->SettingIncrement->save($this->data)) {
				$this->Session->setFlash(__('The increment has been successfully added.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the increment please review the errors below and try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Setting', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->SettingIncrement->save($this->data)) {
				$this->Session->setFlash(__('The increment has been successfully updated.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem updating the increment please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->SettingIncrement->read(null, $id);
		}
	}
	
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for bid increment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->SettingIncrement->del($id)) {
			$this->Session->setFlash(__('The bid increment was successfully deleted.', true));
			$this->redirect(array('action'=>'index'));
		}
	}
}
?>