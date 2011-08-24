if ($auction['Product']['auto_bid_credit']>0) {
	//credit the user for the bids

	$description=__('Bids won', true). ' in Auction #'.$auction['Auction']['id'];
	$credit=$auction['Product']['auto_bid_credit'];
	$debit=0;

	$bid['Bid']['user_id']     = $this->Auth->user('id');
	$bid['Bid']['description'] = $description;
	$bid['Bid']['credit']      = $credit;
	$bid['Bid']['debit']       = $debit;

	$this->Bid->create();

	$this->Bid->save($bid);
}
