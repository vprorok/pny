<?php
echo $rss->items($auctions, 'transformRSS');

function transformRSS($data) {
	App::import('Helper', array('Number', 'Time'));
	$number = new NumberHelper();
	$time = new TimeHelper();

	$price = !empty($data['Product']['fixed']) ? $data['Product']['fixed_price'] : $data['Auction']['price'];
	$description = '【Current Price】'.$number->currency($price, Configure::read('App.currency'));

	$description .= '<br/>【Latest Bidder】'.$data['LastBid']['username'];
	$description .= '<br/>【Closed time】'.$time->nice($data['Auction']['end_time']);
	$description .= '<br/><br/>'.$data['Product']['brief'];

	return array(
		'title' => $data['Product']['title'],
		'link' => '/auctions/view/'.$data['Auction']['id'],
		'guid' => '/auctions/view/'.$data['Auction']['id'],
		'description' => $description,
		'author' => Configure::read('App.email'),
		'pubDate' => $data['Auction']['start_time']
	);
}
?>