<?php
class StatusesController extends AppController {

	var $name = 'Statuses';

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('id' => 'asc'));
		$this->set('statuses', $this->paginate());
	}

	function admin_edit($id = null) {
		if(!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Status', true));
			$this->redirect(array('action' => 'index'));
		}
		if(!empty($this->data)) {
			if ($this->Status->save($this->data)) {
				$this->Session->setFlash(__('The status has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The status could not be saved. Please, try again.', true));
			}
		}
		if(empty($this->data)) {
			$this->data = $this->Status->read(null, $id);
		}
	}
}
?>
