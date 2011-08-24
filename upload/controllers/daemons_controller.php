<?php
class DaemonsController extends AppController {

	var $name = 'Daemons';

	var $uses = array('Auction', 'Setting', 'Reminder');

	var $layout = 'js/ajax';

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)) {
			$this->Auth->allow('cleaner', 'reminder', 'winner');
		}
		ini_set('max_execution_time', ($this->appConfigurations['cronTime'] * 60) + 1);
	}

	function cleaner() {
		$this->autoLayout = false;
		$this->autoRender = false;
		Configure::write('debug', 0);

		if(Cache::read('cleaner.pid')) {
			return false;
		} else {
			Cache::write('cleaner.pid', microtime(), $this->appConfigurations['cronTime'] * 50);
		}

		ini_set('max_execution_time', 0);

		$auctions = $this->Auction->find('all', array('conditions' => array('Auction.closed' => 1), 'contain' => '', 'limit' => 100, 'order' => 'RAND()', 'fields' => 'id'));
		if(!empty($auctions)) {
			foreach($auctions as $auction) {
				$bids = $this->Auction->Bid->find('all', array('conditions' => array('Bid.auction_id' => $auction['Auction']['id']), 'contain' => 'User', 'order' => array('Bid.id' => 'desc'), 'fields' => array('Bid.id', 'User.autobidder')));
				if(!empty($bids)) {
					$count = 0;
					foreach($bids as $bid) {
						if($count >= $this->appConfigurations['bidHistoryLimit']) {
							if($this->appConfigurations['simpleBids'] == true) {
								$this->Auction->Bid->delete($bid['Bid']['id']);
							} elseif(!empty($bid['User']['autobidder'])) {
								$this->Auction->Bid->delete($bid['Bid']['id']);
							}
						} else {
							$count += 1;
						}
					}
				}
			}
		}

		// now delete messages from the messages table
		$this->Auction->Message->deleteAll(array('Message.id >' => 0));

		// now the auction and bid cleaner stuff
		if(!empty($this->appConfigurations['cleaner']['active'])) {

			$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['cleaner']['clear'] * 24 * 60 * 60));

			ini_set('max_execution_time', 0);

			$auctions = $this->Auction->find('all', array('conditions' => array('Auction.end_time <' => $expiry_date, 'Auction.closed' => 1, 'Auction.status_id' => 0), 'contain' => ''));

			if(!empty($auctions)) {
				foreach($auctions as $auction) {
					$bids = $this->Auction->Bid->find('all', array('conditions' => array('Bid.auction_id' => $auction['Auction']['id']), 'contain' => ''));
					if($this->appConfigurations['simpleBids'] == false) {
						if(!empty($bids)) {
							foreach($bids as $bid) {
								$archived = $this->Auction->Bid->find('first', array('conditions' => array('Bid.description' => __('Archived Bids', true), 'Bid.user_id' => $bid['Bid']['user_id']), 'contain' => ''));
								if(!empty($archived)) {
									$archived['Bid']['debit'] 	+= $bid['Bid']['debit'];
									$archived['Bid']['created'] = $expiry_date;

									$this->Auction->Bid->save($archived);
								} else {
									$archive['Bid']['user_id'] 		= $bid['Bid']['user_id'];
									$archive['Bid']['description']  = __('Archived Bids', true);
									$archive['Bid']['debit'] 		= $bid['Bid']['debit'];
									$archive['Bid']['created'] 		= $expiry_date;

									$this->Auction->Bid->create();
									$this->Auction->Bid->save($archive);
								}

								$this->Auction->Bid->delete($bid['Bid']['id']);
							}
						}
					}

					$this->Auction->delete($auction['Auction']['id']);
				}
			}
		}
		
		//Auction clean_all
		if(!empty($this->appConfigurations['cleaner']['active']) && intval($this->appConfigurations['cleaner']['clear_all'])>0) {
			$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['cleaner']['clear_all'] * 24 * 60 * 60));

			ini_set('max_execution_time', 0);

			$auctions = $this->Auction->find('all', array('conditions' => array('Auction.end_time <' => $expiry_date), 'contain' => ''));

			if(!empty($auctions)) {
				foreach($auctions as $auction) {
					$bids = $this->Auction->Bid->find('all', array('conditions' => array('Bid.auction_id' => $auction['Auction']['id'])));
					if($this->appConfigurations['simpleBids'] == false) {
						if(!empty($bids)) {
							foreach($bids as $bid) {
								$archived = $this->Auction->Bid->find('first', array('conditions' => array('Bid.description' => __('Archived Bids', true), 'Bid.user_id' => $bid['Bid']['user_id']), 'contain' => ''));
								if(!empty($archived)) {
									$archived['Bid']['debit'] 	+= $bid['Bid']['debit'];
									$archived['Bid']['created'] = $expiry_date;

									$this->Auction->Bid->save($archived);
								} else {
									$archive['Bid']['user_id'] 		= $bid['Bid']['user_id'];
									$archive['Bid']['description']  = __('Archived Bids', true);
									$archive['Bid']['debit'] 		= $bid['Bid']['debit'];
									$archive['Bid']['created'] 		= $expiry_date;

									$this->Auction->Bid->create();
									$this->Auction->Bid->save($archive);
								}

								$this->Auction->Bid->delete($bid['Bid']['id']);
							}
						}
					}
					$this->Auction->delete($auction['Auction']['id']);
				}
			}
		}

		Cache::delete('cleaner.pid');
	}

	function reminder(){
		$reminders = $this->Reminder->find('all', array('conditions' => array('Auction.start_time < ' => date('Y-m-d H:i:s'))));

		if(!empty($reminders)){
			foreach($reminders as $reminder){
				$data = array();

				$data['to']       = $reminder['User']['email'];
				$data['subject']  = __('The auction has started', true);
				$data['template'] = 'reminders/add';
				$data['User']     = $reminder['User'];
				$data['Auction']  = $reminder['Auction'];

				if($this->_sendEmail($data)){
					//$this->log('Send a reminder email to '.$data['to'].' succeeded');
					// Delete reminder upon successful reminder email send
					$this->Reminder->delete($reminder['Reminder']['id']);
				}else{
					//$this->log('Failed while sending a reminder email to '.$data['to']);
					// Schedule this email send failed to be send on the next call
					// So we leave the reminder
				}

				// Delay for a while
				usleep(100);
			}
		}
	}
	
	/**
	 * The function sends the email off to the winner of an auction
	 *
	 * @return n/a
	 */
	function winner() {
		$this->autoLayout = false;
		$this->autoRender = false;
		Configure::write('debug', 0);
		
		if(Cache::read('winner.pid')) {
			return false;
		} else {
			Cache::write('winner.pid', microtime(), $this->appConfigurations['cronTime'] * 50);
		}
		
		
		$auctions = $this->Auction->AuctionEmail->find('all', array('contain' => array('Auction' => array('Product', 'Winner'))));
		if(!empty($auctions)) {
			foreach ($auctions as $auction) {
				if(!empty($auction['Auction']['closed'])) {
					$data['Auction'] 			   = $auction['Auction'];
					$data['Product'] 			   = $auction['Auction']['Product'];
					$data['User'] 				   = $auction['Auction']['Winner'];
					
					if($this->appConfigurations['emailWinner'] == true) {
						// send the email to the winner
						$data['to'] 				   = $auction['Auction']['Winner']['email'];
						$data['subject'] 			   = sprintf(__('%s - You have won an auction', true), $this->appConfigurations['name']);
						$data['template'] 			   = 'auctions/won_auction';
						$this->_sendEmail($data);
						echo "Emailing ".$data['to']."<br>";
					}
				
					// send the email to the website owner
					$data['to'] 				   = $this->appConfigurations['email'];
					$data['subject'] 			   = sprintf(__('%s - You have sold an auction', true), $this->appConfigurations['name']);
					$data['template'] 			   = 'auctions/sold_auction';
					$this->_sendEmail($data);
				}
				
				$this->Auction->AuctionEmail->del($auction['AuctionEmail']['id']);
			}
		}
		Cache::delete('winner.pid');
	}
}
?>
