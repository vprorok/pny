<?php
class AuctionEmail extends AppModel {

	var $name = 'AuctionEmail';

	var $actsAs = array('Containable');

	var $belongsTo = 'Auction';
}
?>