<?php
class BidbutlersController extends AppController {

	var $name = 'Bidbutlers';

	function index() {
		$this->paginate = array('conditions' => array('Auction.closed' => 0, 'Bidbutler.user_id' => $this->Auth->user('id')), 'limit' => 20, 'order' => array('Bidbutler.created' => 'desc'), 'contain' => array('Auction' => array('Product' => 'Image')));
		$bidbutlers = $this->Bidbutler->Auction->Product->Translation->translate($this->paginate());
		$this->set('bidbutlers', $bidbutlers);
		$this->pageTitle = __('My Bid Butlers', true);
	}

	function add($auction_id = null) {
		if (!$auction_id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid bid butler', true));
			$this->redirect(array('action'=>'index'));
		}
		$auction = $this->Bidbutler->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => 'Product'));
		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid auction', true));
			$this->redirect(array('action'=>'index'));
		}

		if(!empty($auction['Auction']['nail_bitter'])) {
			$this->Session->setFlash(__('This is a nail bitter auction, a bid butler cannot be added.', true));
			$this->redirect(array('controller' => 'auctions', 'action' => 'view', $auction_id));
		}

		$this->set('auction', $auction);
		
		// if the advanced mode is on, lets check that they haven't already placed a valid bid butler on this auction
		if($this->appConfigurations['bidButlerType'] == 'advanced') {
			$bidbutlerCount = $this->Bidbutler->find('count', array('conditions' => array('Bidbutler.auction_id' => $auction_id, 'Bidbutler.user_id' => $this->Auth->user('id'), 'Bidbutler.maximum_price >' => $auction['Auction']['price'], 'Bidbutler.bids >' => 0)));
			if($bidbutlerCount > 0) {
				$this->Session->setFlash(__('You already have a valid bid butler on this auction you cannot add another one until the existing bid butler is used.', true), 'default', array('class' => 'success'));
				$this->redirect(array('controller' => 'auctions', 'action' => 'view', $auction['Auction']['id']));
			}
		}
		
		if (!empty($this->data)) {
			$this->data['Bidbutler']['user_id'] = $this->Auth->user('id');
			$this->data['Bidbutler']['auction_id'] = $auction_id;
			$this->data['Bidbutler']['balance'] = $this->Bidbutler->Auction->Bid->balance($this->Auth->user('id'));

			$this->Bidbutler->create();
			if ($this->Bidbutler->save($this->data)) {
				if($this->appConfigurations['bidButlerType'] == 'advanced') {
					//if($this->appConfigurations['simpleBids'] == true) {
					//	$user['User']['id'] = $this->Auth->user('id');
					//	$user['User']['bid_balance'] += $this->data['Bidbutler']['bids'] * $auction['Auction']['bid_debit'];
					//	$this->User->save($user);
					//} else {
					//	// lets take the credit now
					//	$bid['Bid']['user_id'] 	  = $this->data['Bidbutler']['user_id'];
					//	$bid['Bid']['credit']     = 0;
					//	$bid['Bid']['debit']      = $this->data['Bidbutler']['bids'] * $auction['Auction']['bid_debit'];
					//	$bid['Bid']['description'] = __('Bid Butler Credits', true);
                              //
					//	$this->User->Bid->create();
					//	$this->User->Bid->save($bid);
					//}
				}
				$this->Session->setFlash(__('The bid butler has been added successfully.', true), 'default', array('class' => 'success'));
				$this->redirect(array('controller' => 'auctions', 'action' => 'view', $auction['Auction']['id']));
			} else {
				$this->Session->setFlash(__('There was a problem adding the bid butler please review the errors below and try again.', true));
			}
		}
		$this->pageTitle = __('Add a Bid Butler', true);
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid bid butler', true));
			$this->redirect(array('action'=>'index'));
		}

		$bidbutler = $this->Bidbutler->find('first', array('conditions' => array('Bidbutler.id' => $id), 'contain' => array('Auction' => array('Product'))));
		if(empty($bidbutler)) {
			$this->Session->setFlash(__('Invalid bid butler', true));
			$this->redirect(array('action'=>'index'));
		}
		if($bidbutler['Bidbutler']['user_id'] !== $this->Auth->user('id')) {
			$this->Session->setFlash(__('Invalid bid butler', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('bidbutler', $bidbutler);

		if (!empty($this->data)) {
			$this->data['Bidbutler']['user_id'] = $this->Auth->user('id');
			$this->data['Bidbutler']['auction_id'] = $bidbutler['Bidbutler']['auction_id'];
			$this->data['Bidbutler']['balance'] = $this->Bidbutler->Auction->Bid->balance($this->Auth->user('id'));
			if ($this->Bidbutler->save($this->data)) {
				$this->Session->setFlash(__('The bid butler has been updated successfully.', true), 'default', array('class' => 'success'));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem updating the bid butler please review the errors below and try again.', true));
			}
		} else {
			$this->data = $bidbutler;
		}
		$this->pageTitle = __('Edit a Bid Butler', true);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Bid butler', true));
			$this->redirect(array('action'=>'index'));
		}
		$bidbutler = $this->Bidbutler->read(null, $id);
		if(empty($bidbutler)) {
			$this->Session->setFlash(__('Invalid bid butler', true));
			$this->redirect(array('action'=>'index'));
		}
		if($bidbutler['Bidbutler']['user_id'] == $this->Auth->user('id')) {
			if ($this->Bidbutler->del($id)) {
				$this->Session->setFlash(__('Your bid butler was successfully deleted.', true), 'default', array('class' => 'success'));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem deleting the bid butler please try again.', true));
				$this->redirect(array('action'=>'index'));
			}
		} else {
			$this->Session->setFlash(__('Invalid Bid butler', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Bidbutler->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Bidbutler.user_id' => $user_id), 'contain' => array('User', 'Auction' => 'Product'), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bidbutler.created' => 'desc'));
		$this->set('bidbutlers', $this->paginate());
	}

	function admin_delete($id = null) {
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid id for bid butler', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$bidbutler = $this->Bidbutler->read(null, $id);
		if(empty($bidbutler)) {
			$this->Session->setFlash(__('Invalid id for bid butler', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		if ($this->Bidbutler->del($id)) {
			$this->Session->setFlash(__('The bid butler was successfully deleted.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this bid butler.', true));
		}
		$this->redirect(array('action'=>'user', $bidbutler['Bidbutler']['user_id']));
	}
}
?>