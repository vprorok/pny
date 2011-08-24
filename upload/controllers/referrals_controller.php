<?php
class ReferralsController extends AppController {

	var $name = 'Referrals';
	var $uses = array('Referral', 'Setting');

	function index() {
		$this->paginate = array('conditions' => array('Referral.referrer_id' => $this->Auth->user('id'), 'Referral.confirmed' => 1), 'limit' => 20, 'order' => array('Referral.modified' => 'desc'));
		$this->set('referrals', $this->paginate());

		$this->set('setting', $this->Setting->get('free_referral_bids'));
		$this->pageTitle = __('Referrals', true);
	}

	function pending() {
		$this->UserReferral->recursive = 0;
		$this->paginate = array('conditions' => array('Referral.referrer_id' => $this->Auth->user('id'), 'Referral.confirmed' => 0), 'limit' => 20, 'order' => array('Referral.modified' => 'desc'));
		$this->set('referrals', $this->paginate());
		$this->pageTitle = __('Pending Referrals', true);
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Referral.created' => 'desc'));
		$this->set('referrals', $this->paginate());
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Referral->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Referral.referrer_id' => $user_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Referral.created' => 'desc'));
		$this->set('referrals', $this->paginate());
	}
}
?>
