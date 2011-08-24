<?php
class PackagesController extends AppController {

	var $name = 'Packages';
	var $uses = array('Package', 'User', 'Setting', 'Country', 'Coupon');
	var $components = array('PaypalProUk', 'Epayment');
	var $helpers = array('Epayment');

	function beforeFilter(){
		parent::beforeFilter();

		if(isset($this->Auth)){
			$this->Auth->allow('ipn', 'getlist', 'creditcard');
		}

		if(Configure::read('App.coupons')){
			if($coupon = Cache::read('coupon_user_'.$this->Auth->user('id'))){
				$coupon = $this->Coupon->findByCode(strtoupper($coupon['Coupon']['code']));
				if(empty($coupon)){
					Cache::delete('coupon_user_'.$this->Auth->user('id'));
					$this->Session->setFlash(__('The coupon you applied is no longer exist. Please enter another coupon.', true));
					$this->redirect(array('controller' => 'packages', 'action' => 'index'));
				}
			}
		}
	}

	function getlist(){
		return $this->Package->find('all', array('order' => array('Package.price' => 'asc')));
	}

	function index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('price' => 'asc'));
		$this->set('packages', $this->paginate());
		$this->pageTitle = __('Purchase Bids', true);
	}

	function applycoupon(){
		if(!empty($this->data['Coupon']['code'])){
			$coupon = $this->Coupon->findByCode(strtoupper($this->data['Coupon']['code']));
			if(!empty($coupon)){
				Cache::write('coupon_user_'.$this->Auth->user('id'), $coupon);
				$this->Session->setFlash(__('The coupon has been applied.',true), 'default', array('class' => 'success'));
			}else{
				$this->Session->setFlash(__('Invalid Coupon',true));
			}
		}else{
			$this->Session->setFlash(__('Invalid Coupon',true));
		}

		$this->redirect(array('action' => 'index'));
	}

	function removecoupon(){
		if(Cache::read('coupon_user_'.$this->Auth->user('id'))){
			Cache::delete('coupon_user_'.$this->Auth->user('id'));
			$this->Session->setFlash(__('The coupon has been removed.', true));
		}else{
			$this->Session->setFlash(__('You are not apply any coupon yet.', true));
		}

		$this->redirect(array('action' => 'index'));
	}

	function creditcard($id = null, $currency_code = null, $redirect = null, $user_id = null) {
		if (!Configure::read('PaypalProUk.username') || !Configure::read('PaypalProUk.password')) {
			$this->log('Attempted to access PaypalProUk which is disabled', 'payment');
			$this->redirect("/packages");
		}
		
		
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid Package', true));
			$this->redirect(array('action'=>'index'));
		}
		$package = $this->Package->read(null, $id);
		if(empty($package)) {
			$this->Session->setFlash(__('Invalid Package', true));
			$this->redirect(array('action'=>'index'));
		}

		if(!empty($currency_code)) {
			$currency = $this->Currency->find('first', array('fields' => 'rate', 'conditions' => array('Currency.currency' => $currency_code)));
			if(!empty($currency)){
				//Configure::write('Currency.rate', $currency['Currency']['rate']);
				Configure::write('App.currency', $currency_code);
				$this->set('currency_code', $currency_code);
				$this->appConfigurations['currency'] = $currency_code;
				$this->set('appConfigurations', $this->appConfigurations);
			} else {
				$this->Session->setFlash(__('Invalid Currency', true));
				$this->redirect(array('action'=>'index'));
			}
		}

		if(!empty($redirect)) {
			$this->set('redirect', $redirect);
		}

		if($this->Auth->user('id')) {
			$user = $this->User->findById($this->Auth->user('id'));
		} else {
			$user = $this->User->findById($user_id);
		}

		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->set('package', $package);
		$this->set('user', $user);
		$this->set('countries', $this->Country->find('list', array('fields' => array('Country.code', 'Country.name'))));
		$this->set('ccTypes', array('Visa' => 'Visa', 'MasterCard' => 'MasterCard', 'Amex' => 'Amex', 'Discover' => 'Discover', 'Solo' => 'Solo', 'Maestro' => 'Maestro'));

		if(!empty($this->data)) {
			$this->data['price'] = $package['Package']['price'];
			$result = $this->PaypalProUk->process($this->data);
			if($result !== 'FAILURE') {
				$this->__process($package, $user);
				$this->log('PaypalProUk: successfully processed ('.$result.')', 'payment');
				$this->Session->setFlash(__('You payment was successful and your bids are available for you use.', true));
				if(Configure::read('debug') == 0) {
					if(!empty($redirect)) {
						$this->redirect('http://www.'.$redirect.'/accounts');
					} else {
						$this->redirect($this->appConfigurations['url'].'/accounts');
					}
				} else {
					$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
				}
			} else {
				$this->log('PaypalProUk: error processing ('.$result.')', 'payment');
				$this->Session->setFlash(__('There was a problem processing your payment.  Please check your credit card details and try again.  Also ensure that all fields marked * are filled in below.  If the problem persists try the Paypal payment option.', true));
			}
		} else {
			$this->data['buyer']['first'] 		= $user['User']['first_name'];
			$this->data['buyer']['last'] 		= $user['User']['last_name'];
			$this->data['buyer']['email'] 		= $user['User']['email'];
			if(!empty($user['Address'][0])) {
				$this->data['buyer']['address1'] 	= $user['Address'][0]['address_1'];
				$this->data['buyer']['address2']	= $user['Address'][0]['address_2'];
				$this->data['buyer']['city'] 		= $user['Address'][0]['suburb'];
				$this->data['buyer']['state'] 		= $user['Address'][0]['city'];
				$this->data['buyer']['zip'] 		= $user['Address'][0]['postcode'];

				$country = $this->User->Address->Country->find('first', array('conditions' => array('Country.id' => $user['Address'][0]['country_id']), 'recursive' => -1));
				$this->data['buyer']['country'] = $country['Country']['code'];
			}

			$this->data['cc']['owner']['first'] = $user['User']['first_name'];
			$this->data['cc']['owner']['last'] 	= $user['User']['last_name'];
		}
	}

	function buy($id = null){
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid Package', true));
			$this->redirect(array('action'=>'index'));
		}
		$package = $this->Package->read(null, $id);
		if(empty($package)) {
			$this->Session->setFlash(__('Invalid Package', true));
			$this->redirect(array('action'=>'index'));
		}
		$user = $this->User->findById($this->Auth->user('id'));
		$this->set('package', $package);

		if(!empty($this->data)) {
			// now check to see if the gateway is turned on in config.  If it is send them to the gateway
			if(empty($this->appConfigurations['gateway'])) {
				// this is for the demo version
				$this->__process($package, $user);
				$this->redirect(array('action' => 'returning'));
			}
		}

		if(!empty($this->appConfigurations['gateway'])) {
			// Formating the data
			$paypal['url'] 	  	     = Configure::read('Paypal.url');
			$paypal['business']      = Configure::read('Paypal.email');
			$paypal['lc'] 	 	     = Configure::read('Paypal.lc');
			$paypal['currency_code'] = $this->appConfigurations['currency'];
			$paypal['item_name']     = $package['Package']['name'];
			$paypal['item_number']   = $package['Package']['id'];
			$paypal['amount']        = number_format($package['Package']['price'], 2);
			$paypal['return'] 	     = $this->appConfigurations['url'] . '/packages/returning';
			$paypal['cancel_return'] = $this->appConfigurations['url'] . '/packages/cancelled';
			$paypal['notify_url']    = $this->appConfigurations['url'] . '/packages/ipn';
			$paypal['custom']        = $this->Auth->user('id');
			$paypal['first_name']    = $this->Auth->user('first_name');
			$paypal['last_name']     = $this->Auth->user('last_name');
			$paypal['email']         = $this->Auth->user('email');

			$this->Paypal->configure($paypal);
			$paypalData = $this->Paypal->getFormData();
			$this->set('paypalData', $paypalData);

			// formatting the data for ePayment.ro
			$ePayment['merchant'] = Configure::read('ePayment.merchant');
			$ePayment['order_date'] = date('Y-m-d H:i:s');
			$ePayment['orders'][0]['name']  = $package['Package']['name'];
			$ePayment['orders'][0]['code']  = $package['Package']['id'];
			$ePayment['orders'][0]['price'] = number_format($package['Package']['price'], 2);
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

	function buy_epayment($id = null){
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid Package', true));
			$this->redirect(array('action'=>'index'));
		}
		$package = $this->Package->read(null, $id);
		if(empty($package)) {
			$this->Session->setFlash(__('Invalid Package', true));
			$this->redirect(array('action'=>'index'));
		}
		$user = $this->User->findById($this->Auth->user('id'));
		$this->set('package', $package);

		if(!empty($this->data)) {
			// now check to see if the gateway is turned on in config.  If it is send them to the gateway
			if(empty($this->appConfigurations['gateway'])) {
				// this is for the demo version
				$this->__process($package, $user);
				$this->redirect(array('action' => 'returning'));
			}
		}

		if(!empty($this->appConfigurations['gateway'])) {
			// formatting the data for ePayment.ro
			$ePayment['merchant'] = Configure::read('ePayment.merchant');
			$ePayment['order_date'] = date('Y-m-d H:i:s');
			$ePayment['orders'][0]['name']  = $package['Package']['name'];
			$ePayment['orders'][0]['code']  = $package['Package']['id'];
			$ePayment['orders'][0]['price'] = number_format($package['Package']['price'], 2);
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

	function returning() {
		$this->Session->setFlash(__('You payment was successful and your bids are available for you use.  If your bids are not available yet, please allow a couple of minutes for them to become available.', true), 'default', array('class'=>'success'));
		if(Configure::read('GoogleTracking.bidPurchase.active')) {
			$this->redirect(array('action' => 'tracking'));
		} else {
			$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
		}
	}

	function cancelled() {
		$this->Session->setFlash(__('The transaction was cancelled and your bids were not purchased.', true));
		$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
	}

	function tracking() {

	}

	function ipn_epayment(){
		$this->layout = 'js/ajax';
		$response = $this->Epayment->ipn();
		$data = $this->Epayment->ipn_data();

		// Check data here, need to get the ipn url first to check the data given
		// by epayment server

		$this->set('response', $response);
	}

	function ipn() {
		$this->layout = 'js/ajax';
		$this->Paypal->configure(array('url' => Configure::read('Paypal.url')));
		if($this->Paypal->validate_ipn()) {

			// Get the package
			$package = $this->Package->findById($this->Paypal->ipn_data['item_number']);

			if(Configure::read('App.coupons')){
				$name = $this->Paypal->ipn_data['item_name'];
				$coupon = explode('+', $name);
				if(!empty($coupon[1])){
					$coupon_code = trim($coupon[1]);
					$coupon = $this->Coupon->findByCode($coupon_code);
					if(!empty($coupon)){
						switch($coupon['Coupon']['coupon_type_id']){
							case 1:
								$package['Package']['price'] -= $package['Package']['price'] * ($coupon['Coupon']['saving']/100);
								break;

							case 2:
								$package['Package']['price'] -= $coupon['Coupon']['saving'];
								break;

							case 3:
								$package['Package']['bids'] += round($coupon['Coupon']['saving']);
								break;

							case 4:
								$package['Package']['bids'] += $package['Package']['bids'] * ($coupon['Coupon']['saving']/100);
								break;

						}

						$package['Package']['name']  .= ' + '.$coupon['Coupon']['code'];
					}
				}
			}

			if(!empty($package)){
				// Only process payment with "Completed" status
				// otherwise return false. check it in lowercase format
				if(strtolower($this->Paypal->ipn_data['payment_status']) == 'completed'){
					$user = $this->User->read(null, $this->Paypal->ipn_data['custom']);
					$this->__process($package, $user);
				}else{
					return false;
				}

			}else{
				$this->log('User package id:'.$this->Paypal->ipn_data['item_number'].' not found');
				return false;
			}
		}
	}

	function __process($package = null, $user = null)
	{
		// Now start by adding their bids
		if($this->appConfigurations['simpleBids'] == true) {
			$user['User']['bid_balance'] += $package['Package']['bids'];
			$this->User->save($user);
		} else {
			$bidData['Bid']['user_id']     = $user['User']['id'];
			$bidData['Bid']['description'] = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
			$bidData['Bid']['credit']      = $package['Package']['bids'];

			// Save the bid
			$this->User->Bid->create();
			$this->User->Bid->save($bidData);
		}

		// Lets check to see if we this is the first time they have added bids
		$purchasedBefore = $this->User->Account->find('first', array('conditions' => array('Account.user_id' => $user['User']['id'], 'Account.bids >' => 0)));

		// Now lets update their account
		$accountData['Account']['user_id'] = $user['User']['id'];
		$accountData['Account']['name']    = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
		$accountData['Account']['bids']    = $package['Package']['bids'];
		$accountData['Account']['price']   = $package['Package']['price'];
		// add in the IP address of the user for fraud tracking
		$accountData['Account']['ip']   = $_SERVER['REMOTE_ADDR'];

		// Save the account
		$this->User->Account->create();
		$this->User->Account->save($accountData);

		// Now we check if this user was referred so we can give the free bids away
		$this->User->Referral->recursive = -1;
		$referral = $this->User->Referral->find('first', array('conditions' => array('user_id' => $user['User']['id'], 'confirmed' => 0)));

		if(!empty($referral)) {
			// Get the setting for free bids
			$setting = $this->Setting->get('free_referral_bids');

			if((is_numeric($setting)) && $setting > 0) {
				// Saving the bid
				if($this->appConfigurations['simpleBids'] == true) {
					$user['User']['bid_balance'] += $setting;
					$this->User->save($user);
				} else {
					// Formating the referral bid data
					$referralBidData['Bid']['user_id'] 	   = $referral['Referral']['referrer_id'];
					$referralBidData['Bid']['description'] = __('Referral Bids Earned for:', true).' '.$user['User']['username'];
					$referralBidData['Bid']['credit'] 	   = $setting;

					$this->User->Bid->create();
					$this->User->Bid->save($referralBidData);
				}
			} elseif(substr($setting, -1) == '%' && is_numeric(substr($setting, 0, -1))) {
				if($this->appConfigurations['simpleBids'] == true) {
					$user['User']['bid_balance'] += $package['Package']['bids'] * (substr($setting, 0, -1) / 100);
					$this->User->save($user);
				} else {
					// Formating the referral bid data
					$referralBidData['Bid']['user_id'] 	   = $referral['Referral']['referrer_id'];
					$referralBidData['Bid']['description'] = __('Referral Bids Earned for:', true).' '.$user['User']['username'];
					$referralBidData['Bid']['credit'] 	   = $package['Package']['bids'] * (substr($setting, 0, -1) / 100);

					$this->User->Bid->create();
					$this->User->Bid->save($referralBidData);
				}
			}

			// Finally set the referral as confirmed
			$referral['Referral']['confirmed'] = 1;
			unset($referral['Referral']['modified']);

			// Save the referral
			$this->User->Referral->save($referral);
		}

		// Lets see if they get some free bids
		if(empty($purchasedBefore)) {
			// Get the setting
			$setting = $this->Setting->get('free_bid_packages_bids');

			// If setting for free bids is not 0
			if((is_numeric($setting)) && $setting > 0) {
				if($this->appConfigurations['simpleBids'] == true) {
					$user['User']['bid_balance'] += $setting;
					$this->User->save($user);
				} else {
					// Format the data
					$freeBidData['Bid']['user_id'] 	   = $user['User']['id'];
					$freeBidData['Bid']['description'] = __('Free bids given for purchasing bids for the first time.', true);
					$freeBidData['Bid']['credit']      = $setting;

					$this->User->Bid->create();
					$this->User->Bid->save($freeBidData);
				}
			} elseif(substr($setting, -1) == '%' && is_numeric(substr($setting, 0, -1))) {
				if($this->appConfigurations['simpleBids'] == true) {
					$user['User']['bid_balance'] += $package['Package']['bids'] * (substr($setting, 0, -1) / 100);
					$this->User->save($user);
				} else {
					// Format the data
					$freeBidData['Bid']['user_id'] 	   = $user['User']['id'];
					$freeBidData['Bid']['description'] = __('Free bids given for purchasing bids for the first time.', true);
					$freeBidData['Bid']['credit']      = $package['Package']['bids'] * (substr($setting, 0, -1) / 100);

					// Save the bid
					$this->User->Bid->create();
					$this->User->Bid->save($freeBidData);
				}
			}
		}

		// Adding points
		if(Configure::read('App.rewardsPoint')){
			if(!empty($package['PackagePoint']['points'])){
				$point = $this->User->Point->findByUserId($user['User']['id']);
				if(!empty($point)){
					$point['Point']['points'] += $package['PackagePoint']['points'];
				}else{
					$point['Point']['user_id'] = $user['User']['id'];
					$point['Point']['points']  = $package['PackagePoint']['points'];

					$this->User->Point->create();
				}

				$this->User->Point->save($point);
			}
		}
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('price' => 'asc'));
		$this->set('packages', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Package->create();
			if ($this->Package->save($this->data)) {
				$this->Session->setFlash(__('The package has been added successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the package please review the errors below and try again.', true));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid BidPackage', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Package->save($this->data)) {
				$this->Session->setFlash(__('The package has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem updating the package please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Package->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for BidPackage.', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Package->del($id)) {
			$this->Session->setFlash(__('The package was successfully deleted.', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>
