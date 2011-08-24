<?php
class CurrenciesController extends AppController {

	var $name = 'Currencies';

	function count() {
		return $this->Currency->find('count');
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('currency' => 'asc'));
		$this->set('currencies', $this->paginate());
	}
	
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid News', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Currency->save($this->data)) {
				$this->Session->setFlash(__('The currency has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem editing the currency please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Currency->read(null, $id);
		}
	}
}
?>