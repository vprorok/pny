<?php
class CreditsController extends AppController {

	var $name = 'Credits';

	function index() {
		$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['credits']['expiry'] * 24 * 60 * 60));
		
		$this->paginate = array('conditions' => array('Credit.user_id' => $this->Auth->user('id'), 'Credit.created >' => $expiry_date), 'limit' => 50, 'order' => array('Credit.id' => 'desc'), 'contain' => array('Auction' => 'Product'));
		$this->set('credits', $this->paginate());

		$this->pageTitle = __('My Credits', true);
		
		$this->set('creditBalance', $this->Credit->balance($this->Auth->user('id'), $this->appConfigurations['credits']['expiry']));
	}
	
	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Credit->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);
		
		$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['credits']['expiry'] * 24 * 60 * 60));
		
		$this->paginate = array('conditions' => array('Credit.user_id' => $user_id, 'Credit.created >' => $expiry_date), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Credit.id' => 'desc'), 'contain' => array('Auction' => 'Product'));
		$this->set('credits', $this->paginate());
		
		$this->set('creditBalance', $this->Credit->balance($user_id, $this->appConfigurations['credits']['expiry']));
	}
}
?>