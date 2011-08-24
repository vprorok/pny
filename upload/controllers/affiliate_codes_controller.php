<?php
class AffiliateCodesController extends AppController {

	var $name = 'AffiliateCodes';
	
	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)) {
			$this->Auth->allow('check');
		}
	}
	
	function check($user_id = null) {
		if(empty($user_id)) {
			$user_id = $this->Auth->user('id');
		}
		
		$count = $this->AffiliateCode->find('count', array('conditions' => array('AffiliateCode.user_id' => $user_id)));
		if($count > 0) {
			return true;
		} else {
			return false;
		}
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('AffiliateCode.code' => 'asc'));
		$this->set('codes', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->AffiliateCode->create();
			if ($this->AffiliateCode->save($this->data)) {
				$this->Session->setFlash(__('The affiliate code has been added successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the affiliate code please review the errors below and try again.', true));
			}
		}
		
		$this->set('users', $this->AffiliateCode->User->find('list', array('conditions' => array('User.autobidder' => 0), 'fields' => 'User.username', 'order' => array('User.username' => 'asc'))));
	}

	function admin_edit($id = null) {
		if(!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Affiliate Code', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
            if ($this->AffiliateCode->save($this->data)) {
				$this->Session->setFlash(__('There affiliate code has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem updating the affiliate code please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->AffiliateCode->read(null, $id);
			if(empty($this->data)) {
				$this->Session->setFlash(__('Invalid Affiliate Code', true));
				$this->redirect(array('action' => 'index'));
			}
		}
		
		$this->set('users', $this->AffiliateCode->User->find('list', array('conditions' => array('User.autobidder' => 0), 'fields' => 'User.username', 'order' => array('User.username' => 'asc'))));
	}

	function admin_delete($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid id for Affiliate Code', true));
			$this->redirect(array('action'=>'index'));
		}
		if($this->AffiliateCode->del($id)) {
			$this->Session->setFlash(__('The affiliate code was successfully deleted.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting the affiliate code.', true));
		}
		
		$this->redirect(array('action' => 'index'));
	}
}
?>