<?php
class WatchlistsController extends AppController {

	var $name = 'Watchlists';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->paginate = array('conditions' => array('User.id' => $this->Auth->user('id')), 'limit' => 50, 'order' => array('Auction.end_time' => 'asc'), 'contain' => array('User', 'Auction' => array('Product' => 'Image')));
		$this->set('watchlists', $this->paginate());
	}

	function add($auction_id = null) {
		if(!empty($auction_id)){
			$watchlist = $this->Watchlist->find('first', array('conditions' => array('Auction.id' => $auction_id, 'User.id' => $this->Auth->user('id'))));

			if(empty($watchlist)){
				$watchlist['Watchlist']['auction_id'] = $auction_id;
				$watchlist['Watchlist']['user_id'] 	  = $this->Auth->user('id');
				
				if($this->Watchlist->save($watchlist)){
					$this->Session->setFlash(__('The auction has been added to your watch list.', true), 'default', array('class' => 'success'));
					$this->redirect($this->referer('/auctions/view/'.$auction_id));
				}else{
					$this->Session->setFlash(__('The auction cannot be added to the watchlist.', true));
					$this->redirect($this->referer('/auctions/view/'.$auction_id));
				}
			}else{
				$this->Session->setFlash(__('The auction is already in your watchlist.', true));
				$this->redirect($this->referer('/auctions/view/'.$auction_id));
			}
		}else{
			$this->Session->setFlash(__('Invalid auction.', true));
			$this->redirect($this->referer('/auctions/view/'.$auction_id));
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Watchlist', true));
			$this->redirect($this->referer('/'));
		}
		if ($this->Watchlist->del($id)) {
			$this->Session->setFlash(__('The auction has been deleted from your watchlist.', true), 'default', array('class' => 'success'));
			$this->redirect($this->referer('/watchlists/index'));
		}
	}
}
?>