<?php
class AccountsController extends AppController {

	var $name = 'Accounts';

	function index() {
		$this->Account->recursive = 0;
		$this->paginate = array('conditions' => array('Account.user_id' => $this->Auth->user('id')), 'limit' => 50, 'order' => array('Account.created' => 'desc'));
		$this->set('accounts', $this->paginate());

		$this->pageTitle = __('My Account', true);
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Account->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Account.user_id' => $user_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Auction.end_time' => 'asc'));
		$this->set('accounts', $this->paginate());
	}
}
?>
