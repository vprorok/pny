<?php

class AuctionsController extends AppController {

	var $name = 'Auctions';
	var $uses = array('Auction', 'Setting', 'Country', 'Bid', 'Users', 'SettingIncrement');
	var $components = array('PaypalProUk', 'Epayment', 'Twitter');
	var $helpers = array('Epayment');

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)) {
			$this->Auth->allow('test', 'popup', 'index', 'view', 'live', 'home', 'future', 'closed', 'winners', 'getcount', 'getstatus', 'ipn', 'latestwinner', 'gettickerauctions', 'endingsoon', 'getfutureauctions', 'getfeatured', 'getauctions', 'getwinners', 'creditcard', 'credits', 'getendedlist', 'free', 'search', 'timeout');
		}
		
		
		
	}

	function _getStringTime($timestamp){
		$diff 	= $timestamp - time();
		if($diff < 0) $diff = 0;

		$day    = floor($diff / 86400);
		if($day < 1){
			$day = '';
		}else{
			$day = $day.'d';
		}

		$diff   -= $day * 86400;

		$hour   = floor($diff / 3600);
		if($hour < 10) $hour = '0'.$hour;
		$diff   -= $hour * 3600;

		$minute = floor($diff / 60);
		if($minute < 10) $minute = '0'.$minute;
		$diff   -= $minute * 60;

		$second = $diff;
		if($second < 10) $second = '0'.$second;

		return trim($day.' '.$hour.':'.$minute.':'.$second);
	}

	function getcount($type = null)	{
		if($type == 'live') {
			$count = $this->Auction->find('count', array('conditions' => "start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . $this->getEndTime() . "'"));
		} elseif($type == 'comingsoon') {
			$count = $this->Auction->find('count', array('conditions' => "start_time > '" . date('Y-m-d H:i:s') . "'"));
		} elseif($type == 'closed') {
			$count = $this->Auction->find('count', array('conditions' => array('closed' => 1)));
		}

		return $count;
	}

	function home() {
		$exclude=array();
		
		//*** Auctions ending soon
		if (Configure::read('App.disableAuctionCache') || ($auctions_end_soon=Cache::read('auctions_end_soon', 'minute'))===FALSE) {
			$auctions_end_soon=$this->Auction->find('all', array(	'conditions'=>array(	'Auction.end_time > '=>$this->getEndTime(),
																'Auction.start_time <='=>date('Y-m-d H:i:s'),
																'Auction.active'=>1,
																'Auction.id NOT'=>$exclude
																),
												'contain'=>array('Product' => array('Image' => 'ImageDefault'), 'Leader'),
												'fields'=>array('Auction.*','Product.*','Leader.username'),
												'limit'=>Configure::read('App.homeEndingLimit'),
												'order'=>array('Auction.end_time'=>'ASC')));
			Cache::write('auctions_end_soon', $auctions_end_soon, 'minute');
		}
		$this->set('auctions_end_soon', $auctions_end_soon);
		$exclude+=$this->Auction->getExcludeIDs($auctions_end_soon);
										
		//*** Live auctions
		if (Configure::read('App.disableAuctionCache') || ($auctions_live=Cache::read('auctions_live', 'minute'))===FALSE) {
			$auctions_live=$this->Auction->find('all', array(	'conditions'=>array(	'Auction.end_time > '=>$this->getEndTime(),
																'Auction.start_time <='=>date('Y-m-d H:i:s'),
																'Auction.active'=>1,
																'NOT'=>array('Auction.id'=>$exclude)
																),
												'contain'=>array('Product' => array('Image' => 'ImageDefault'), 'Leader'),
												'fields'=>array('Auction.*','Product.*','Leader.username'),
												'limit'=>Configure::read('App.homeFeaturedLimit'),
												'order'=>array('Auction.end_time'=>'ASC')));
																									
			Cache::write('auctions_live', $auctions_live, 'minute');
		}
		$this->set('auctions_live', $auctions_live);
			
			
		//*** Latest winner
		if (($latest_winner=Cache::read('latest_winner', 'minute'))===FALSE) {
			$latest_winner=$this->Auction->find('first', array(	'conditions'=>array(	'Auction.winner_id <>'=>0,
																'Auction.closed_status <>'=>2,
															),
												'contain'=>array('Product' => array('Image' => 'ImageDefault'), 'Winner'),
												'fields'=>array('Auction.*','Product.*','Winner.username'),
												'order'=>array('Auction.end_time'=>'DESC')));

			Cache::write('latest_winner', $latest_winner, 'minute');
		}
	
		$this->set('latest_winner', $latest_winner);
		
		
		//*** Future auctions
		if (Configure::read('Settings.home_page_future_auctions')) {
			if (($future_auctions=Cache::read('future_auctions', 'minute'))===FALSE) {
				$future_auctions=$this->Auction->find('all', array(	'conditions'=>array(	'Auction.active'=>1,
																	'Auction.start_time >'=>date('Y-m-d H:i:s')
																),
													'contain'=>array('Product' => array('Image' => 'ImageDefault'), 'Winner'),
													'fields'=>array('Auction.*','Product.*','Winner.username'),
													'order'=>array('Auction.end_time'=>'DESC'),
													'limit'=>Configure::read('App.homeFeaturedLimit')));
				Cache::write('future_auctions', $future_auctions, 'minute');
				//pr($future_auctions);exit;
			}
			$this->set('future_auctions', $future_auctions);
		}
		
	}

	function getendedlist() {
		$auctions = array();

		$endingSoon  = $this->Auction->getAuctions(array("Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . $this->getEndTime() . "'", 'Auction.active' => 1), $this->appConfigurations['homeEndingLimit'], 'Auction.end_time ASC');
		if(!empty($endingSoon)) {
			foreach($endingSoon as $endSoon) {
				$auctions[] = $endSoon['Auction']['id'];
			}
		}

		return $auctions;
	}

	function getauctions($limit = null, $start_hour = null, $end_hour = null, $order = 'asc', $exclude = array()) {
		if(!empty($exclude)) {
			$exclude = explode(',', $exclude);
		}

		if(!empty($start_hour) && !empty($end_hour)) {
			$conditions = array('closed' => 0, 'Auction.active' => 1, 'Auction.end_time >=' => date('Y-m-d H:i:s', time() + $start_hour * 3600), 'Auction.end_time <=' => date('Y-m-d H:i:s', time() + $end_hour * 3600));
		} elseif(!empty($start_hour)) {
			$conditions = array('closed' => 0, 'Auction.active' => 1, 'Auction.end_time >=' => date('Y-m-d H:i:s', time() + $start_hour * 3600));
		} elseif(!empty($end_hour)) {
			$conditions = array('closed' => 0, 'Auction.active' => 1, 'Auction.end_time <=' => date('Y-m-d H:i:s', time() + $end_hour * 3600));
		} else {
			$conditions = array('closed' => 0, 'Auction.active' => 1);
		}

		$auctions = $this->Auction->find('all', array('conditions' => $conditions, 'contain' => '', 'fields' => array('Auction.id', 'Auction.closed', 'Auction.active', 'Auction.end_time'), 'limit' => $limit, 'order' => array('Auction.end_time' => $order)));

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				if(!in_array($auction['Auction']['id'], $exclude)) {
					$auctions[$key] = $auction;
				} else {
					unset($auctions[$key]);
				}
			}
		}

		return $auctions;
	}

	function getfutureauctions($limit = 5) {
		return $this->Auction->getAuctions(array("Auction.start_time > '" . date('Y-m-d H:i:s') . "'", 'Auction.active' => 1), $limit, 'Auction.end_time ASC');
	}

	function getfeatured() {
		$featured  = $this->Auction->getAuctions(array("Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . $this->getEndTime() . "'", 'Auction.active' => 1, 'Auction.featured' => 1), 1, 'Auction.end_time ASC');

		// if there are no featured auctions, lets just get a closing soon auction
		if(empty($featured)) {
			$featured  = $this->Auction->getAuctions(array("Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . $this->getEndTime() . "'", 'Auction.active' => 1), 1, 'Auction.end_time ASC');
		}

		if(!empty($featured['Auction']['image'])){
			if(!empty($featured['Product']['Image'][0]['ImageDefault'])) {
				$featured['Auction']['image'] = 'default_images/'.Configure::read('App.serverName').'/max/'.$featured['Product']['Image'][0]['ImageDefault']['image'];
				$featured['Auction']['thumb'] = 'default_images/'.Configure::read('App.serverName').'/thumbs/'.$featured['Product']['Image'][0]['ImageDefault']['image'];
			} else {
				$featured['Auction']['image'] = 'product_images/max/'.$featured['Product']['Image'][0]['image'];
				$featured['Auction']['thumb'] = 'product_images/thumbs/'.$featured['Product']['Image'][0]['image'];
			}
		}

		return $featured;
	}

	function getwinners($limit = 10) {
		return $this->Auction->getAuctions(array('Auction.winner_id >' => 0, 'Auction.closed' => 1), $limit, 'Auction.end_time DESC');
	}

	function gettickerauctions($limit = 5, $live = false) {
		if($live == true) {
			return $this->Auction->getAuctions(array("Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . $this->getEndTime() . "'", 'Auction.active' => 1), $limit, 'Auction.end_time ASC');
		} else {
			return $this->Auction->getAuctions(array('closed' => 1, 'Auction.active' => 1, 'Auction.winner_id > ' => 0), $limit, 'Auction.end_time DESC');
		}
	}

	function index() {
		//$this->paginate = array('contain' => '', 'conditions' => array("start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . $this->getEndTime() . "'", 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'asc'));
		$this->paginate = array(	'contain' => '', 
						'conditions' => array(	'end_time > '=>$this->getEndTime(), 
									'Auction.active' => 1),
						'limit' => $this->appConfigurations['pageLimit'], 
						'order' => array('Auction.end_time' => 'asc'));
		
		$auctions = $this->paginate();
		
		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$isFeed = ife($this->RequestHandler->prefers('rss') == 'rss', true, false);
		if($isFeed){
			$this->set('channel', array('title' => 'Live Auctions on '.$this->appConfigurations['name'], 'description' => 'Live Auctions on '.$this->appConfigurations['name'].' which available for bidding.'));
		}else{
			$this->pageTitle = __('Live Auctions', true);
		}

		$this->set('auctions', $auctions);
	}

	function future() {
		$this->paginate = array('contain' => '', 'conditions' => array("start_time > '" . date('Y-m-d H:i:s') . "'", 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'asc'));
		$auctions = $this->paginate();

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$this->set('auctions', $auctions);
		$this->pageTitle = __('Coming Soon Auctions', true);
		$this->set('future', 1);
	}

	function closed() {
		if(!empty($this->appConfigurations['endedLimit'])) {
			$this->paginate = array('contain' => '', 'conditions' => array('closed' => 1, 'closed_status <>'=>2, 'Auction.active' => 1), 'limit' => $this->appConfigurations['endedLimit'], 'order' => array('Auction.end_time' => 'desc'));
			$auctions = $this->paginate();
		} else {
			$this->paginate = array('contain' => '', 'conditions' => array('closed' => 1, 'closed_status <>'=>2, 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'desc'));
			$auctions = $this->paginate();
		}

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$this->set('auctions', $auctions);
		$this->pageTitle = __('Closed Auctions', true);
	}

	function winners() {
		$this->paginate = array('contain' => '', 'conditions' => array('winner_id >' => 0, 'Auction.closed' => 1, 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'desc'));
		$auctions = $this->paginate();

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$isFeed = ife($this->RequestHandler->prefers('rss') == 'rss', true, false);
		if($isFeed){
			$this->set('channel', array('title' => 'Latest Auctions Winner on '.$this->appConfigurations['name'], 'description' => 'Last Auctions Winners on '.$this->appConfigurations['name']));
		}else{
			$this->pageTitle = __('Winners', true);
		}

		$this->set('auctions', $auctions);
	}

	function credits($bid_debit = null) {
		$this->paginate = array(	'contain' => '', 
							'conditions' => array(
								"start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . $this->getEndTime() . "'", 
								'Auction.active' => 1, 
								'Auction.bid_debit' => $bid_debit), 
							'limit' => $this->appConfigurations['pageLimit'], 
							'order' => array('Auction.end_time' => 'asc'));
		$auctions = $this->paginate();

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$this->set('auctions', $auctions);
		$this->set('bid_debit', $bid_debit);
		$this->pageTitle = $bid_debit.__(' Credit Auctions', true);
	}

	function free() {
		$this->paginate = array(	'contain' => 'Product', 
							'conditions' => array(
								"start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . $this->getEndTime() . "'", 
								'Auction.active' => 1, 
								'Auction.free' => 1), 
							'limit' => $this->appConfigurations['pageLimit'], 
							'order' => array('Auction.end_time' => 'asc'));
		$auctions = $this->paginate();

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$this->set('auctions', $auctions);
		$this->pageTitle = __('Free Auctions', true);
	}

	function search($search = null) {
		if(!empty($this->data['Auction']['search'])) {
			$this->redirect('/auctions/search/'.$this->data['Auction']['search']);
		}

		if(!empty($search)) {
			$this->paginate = array(	'contain' => 'Product', 
								'conditions' => array(	"(Product.title LIKE '%$search%' OR Product.description LIKE '%$search%') AND start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . $this->getEndTime() . "'", 
												'Auction.active' => 1), 
								'limit' => $this->appConfigurations['pageLimit'], 
								'order' => array('Auction.end_time' => 'asc'));
			$auctions = $this->paginate();

			if(!empty($auctions)) {
				foreach($auctions as $key => $auction) {
					$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
					$auctions[$key] = $auction;
				}
			}

			$this->set('auctions', $auctions);
			$this->pageTitle = __('Free Auctions', true);

			$this->set('search', $search);
		} else {
			$this->Session->setFlash(__('You did not enter anything to search for.', true));
			$this->redirect('/');
		}
	}

	function view($id = null) {
		if (!Configure::read('App.disableSlugs')) {
			if (is_numeric($id)) {
				//old style, redirect
				$auction=$this->Auction->findById($id);
				$this->redirect($this->AuctionLink($id, $auction['Product']['title']));
			} else {
				preg_match('/([0-9]+)$/U', $id, $matches);
				$id=$matches[0];
			}
		}
		
		if($this->appConfigurations['uniqueAuctionLayout'] == true) {
			$this->layout = 'auction_view';                                             
		}

		if(empty($id)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('action' => 'index'));
		}

		$auction = $this->Auction->getAuctions(array('Auction.id' => $id), 1, null, null, 'max');

		if (empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('action' => 'index'));
		}

		//adjust the buy-it-now price depending on configuration
		$auction['Product']['buy_now']=$this->Auction->binPrice($auction['Auction']['id'], $auction['Product']['buy_now'], $this->Auth->user('id'));
		
		if ($this->Auction->canBuyNow($auction, $this->Auth->user('id'))) {
			$this->set('buy_it_now', true);
		} else {
			$this->set('buy_it_now', false);
		}
		
		$this->set('auction', $auction);
		$this->set('watchlist', $this->Auction->Watchlist->find('first', array('conditions' => array('Auction.id' => $id, 'User.id' => $this->Auth->user('id')))));

		//$options['include_amount']['price_increment'] = $this->requestAction('/settings/get/price_increment/'.$id.'/0');
		$options['include_amount']['price_increment']=0;
		
		$this->set('bidHistories', $this->Auction->Bid->histories($auction['Auction']['id'], $this->appConfigurations['bidHistoryLimit'], $options));

		$this->set('parents', $this->Auction->Product->Category->getpath($auction['Product']['category_id']));
		$this->set('bidIncrease', $this->__getSetting('price_increment', $id));
		$this->set('timeIncrease', $this->__getSetting('time_increment', $id));

		$this->pageTitle = $auction['Product']['title'];
		if(!empty($auction['Product']['meta_description'])) {
			$this->set('meta_description', $auction['Product']['meta_description']);
		}
		if(!empty($auction['Product']['meta_keywords'])) {
			$this->set('meta_keywords', $auction['Product']['meta_keywords']);
		}

		$hits['Auction']['id'] = $auction['Auction']['id'];
		$hits['Auction']['hits'] = $auction['Auction']['hits'] + 1;
		$this->Auction->save($hits);
	}

	function __getSetting($name = null, $auction_id = null, $cache = true) {
		if($cache == true) {
			$setting = $this->Setting->get($name);
		}
		if(!empty($setting)) {
			return $setting;
		} else {
			// lets check our dynamic settings
			if(Configure::read('App.bidIncrements') == 'single') {
				$settings = $this->SettingIncrement->find('first');
			} elseif(Configure::read('App.bidIncrements') == 'dynamic') {
				$auction = $this->SettingIncrement->Product->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => '', 'fields' => 'Auction.price'));
				$settings = $this->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.lower_price <=' => $auction['Auction']['price'], 'SettingIncrement.upper_price >' => $auction['Auction']['price'])));

				if(empty($settings)) {
					// lets check to see if the price is in the upper region
					$settings = $this->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.lower_price <=' => $auction['Auction']['price'], 'SettingIncrement.upper_price' => '0.00')));
				}
				
				if(empty($settings)) {
					// lets check to see if the price is in the upper region
					$settings = $this->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.lower_price' => '0.00', 'SettingIncrement.upper_price >' => $auction['Auction']['price'])));
				}
				
				if(empty($settings)) {
					// finally if it fits none of them for some strange reason
					$settings = $this->SettingIncrement->find('first');
				}
			} elseif(Configure::read('App.bidIncrements') == 'product') {
				$auction = $this->SettingIncrement->Product->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => '', 'fields' => 'Auction.product_id'));
				$settings = $this->SettingIncrement->find('first', array('conditions' => array('SettingIncrement.product_id' => $auction['Auction']['product_id']), 'recursive' => -1));
			}

			return $settings['SettingIncrement'][$name];
			//if(!empty($this->params['requested'])) {
			//	return $settings['SettingIncrement'][$name];
			//} else {
				//$this->layout = 'js/ajax';
				//echo $settings['SettingIncrement'][$name];
			//}
		}
	}

	function popup($layout = null, $view = 'popup'){
		if(empty($layout)){
			$this->layout = 'auction_view';
		}else{
			$this->layout = $layout;
		}

		$this->render($view);
	}

	function endingsoon($id = null, $limit = 5) {
		return $this->Auction->getAuctions(array('Auction.id <> '.$id, "Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . $this->getEndTime() . "'"), $limit, array('Auction.end_time ASC'));
	}

	function won() {
		$this->paginate = array('conditions' => array('Auction.winner_id' => $this->Auth->user('id')), 'limit' => 50, 'order' => array('Auction.end_time' => 'desc'), 'contain' => array('Product' => 'Image', 'Status'));
		$auctions = $this->Auction->Product->Translation->translate($this->paginate());
		$this->set('auctions', $auctions);

		$this->pageTitle = __('Won Auctions', true);
	}

	function creditcard($id = null, $currency_code = null, $redirect = null, $user_id = null) {
		$this->pageTitle = __('Pay for an Auction', true);
		if (empty($id)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'won'));
		}

		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'won'));
		}
		if($auction['Auction']['winner_id'] !== $this->Auth->user('id')) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'won'));
		}

		if($auction['Auction']['status_id'] != 1) {
			$this->Session->setFlash(__('You have already paid for this auction.', true));
			$this->redirect(array('action'=>'won'));
		}

		$this->set('auction', $auction);

		if(!empty($currency_code)) {
			$currency = $this->Currency->find('first', array('fields' => 'rate', 'conditions' => array('Currency.currency' => $currency_code)));

	        if(!empty($currency)){
	            Configure::write('App.currency', $currency_code);
	            $this->set('currency_code', $currency_code);
	            $this->appConfigurations['currency'] = $currency_code;
	           	$this->set('appConfigurations', $this->appConfigurations);
	        } else {
	        	$this->Session->setFlash(__('Invalid Currency', true));
				$this->redirect(array('action'=>'won'));
	        }
		}

		if(!empty($redirect)) {
			$this->set('redirect', $redirect);
		}


		if($this->Auth->user('id')) {
			$user = $this->Auction->Winner->read(null, $this->Auth->user('id'));
		} else {
			$user = $this->Auction->Winner->read(null, $user_id);
		}

		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('user', $user);
		$this->set('countries', $this->Country->find('list', array('fields' => array('Country.code', 'Country.name'))));
		$this->set('ccTypes', array('Visa' => 'Visa', 'MasterCard' => 'MasterCard', 'Amex' => 'Amex', 'Discover' => 'Discover', 'Solo' => 'Solo', 'Maestro' => 'Maestro'));

		$addresses 	 	 = $this->Auction->Winner->Address->addressTypes();
		$userAddress 	 = array();
		$addressRequired = 0;

		if(!empty($addresses)) {
			foreach($addresses as $address_type_id=>$address) {
				$userAddress[$address] = $this->Auction->Winner->Address->find('first', array('conditions' => array('Address.user_id' => $this->Auth->user('id'), 'Address.user_address_type_id' => $address_type_id)));
				if(empty($userAddress[$address])) {
					$addressRequired = 1;
				}
			}
		}

		if(!empty($addressRequired)) {
			$this->Session->setFlash(__('Please enter in your address details.', true));
			$this->redirect(array('action'=>'won'));
		}

		if(!empty($auction['Product']['fixed'])) {
			$total = $auction['Product']['fixed_price'] + $auction['Product']['delivery_cost'];
		} else {
			$total = $auction['Auction']['price'] + $auction['Product']['delivery_cost'];
		}

		if(!empty($this->appConfigurations['credits']['active'])) {
			$credits = $this->Auction->Credit->balance($this->Auth->user('id'), $this->appConfigurations['credits']['expiry']);
			$orignal = $total;
			$total = $total - $credits;

			if($total < 0) {
				$total = 0;
				$creditsRequired = $orignal;
			} else {
				$creditsRequired = $credits;
			}
		}

		if(!empty($this->data['cc'])) {
			$this->data['price'] = $total;
			$result = $this->PaypalProUk->process($this->data);

			if($result !== 'FAILURE') {
				$auction['Auction']['status_id'] = 2;
				$this->Auction->save($auction);

				if(!empty($this->appConfigurations['credits']['active'])) {
					// 	lets deduct the spent credits
					$credit['Credit']['user_id'] = $this->Auth->user('id');
					$credit['Credit']['auction_id'] = $id;
					$credit['Credit']['debit'] = $creditsRequired;
					$this->Auction->Credit->create();
					$this->Auction->Credit->save($credit);
				}

				// lets check to see if we this is the first time they have added bids
				$wonBefore = $this->Auction->find('count', array('conditions' => array('Auction.winner_id' => $this->Auth->user('id'))));
				if($wonBefore == 1) {
					$setting = $this->Setting->get('free_won_auction_bids');
					if((is_numeric($setting)) && $setting > 0) {
						if($this->appConfigurations['simpleBids'] == true) {
							$user['User']['id'] = $this->Auth->user('id');
							$user['User']['bid_balance'] += $setting;
							$this->User->save($user);
						} else {
							$freeBidData['Bid']['user_id'] = $this->Auth->user('id');
							$freeBidData['Bid']['description'] = __('Free bids given for winning your first auction.', true);
							$freeBidData['Bid']['credit'] = $setting;
							$this->User->Bid->create();
							$this->User->Bid->save($freeBidData);
						}
					}
				}
				if(Configure::read('debug') == 0) {
					if(!empty($redirect)) {
						$this->redirect('http://www.'.$redirect.'/auctions/returning');
					} else {
						$this->redirect($this->appConfigurations['url'].'/auctions/returning');
					}
				} else {
					$this->redirect(array('action' => 'returning'));
				}
			} else {
				$this->Session->setFlash(__('There was a problem processing your payment.  Please check your credit card details and try again.  If the problem persists try the Paypal payment option.', true));
			}
		} else {
			$this->data['buyer']['first'] 		= $user['Winner']['first_name'];
			$this->data['buyer']['last'] 		= $user['Winner']['last_name'];
			$this->data['buyer']['email'] 		= $user['Winner']['email'];
			if(!empty($user['Address'][0])) {
				$this->data['buyer']['address1'] 	= $user['Address'][0]['address_1'];
				$this->data['buyer']['address2']	= $user['Address'][0]['address_2'];
				$this->data['buyer']['city'] 		= $user['Address'][0]['suburb'];
				$this->data['buyer']['state'] 		= $user['Address'][0]['city'];
				$this->data['buyer']['zip'] 		= $user['Address'][0]['postcode'];

				$country = $this->User->Address->Country->find('first', array('conditions' => array('Country.id' => $user['Address'][0]['country_id']), 'recursive' => -1));
				$this->data['buyer']['country'] = $country['Country']['code'];
			}

			$this->data['cc']['owner']['first'] = $user['Winner']['first_name'];
			$this->data['cc']['owner']['last'] 	= $user['Winner']['last_name'];
		}
	}

	function pay($id = null) {
		$this->pageTitle = __('Pay for an Auction', true);
		if (empty($id)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'won'));
		}

		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'won'));
		}
		if($auction['Auction']['winner_id'] !== $this->Auth->user('id')) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'won'));
		}

		if($auction['Auction']['status_id'] != 1) {
			$this->Session->setFlash(__('You have already paid for this auction.', true));
			$this->redirect(array('action'=>'won'));
		}

		$this->set('auction', $auction);

		$user = $this->Auction->Winner->read(null, $this->Auth->user('id'));
		$this->set('user', $user);

		$addresses 	 	 = $this->Auction->Winner->Address->addressTypes();
		$userAddress 	 = array();
		$addressRequired = 0;
		if(!empty($addresses)) {
			foreach($addresses as $address_id=>$address) {
				$userAddress[$address] = $this->Auction->Winner->Address->find('first', array('conditions' => array('Address.user_id' => $this->Auth->user('id'), 'Address.user_address_type_id' => $address_id)));
				if(empty($userAddress[$address])) {
					$addressRequired = 1;
				}
			}
		}

		$this->set('address', $userAddress);
		$this->set('addressRequired', $addressRequired);

		if(empty($addressRequired)) {
			if(!empty($auction['Product']['fixed'])) {
				$total = $auction['Product']['fixed_price'] + $auction['Product']['delivery_cost'];
			} else {
				$total = $auction['Auction']['price'] + $auction['Product']['delivery_cost'];
			}

			// 19/02/2009 - Michael - updated to take into account free auctions
			if(!empty($this->appConfigurations['credits']['active']) && empty($auction['Auction']['free'])) {
				$credits = $this->Auction->Credit->balance($this->Auth->user('id'), $this->appConfigurations['credits']['expiry']);
				$orignal = $total;
				$total = $total - $credits;

				if($total < 0) {
					$total = 0;
					$creditsRequired = $orignal;
				} else {
					$creditsRequired = $credits;
				}
			}

			if(!empty($this->data)) {
				if($total == 0) {
					// if the total is 0, we don't need to pay through the gateway!
					$this->appConfigurations['gateway'] = null;
				}

				if(!$this->appConfigurations['gateway']) {
					// the gateway is not set
					$auction['Auction']['status_id'] = 2;
					$this->Auction->save($auction);

					if(!empty($this->appConfigurations['credits']['active'])) {
						// 	lets deduct the spent credits
						$credit['Credit']['user_id'] = $this->Auth->user('id');
						$credit['Credit']['auction_id'] = $id;
						$credit['Credit']['debit'] = $creditsRequired;
						$this->Auction->Credit->create();
						$this->Auction->Credit->save($credit);
					}

					// lets check to see if we this is the first time they have added bids
					$wonBefore = $this->Auction->find('count', array('conditions' => array('Auction.winner_id' => $this->Auth->user('id'))));
					if($wonBefore == 1) {
						$setting = $this->Setting->get('free_won_auction_bids');
						if((is_numeric($setting)) && $setting > 0) {
							if($this->appConfigurations['simpleBids'] == true) {
								$winner = $this->Auction->Winner->find('first', array('conditions' => array('Winner.id' => $this->Auth->user('id')), 'contain' => ''));
								$user['User']['id'] = $this->Auth->user('id');
								$user['User']['bid_balance'] = $winner['Winner']['bid_balance'] + $setting;
								$this->User->save($user);
							} else {
								$freeBidData['Bid']['user_id'] = $this->Auth->user('id');
								$freeBidData['Bid']['description'] = __('Free bids given for winning your first auction.', true);
								$freeBidData['Bid']['credit'] = $setting;
								$this->User->Bid->create();
								$this->User->Bid->save($freeBidData);
							}
						}
					}

					eval(PluginManager::hook('controllers_auctions_pay_paid'));

					if($total > 0) {
						$this->redirect(array('action' => 'returning'));
					} else {
						$this->redirect(array('action' => 'returning', 1));
					}
				}
			}

			if(!empty($this->appConfigurations['credits']['active'])) {
				$this->set('orignal', $orignal);
				$this->set('credits', $credits);
				$this->set('creditsRequired', $creditsRequired);
			}

			$this->set('total', $total);

			if($this->appConfigurations['gateway']) {
				// Formating the data for paypal
				$paypal['url'] 	  	     = Configure::read('Paypal.url');
				$paypal['business']      = Configure::read('Paypal.email');
				$paypal['lc'] 	 	     = Configure::read('Paypal.lc');
				$paypal['currency_code'] = $this->appConfigurations['currency'];
				$paypal['item_name']     = $auction['Product']['title'];
				$paypal['item_number']   = $auction['Auction']['id'];
				$paypal['amount']        = number_format($total, 2);
				$paypal['return'] 	     = $this->appConfigurations['url'] . '/auctions/returning';
				$paypal['cancel_return'] = $this->appConfigurations['url'] . '/auctions/cancelled';
				$paypal['notify_url']    = $this->appConfigurations['url'] . '/auctions/ipn';
				$paypal['custom']        = $user['Winner']['id'];
				$paypal['first_name']    = $user['Winner']['first_name'];
				$paypal['last_name']     = $user['Winner']['last_name'];
				$paypal['email']         = $user['Winner']['email'];
				$paypal['address1']      = $userAddress['Billing']['Address']['address_1'];
				$paypal['address2']      = $userAddress['Billing']['Address']['address_2'];
				$paypal['city']    	     = $userAddress['Billing']['Address']['city'];
				$paypal['zip']    	     = $userAddress['Billing']['Address']['postcode'];

				$this->Paypal->configure($paypal);
				$paypalData = $this->Paypal->getFormData();
				$this->set('paypalData', $paypalData);

				// formatting the data for ePayment.ro
				$ePayment['merchant'] = Configure::read('ePayment.merchant');
				$ePayment['order_date'] = date('Y-m-d H:i:s');
				$ePayment['orders'][0]['name']  = $auction['Product']['title'];
				$ePayment['orders'][0]['code']  = $auction['Auction']['id'];
				$ePayment['orders'][0]['price'] = number_format($total, 2);
				$ePayment['orders'][0]['qty']   = 1;
				$ePayment['orders'][0]['vat']   = 0;
				$ePayment['prices_currency']    = Configure::read('App.currency');
				$ePayment['language']   		= Configure::read('ePayment.language');
				$ePayment['test_order'] 		= Configure::read('ePayment.test_order');
				$ePaymentKey     				= Configure::read('ePayment.key');

				$this->set('ePaymentData', $ePayment);
				$this->set('ePaymentKey', $ePaymentKey);
			}
		}
	}

	function returning($zero_priced = null) {
		if(!empty($zero_priced)) {
			$this->Session->setFlash(__('Your details have been confirmed.  We will notify you when your item has been shipped.', true));
		} else {
			$this->Session->setFlash(__('Your payment was successful.  We will notify you when your item has been shipped.', true));
		}
		$this->redirect(array('action' => 'won'));
	}

	function cancelled() {
		$this->Session->setFlash(__('Your transaction was cancelled and your auctions was not purchased.', true));
		$this->redirect(array('action' => 'won'));
	}

	function ipn_epayment(){
		$this->layout = 'js/ajax';
		$response = $this->Epayment->ipn();
		$data = $this->Epayment->ipn_data();

		// Check data here, need to get the ipn url first to check the data given
		// by epayment server

		$this->set('response', $response);
	}

	function ipn(){
		$this->layout = 'js/ajax';

		$this->Paypal->configure(array('url' => Configure::read('Paypal.url')));
		if($this->Paypal->validate_ipn()){

			// Get user auction
			$auction = $this->Auction->findById($this->Paypal->ipn_data['item_number']);
			if(!empty($auction)){

				// Variable to hold auction total
				$total = 0;

				// Get auction total
				if(!empty($auction['Product']['fixed'])) {
					$total = $auction['Product']['fixed_price'] + $auction['Product']['delivery_cost'];
				} else {
					$total = $auction['Auction']['price'] + $auction['Product']['delivery_cost'];
				}

				if(!empty($this->appConfigurations['credits']['active'])) {
					$credits = $this->Auction->Credit->balance($this->Auth->user('id'), $this->appConfigurations['credits']['expiry']);
					$orignal = $total;
					$total = $total - $credits;

					if($total < 0) {
						$total = 0;
						$creditsRequired = $orignal;
					} else {
						$creditsRequired = $credits;
					}
				}

				$total = number_format($total, 2);

				// Proceed only if payment status is "Completed"
				// check it in lower case format in case they return it in
				// different format
				if(strtolower($this->Paypal->ipn_data['payment_status']) == 'completed'){
					// Fraud detection
					if($this->Paypal->ipn_data['mc_gross'] == $total){

						$auction['Auction']['status_id'] = 2;
						$this->Auction->save($auction);

						if(!empty($this->appConfigurations['credits']['active'])) {
							// 	lets deduct the spent credits
							$credit['Credit']['user_id'] = $this->Auth->user('id');
							$credit['Credit']['auction_id'] = $auction['Auction']['id'];
							$credit['Credit']['debit'] = $creditsRequired;
							$this->Auction->Credit->create();
							$this->Auction->Credit->save($credit);
						}

						// lets check to see if we this is the first time they have added bids
						$wonBefore = $this->Auction->find('count', array('conditions' => array('Auction.winner_id' => $this->Paypal->ipn_data('custom'))));
						if($wonBefore == 1) {
							$setting = $this->Setting->get('free_won_auction_bids');
							if((is_numeric($setting)) && $setting > 0) {
								if($this->appConfigurations['simpleBids'] == true) {
									$user['User']['id'] = $this->Auth->user('id');
									$user['User']['bid_balance'] += $setting;
									$this->User->save($user);
								} else {
									$freeBidData['Bid']['user_id'] = $this->Auth->user('id');
									$freeBidData['Bid']['description'] = __('Free bids given for winning your first auction.', true);
									$freeBidData['Bid']['credit'] = $setting;
									$this->User->Bid->create();
									$this->User->Bid->save($freeBidData);
								}

								$this->log('free bids added');
							}
						}
					}else{
						$this->log('Fraud detected on auction #'. $auction['Auction']['id'] . ', db total : ' . $total . ', paypal total : ' . $this->Paypal->ipn_data['mc_gross']);
						return false;
					}
				}else{
					return false;
				}
			}else{
				$this->log('User auction id:'.$this->Paypal->ipn_data['item_number'].' not found');
				return false;
			}
		}else{
			$this->log('ipn verification failed');
		}
	}

	function offline() {

	}


	function timeout() {

	}

	function buy($id = null) {
		
		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}
		
		if (!$this->Auction->canBuyNow($auction, $this->Auth->user('id'))) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}
		
		if(!(Configure::read('App.buyNow')===true or Configure::read('App.buyNow.enabled')===true)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}

		if (empty($id)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}

		

		if($auction['Product']['buy_now'] == '0.00') {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}

		
		//are we paying full B-I-N or just the difference?
		$auction['Auction']['price']=$this->Auction->binPrice($auction['Auction']['id'], $auction['Product']['buy_now'], $this->Auth->user('id'));
		
		$this->set('auction', $auction);

		if(!empty($this->data)) {
			
			//Do we need to split it off?
			$splitting=false;
			if (Configure::read('App.buyNow.split')===true) {
				
				if ($auction['Product']['stock']==1) {
					//we're using stock control
					if ($auction['Product']['stock_number']>1) {
						//we split have more than 1 in stock, so split
						$splitting=true;
					}
					
					//there's one fewer in stock now
					$this->Auction->Product->soldStock($auction['Product']['id'],1);
					
				} else {
					//no stock control, split anyway
					$splitting=true;
				}
			}
			
			if ($splitting) {
				unset($auction['Auction']['id']);
				$this->Auction->create();
				$auction['Auction']['parent_id']=$id;
			}
			
			$auction['Auction']['active'] = 0;
			$auction['Auction']['closed'] = 1;
			$auction['Auction']['closed_status'] = 2;
			$auction['Auction']['end_time'] = date('Y-m-d H:i:s');
			$auction['Auction']['winner_id'] = $this->Auth->user('id');
			$auction['Auction']['leader_id'] = $this->Auth->user('id');
			$auction['Auction']['status_id'] = 1;
			
			$this->Auction->save($auction, false);
			
			//find the ID of the BIN auction for the reminder
			if ($splitting) { 
				$new_id=$this->Auction->getLastInsertId();
			} else {
				$new_id=$auction['Auction']['id'];
			}
			$this->Auction->AuctionEmail->create();
			$this->Auction->AuctionEmail->save(array('AuctionEmail'=>array('auction_id'=>$new_id)));
			
			$this->Session->setFlash(__('You have successfully won this auction.', true));
			$this->redirect(array('action'=>'won'));
		}

		$this->pageTitle = __('Buy This Auction Now', true);
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Auction.end_time DESC', 'Auction.closed ASC'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_live() {
		$this->paginate = array('conditions' => "start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . $this->getEndTime() . "'", 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('closed' => 'asc', 'end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->set('extraCrumb', array('title' => __('Live Auctions',true), 'url' => 'live'));

		$this->render('admin_index');
		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_comingsoon(){
		$this->paginate = array('conditions' => "start_time > '" . date('Y-m-d H:i:s') . "'", 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->set('extraCrumb', array('title' => 'Coming Soon', 'url' => 'comingsoon'));

		$this->render('admin_index');
		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_closed(){
		$this->paginate = array('conditions' => array('closed' => 1), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'desc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->set('extraCrumb', array('title' => __('Closed Auctions',true), 'url' => 'closed'));

		$this->render('admin_index');
		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_won($status_id = null) {
		if(!empty($status_id)){
			$conditions = array('winner_id >' => 0, 'Winner.autobidder' => 0, 'Auction.status_id' => $status_id);
		}else{
			$conditions = array('winner_id >' => 0, 'Winner.autobidder' => 0);
		}
		$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));

		$this->set('auctions', $this->paginate('Auction'));
		$this->set('statuses', $this->Auction->Status->find('list'));
		$this->set('selected', $status_id);
		$this->set('extraCrumb', array('title' => __('Won Auctions', true), 'url' => 'won'));

		$this->render('admin_index');
	}

	function admin_autobidders() {
		$conditions = array('winner_id >' => 0, 'Winner.autobidder' => 1);

		$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));

		$this->set('auctions', $this->paginate('Auction'));
	}

	function admin_adminusers() {
		$conditions = array('winner_id >' => 0, 'Winner.admin' => 1);

		$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));

		$this->set('auctions', $this->paginate('Auction'));
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Auction->Winner->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Auction.winner_id' => $user_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Auction.end_time' => 'asc'), 'contain' => array('Winner', 'Product' => 'Category'));
		$this->set('auctions', $this->paginate());
	}

	function admin_winner($id = null) {
		if (empty($id)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}
		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}

		if(!empty($this->data)) {
			$this->Auction->save($this->data);
	
			if($this->data['Auction']['inform'] == 1) {
				$data						   = $this->Auction->read(null, $id);
				$data['Status']['comment'] 	   = $this->data['Auction']['comment'];
				$data['to'] 				   = $auction['Winner']['email'];
					$data['subject'] 			   = sprintf(__('%s - Auction Status Updated', true), $this->appConfigurations['name']);
					$data['template'] 			   = 'auctions/status';
					$this->_sendEmail($data);
			}
	
			$this->Session->setFlash(__('The auction status was successfully updated.', true));
			$this->redirect(array('action' => 'winner', $auction['Auction']['id']));
		}

		$this->set('auction', $auction);

		$user = $this->Auction->Winner->read(null, $auction['Auction']['winner_id']);
		$this->set('user', $user);

		$addresses = $this->Auction->Winner->Address->addressTypes();
		$userAddress = array();
		$addressRequired = 0;
		if(!empty($addresses)) {
			foreach($addresses as $address_type_id=>$address) {
				$userAddress[$address] = $this->Auction->Winner->Address->find('first', array('conditions' => array('Address.user_id' => $auction['Auction']['winner_id'], 'Address.user_address_type_id' => $address_type_id)));
			}
		}
		$this->set('address', $userAddress);

		$this->set('selectedStatus', $auction['Auction']['status_id']);
		$this->set('statuses', $this->Auction->Status->find('list'));
	}

	function admin_add($product_id = null) {
		if(empty($product_id)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$product = $this->Auction->Product->read(null, $product_id);
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $product);

		if (!empty($this->data)) {
			$this->data['Auction']['product_id'] = $product_id;
			if ($this->data['Auction']['reverse']) {
				$this->data['Auction']['price'] = $product['Product']['rrp'];
			} else {
				$this->data['Auction']['price'] = $product['Product']['start_price'];
			}
			$this->data['Auction']['minimum_price'] = $product['Product']['minimum_price'];
			if(!empty($product['SettingIncrement'])) {
				$this->data['Auction']['bid_debit'] = $product['SettingIncrement'][0]['bid_debit'];
			}
			if(empty($this->data['Auction']['hidden_reserve'])) {
				$this->data['Auction']['hidden_reserve'] = 0;
			}

			$this->Auction->create();
			if ($this->Auction->save($this->data)) {
				
				$this->Auction->clearCache();
				
				$auction_id=$this->Auction->getLastInsertId();
				
				//*** See if we need to Tweet
				$config=Configure::read('Twitter');
		
				if (isset($config['enabled'])===true) {
					$this->Twitter->tweet($product, $auction_id, $config);
				}
				
				$this->Session->setFlash(__('The auction has been added successfully.', true));
				if($this->Session->check('auctionsPage')) {
					$this->redirect('/'.$this->Session->read('auctionsPage'));
				} else {
					$this->redirect(array('controller' => 'products', 'action'=>'auctions', $product_id));
				}
			} else {
				$this->Session->setFlash(__('There was a problem adding the auction please review the errors below and try again.', true));
			}
		} else {
			$this->data['Auction']['active'] = 1;
		}
		$this->set('categories', $this->Auction->Product->Category->generatetreelist(null, null, null, '-'));
	}

	function admin_edit($id = null) {
		if (empty($id)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}
		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('The auction ID was invalid.', true));
			$this->redirect(array('action'=>'index'));
		}
		$product = $this->Auction->Product->read(null, $auction['Product']['id']);
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $product);

		if (!empty($this->data)) {
			if(empty($this->data['Auction']['hidden_reserve'])) {
				$this->data['Auction']['hidden_reserve'] = 0;
			}
			if ($this->Auction->save($this->data)) {
				$this->Auction->clearCache();
				
				$this->Session->setFlash(__('The auction has been updated successfully.', true));
				if($this->Session->check('auctionsPage')) {
					$this->redirect('/'.$this->Session->read('auctionsPage'));
				} else {
					$this->redirect(array('controller' => 'products', 'action'=>'auctions', $auction['Product']['id']));
				}
			} else {
				$this->Session->setFlash(__('There was a problem updating the auction please review the errors below and try again.', true));
			}
		} else {
			$this->data = $auction;
		}

		$this->set('categories', $this->Auction->Product->Category->generatetreelist(null, null, null, '-'));
	}

	function admin_refund($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Auction.', true));
			$this->redirect(array('action' => 'index'));
		}

		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('The auction ID was invalid.', true));
			$this->redirect(array('action'=>'index'));
		}

		$auction['Auction']['leader_id'] = 0;
		$auction['Auction']['status_id'] = 0;
		$auction['Auction']['winner_id'] = 0;
		$this->Auction->save($auction);

		$this->Auction->Bid->deleteAll(array('Bid.auction_id' => $id));
		$this->Session->setFlash(__('The bids were successfully refunded.', true));
		$this->redirect(array('action' => 'index'));
	}

	function admin_stats($auction_id = null) {
		if(empty($auction_id)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('action' => 'index'));
		}
		$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => 'Product'));

		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('auction', $auction);

		if(!empty($realBidsOnly)) {
			$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0);
			$this->set('realBidsOnly', $realBidsOnly);
		} else {
			$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0);
		}

		$this->set('realbids', $this->Auction->Bid->find('count', array('conditions' => array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0))));
		$this->set('autobids', $this->Auction->Bid->find('count', array('conditions' => array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 1))));

		$priceIncrement = $this->requestAction('/settings/get/price_increment/'.$auction_id.'/0');
		$this->set('priceIncrement', $priceIncrement);

		$priceDifference = $auction['Auction']['minimum_price'] - $auction['Auction']['price'];
		if($priceDifference > 0) {
			$realBidsRequired = ceil($priceDifference / $priceIncrement);
		} else {
			$realBidsRequired = 0;
		}
		$this->set('realBidsRequired', $realBidsRequired);
	}

	function admin_delete($id = null) {
		//ini_set('max_execution_time', 600);

		if (!$id) {
			$this->Session->setFlash(__('Invalid auction id.', true));

		}
		if ($this->Auction->del($id)) {
			$this->Session->setFlash(__('The auction was deleted successfully.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this auction.', true));
		}
		$this->redirect(array('action' => 'index'));
		
		// we need to archive any bids before deleting the auction
		/*$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['cleaner']['clear'] * 24 * 60 * 60));

		$bids = $this->Auction->Bid->find('all', array('conditions' => array('Bid.auction_id' => $id), 'contain' => ''));
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

		if ($this->Auction->del($id)) {
			$this->Session->setFlash(__('The auction was deleted successfully.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this auction.', true));
		}
		$this->redirect(array('action' => 'index'));*/
	}
}

?>