<?php
class Smartbid extends AppModel {

	var $name = 'Smartbid';

	var $actsAs = array('Containable');

	var $belongsTo = array('Auction', 'User');
	
	function bidPlaced($auction_id = null, $user_id = null, $message = 'Single Bid') {
		$smartbid = $this->find('first', array('contain' => '', 'conditions' => array('auction_id' => $auction_id, 'user_id' => $user_id)));
		if(empty($smartbid)) {
			$smartbid['Smartbid']['auction_id'] = $auction_id;
			$smartbid['Smartbid']['user_id'] 	= $user_id;
			$smartbid['Smartbid']['message'] 	= $message;
			$this->create();
			$this->save($smartbid);
		} elseif(rand(1, 10) == 10) {
			// lets delete a bidder every so often	
			$this->del($smartbid['Smartbid']['id']);
		}
	}
}
?>