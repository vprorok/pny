<?php
class BidsController extends AppController {

	var $name = 'Bids';

	function beforeFilter(){
		parent::beforeFilter();

		if(isset($this->Auth)){
			$this->Auth->allow('histories', 'balance', 'unique');
		}
	}

	function histories($auction_id = null){
		Configure::write('debug', 0);
		$this->layout = 'js/ajax';

		if(!empty($auction_id)){
			$histories = $this->Bid->histories($auction_id, $this->appConfigurations['bidHistoryLimit']);
			$this->set('histories', $histories);
		}
	}

	function unique($auction_id = null) {
		$bids = $this->Bid->unique($auction_id);

		if(!empty($this->params['requested'])){
			return $bids;
		}else{
			Configure::write('debug', 0);
			$this->layout = 'js/ajax';
			$this->set('count', $bids);
		}
	}

	function index() {
		if($this->appConfigurations['simpleBids'] == false) {
			$this->paginate = array('conditions' => array('Bid.user_id' => $this->Auth->user('id')), 'limit' => 50, 'order' => array('Bid.id' => 'desc'), 'contain' => array('Auction' => array('Product')));
			$bids = $this->Bid->Auction->Product->Translation->translate($this->paginate());
			$this->set('bids', $bids);
		}

		$this->set('bidBalance', $this->User->Bid->balance($this->Auth->user('id')));

		$this->pageTitle = __('My Bids', true);
	}

	function admin_index() {
		$this->paginate = array('conditions' => array('Bid.auction_id > ' => 0, 'Bid.debit >' => 0, 'Bid.credit' => 0), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bid.id' => 'desc'), 'contain' => array('User', 'Auction' => 'Product'));
		$this->set('bids', $this->paginate());
	}

	function admin_auction($auction_id = null, $realBidsOnly = false) {
		if(empty($auction_id)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('controller' => 'auctions', 'action' => 'index'));
		}
		$auction = $this->Bid->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => 'Product'));

		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('controller' => 'auctions', 'action' => 'index'));
		}
		$this->set('auction', $auction);
		
		if(!empty($realBidsOnly)) {
			$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0);
			$this->set('realBidsOnly', $realBidsOnly);
		} else {
			$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0);
		}
		
		$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bid.id' => 'desc'), 'contain' => array('User', 'Auction' => 'Product'));
		$this->set('bids', $this->paginate());
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Bid->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Bid.user_id' => $user_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bid.created' => 'desc'), 'contain' => array('Auction' => array('Product')));
		$this->set('bids', $this->paginate());

		$this->set('bidBalance', $this->User->Bid->balance($user_id));
	}

	function admin_add($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Bid->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		if(!empty($this->data)) {
			$this->data['Bid']['user_id'] = $user_id;
			if(!empty($this->data['Bid']['total'])) {
				if($this->data['Bid']['total'] > 0) {
					$this->data['Bid']['credit'] = $this->data['Bid']['total'];
				} else {
					$this->data['Bid']['debit'] = $this->data['Bid']['total'] * -1;
				}
			}

			if($this->Bid->save($this->data)) {
				$this->Session->setFlash(__('The bid transaction has been added successfully.', true));
				$this->redirect(array('action' => 'user', $user_id));
			} else {
				$this->Session->setFlash(__('There was a problem adding the bid transaction please review the errors below and try again.', true));
			}
		}

		$this->set('user', $user);
	}

	function admin_delete($id = null) {
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid id for bid', true));
			$this->redirect(array('controller' => 'users', 'action'=>'index'));
		}
		$bid = $this->Bid->read(null, $id);
		if(empty($bid)) {
			$this->Session->setFlash(__('Invalid id for bid', true));
			$this->redirect(array('controller' => 'users', 'action'=>'index'));
		}

		if ($this->Bid->del($id)) {
			$this->Session->setFlash(__('The bid transaction was successfully deleted.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this bid transation', true));
		}
		$this->redirect(array('action'=>'user', $bid['Bid']['user_id']));
	}

	// this function is used to generate the User.bid_balance if simpleBids is set to true.
	// this works by checking the simpleBids is false.  Once this is run, set simpleBids to true.
	function admin_simple() {
		if($this->appConfigurations['simpleBids'] == false) {
			ini_set('max_execution_time', 0);

			$users = $this->User->find('all', array('conditions' => array('User.autobidder' => 0), 'contain' => ''));
			if(!empty($users)) {
				foreach($users as $user) {
					$user['User']['bid_balance'] = $this->Bid->balance($user['User']['id']);
					$this->User->save($user);
				}
			}
		}
	}
}
?>