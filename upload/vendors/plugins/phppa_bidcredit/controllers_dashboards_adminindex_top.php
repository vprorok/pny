//*** Make sure our database columns exist, if not, create them

$data=$this->User->query('DESCRIBE products');

$exists=false;
foreach ($data as $line) {
	if ($line['COLUMNS']['Field']=='auto_bid_credit') {
		$exists=true;
	}
}

if ($exists===FALSE) {
	$this->User->query('ALTER TABLE `products` ADD `auto_bid_credit` INT NOT NULL AFTER `buy_now` ;');
	$this->log('phppa_bidcredit:  Created auto_bid_credit column', 'plugins');

	//clear cache
	$this->_clearDir(TMP . DS . 'cache');
	$this->_clearDir(TMP . DS . 'cache' . DS . 'models');
}
