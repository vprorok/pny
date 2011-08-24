<?php
class LimitsController extends AppController {

	var $name = 'Limits';

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)) {
			$this->Auth->allow('getlimits', 'canbid');
		}
	}

	function auctions() {
		$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['limits']['expiry'] * 24 * 60 * 60));

		$this->set('total', $this->Limit->Product->Auction->find('count', array('conditions' => array('Auction.leader_id' => $this->Auth->user('id'), 'Auction.end_time >' => $expiry_date))));

		$this->set('auctions', $this->Limit->Product->Auction->find('all', array('conditions' => array('Auction.leader_id' => $this->Auth->user('id'), 'Auction.end_time >' => $expiry_date), 'contain' => array('Product' => 'Limit'), 'order' => array('Auction.end_time' => 'asc'))));

		$this->set('bid_debit', '');

		$this->pageTitle = __('My Auction Limits', true);
	}

	function getlimits() {
		$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['limits']['expiry'] * 24 * 60 * 60));

		$limits = array();

		$auctions = $this->Limit->Product->Auction->find('all', array('conditions' => array('Auction.leader_id' => $this->Auth->user('id'), 'Auction.end_time >' => $expiry_date), 'limit' => $this->appConfigurations['limits']['limit'], 'contain' => '', 'order' => array('Auction.winner_id' => 'desc')));

		foreach($auctions as $auction) {
			if(!empty($auction['Auction']['winner_id'])) {
				$limits[] = 'winner';
			} else {
				$limits[] = 'highest';
			}
		}

		$total = $this->Limit->Product->Auction->find('count', array('conditions' => array('Auction.leader_id' => $this->Auth->user('id'), 'Auction.end_time >' => $expiry_date)));
		$n = $total + 1;
		while ($n <= $this->appConfigurations['limits']['limit']) {
			$limits[] = 'open';
			$n ++;
		}

		return $limits;
	}

	function getlimitsstatus(){
		Configure::write('debug', 0);
		$this->layout = 'js/ajax';
		$status       = array();

		$limits = $this->getlimits();
		foreach($limits as $key => $limit){
			$status[] = array('image' => 'icon-bid-'.$limit.'.gif');
		}
		$this->set('status', $status);
	}

	function canbid($auction_id = null, $user_id = null) {
		return true;
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('limit' => 'desc'));
		$this->set('limits', $this->paginate());
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Limit->Product->Auction->Winner->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['limits']['expiry'] * 24 * 60 * 60));

		$this->set('total', $this->Limit->Product->Auction->find('count', array('conditions' => array('Auction.leader_id' => $user_id, 'Auction.end_time >' => $expiry_date))));

		$this->set('auctions', $this->Limit->Product->Auction->find('all', array('conditions' => array('Auction.leader_id' => $user_id, 'Auction.end_time >' => $expiry_date), 'contain' => array('Product' => 'Limit'), 'order' => array('Auction.end_time' => 'asc'))));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Limit->create();
			if ($this->Limit->save($this->data)) {
				$this->Session->setFlash(__('The limit has been added successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the limit please review the errors below and try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Limit', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Limit->save($this->data)) {
				$this->Session->setFlash(__('The limit has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem editing the limit please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Limit->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for limits', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Limit->del($id)) {
			$this->Session->setFlash(__('The limits was successfully deleted.', true));
			$this->redirect(array('action'=>'index'));
		}
	}
}
?>
