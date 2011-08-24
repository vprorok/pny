<?php
class PaymentGatewaysController extends AppController{
	var $name = 'PaymentGateways';
	var $uses = array('Auction', 'Package', 'Bid', 'Setting', 'Account', 'Referral', 'Coupon', 'Order');
	var $components = array('Cookie');
	var $helpers = array('Dotpay', 'Epayment', 'GoogleCheckout');
	
	function beforeFilter(){
		parent::beforeFilter();
		if(isset($this->Auth)){
//			echo "add allow! ";
			$this->Auth->allow('returning', 'dotpay_ipn', 'epayment_ipn', 'google_checkout_ipn','paypal_ipn',
					  'paypal_pro_ipn', 'secure_pay_ipn','authorizenet_ipn', 'plimus_ipn','paybox_ipn',
					  'usaepay_ipn',
					  'pagseguro_ipn', 'nbepay_ipn', 'ipay88_ipn');
//			$this->Auth->allow('nbepay_ipn');
		}
	}

	/**
	 * Function to calculate hmac
	 */
	function _hmac($data, $key){
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing
		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
		   $key = pack("H*",md5($key));
		}
		$key  = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;
		
		return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
	}

	/**
	 * Function to set an auction's status
	 */
	function _setAuctionStatus($auction_id, $status_id){
		if(!empty($auction_id) && !empty($status_id)){
			$auction['Auction']['id']        = $auction_id;
			$auction['Auction']['status_id'] = $status_id;
			
			return $this->Auction->save($auction, false);
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function to add credit to user's account
	 */
	function _credit($auction_id, $credit = 0, $debit = 0, $user_id = null){
		if(!empty($auction_id)){
		    if(!empty($user)){
			$credit['Credit']['user_id'] = $user_id;
		    }else{
			$credit['Credit']['user_id'] = $this->Auth->user('id');
		    }
		    $credit['Credit']['auction_id'] = $auction_id;
		    $credit['Credit']['credit']     = $credit;
		    $credit['Credit']['debit']      = $debit;
		
		    $this->Auction->Credit->create();
		
		    return $this->Auction->Credit->save($credit);
		}else{
		    return false;
		}
	}

	/**
	 * Function to add bids to user's account
	 */
	function _bids($user_id = null, $description = null, $credit = 0, $debit = 0){
		if(!empty($user_id) && !empty($description)){
			if($this->appConfigurations['simpleBids'] == true) {
				$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'contain' => ''));
				if($credit > 0) {
					$user['User']['bid_balance'] += $credit;
				} else {
					$user['User']['bid_balance'] -= $debit;
				}
				
				$this->User->save($user, false);
				
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				
				return $bid;
			} else {
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				
				$this->Bid->create();
				
				return $this->Bid->save($bid);
			}
		}else{
			return false;
		}
	}

	/**
	 * Function to add an account entry to user's account
	 */
	function _account($user_id = null, $name, $bids = 0, $price){
		if(!empty($user_id) && !empty($name)){
			$account['Account']['user_id'] = $user_id;
			$account['Account']['name']    = $name;
			$account['Account']['bids']    = $bids;
			$account['Account']['price']   = $price;
			
			$this->Account->create();
			$this->Account->save($account, false);
			$this->log("_account: account record added ($user_id, $name, $bids, $price)", 'payment');
			
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Function to get auction
	 */
	function _getAuction($auction_id, $user_id, $redirect = true){
		$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id)));
		
		if(!empty($auction)){
			// Check if logged user is the winner
			if($auction['Auction']['winner_id'] != $user_id){
				if($redirect){
					$this->Session->setFlash(__('Invalid auction', true));
					$this->redirect(array('controller' => 'auctions', 'action' => 'won'));
				}else{
					return false;
				}
			}
			
			// Check auction status is not paid
			if($auction['Auction']['status_id'] != 1) {
				if($redirect){
					$this->Session->setFlash(__('You have already paid for this auction.', true));
					$this->redirect(array('controller' => 'auctions', 'action' => 'won'));
				}else{
					return false;
				}
			}
			
			// Get the total cost
			$total = 0;
			if(!empty($auction['Product']['fixed'])) {
				$total = $auction['Product']['fixed_price'] + $auction['Product']['delivery_cost'];
			} else {
				$total = $auction['Auction']['price'] + $auction['Product']['delivery_cost'];
			}
			
			// Check credit
			if(Configure::read('App.credits.active')){
				$credits  = $this->Auction->Credit->balance($user_id, Configure::read('App.credits.expiry'));
				$original = $total;
				$total    = $total - $credits;
		
				if($total < 0) {
					$total = 0;
					$auction['Credit']['debit'] = $original;
				} else {
					$auction['Credit']['debit'] = $credits;
				}
			}
			
			$auction['Auction']['total'] = $total;
		}
		
		return $auction;
	}

	/**
	 * Function to get package
	 */
	function _getPackage($package_id, $user_id = null){
		if(!empty($package_id)){
			// Set the user id in session for coupon
			// in package's afterFind()
			if(!empty($user_id)){
	
				// better to put it on PaymentGateway array than
				// Auth.User.id since it will open security hole
				$this->Session->write('PaymentGateway.user_id', $user_id);
	
				// Check validity of user's coupon
				if(Configure::read('App.coupons')){
					if($coupon = Cache::read('coupon_user_'.$user_id)){
						$coupon = $this->Coupon->findByCode(strtoupper($coupon['Coupon']['code']));
					}
				}
			}
	
			return $this->Package->find('first', array('conditions' => array('Package.id' => $package_id)));
		}else{
		    return false;
		}
	}

	/**
	 * Function to check if the addressed already completed
	 */
	function _isAddressCompleted($user_id = null){
		if(empty($user_id)){
		    $user_id = $this->Auth->user('id');
		}
		
		return $this->Auction->Winner->Address->isCompleted($user_id);
	}

	/**
	* Check if this is user first won auction
	*/
	function _firstWin($user_id = null, $auction_id) {
		if(empty($user_id)){
		    $user_id = $this->Auth->user('id');
		}
		
		$won = $this->Auction->find('count', array('conditions' => array('Auction.winner_id' => $user_id)));
		if($won == 1) {
			// Give 'em first winning bonus bid
			$setting = $this->Setting->get('free_won_auction_bids');
			if((is_numeric($setting)) && $setting > 0) {
				$credit = $credit;
			} elseif(substr($setting, -1) == '%' && is_numeric(substr($setting, 0, -1))) {
				$bids  = $this->find('all', array('conditions' => array('Bid.user_id' => $user_id, 'Bid.auction_id' => $auction_id), 'fields' => "SUM(Bid.debit) as 'bids'"));
				if(empty($bids[0][0]['bids'])) {
					$bids[0][0]['bids'] = 0;
				}
	
				$credit = $bids[0][0]['bids'] * (substr($setting, 0, -1) / 100);
				$credit = ceil($credit);
			}
	
			if(!empty($credit) && $credit > 0){
				$description = __('Free bids given for winning your first auction.', true);
				$this->_bids($user_id, $description, $credit, 0);
			}

			return true;
		}else{
			return false;
		}
	}

	/**
	* Check if this is user first bid package purchase
	*/
	function _checkFirstPurchase($user_id = null, $bids = null){
		if(!empty($user_id)){
		    $purchasedBefore = $this->User->Account->find('first', array('conditions' => array('Account.user_id' => $user_id, 'Account.bids >' => 0)));
		    if(empty($purchasedBefore)) {
			// Get the setting
			$setting = $this->Setting->get('free_bid_packages_bids');
		
					// If setting for free bids is not 0
					if((is_numeric($setting)) && $setting > 0) {
						$credit = $setting;
					} elseif(substr($setting, -1) == '%' && is_numeric(substr($setting, 0, -1))) {
						$credit = $bids * (substr($setting, 0, -1) / 100);
					}
		
					$description = __('Free bids given for purchasing bids for the first time.', true);
					$this->_bids($user_id, $description, $credit, 0);
		    }else{
			return false;
		    }
		}else{
		    return false;
		}
	}
	
	/**
	* Check if user referred by another user
	*/
	function _checkReferral($user_id = null){
	// Now we check if this user was referred so we can give the free bids away
		$referral = $this->Referral->find('first', array('conditions' => array('user_id' => $user_id, 'confirmed' => 0)));
	
		if(!empty($referral)) {
			// Get the setting for free bids
			$setting = $this->Setting->get('free_referral_bids');
	
			$description = __('Referral Bids Earned for:', true).' '.$referral['User']['username'];
			$this->_bids($referral['Referral']['referrer_id'], $description, $setting, 0);
	
			// Finally set the referral as confirmed
			$referral['Referral']['confirmed'] = 1;
			unset($referral['Referral']['modified']);
	
			// Save the referral
			$this->User->Referral->save($referral);
		}
	}

	/**
	 * Check reward point whether it's on or off and add points into
	 * user account if necessary
	 */
	function _checkRewardPoints($package_id = null, $user_id = null){
		if($package_id && $user_id){
			// Adding points
			if(Configure::read('App.rewardsPoint')){

				// Set the user id in session for coupon
				// in package's afterFind()
				if(!empty($user_id)){

					// better to put it on PaymentGateway array than
					// Auth.User.id since it will open security hole
					$this->Session->write('PaymentGateway.user_id', $user_id);

					// Check validity of user's coupon
					if(Configure::read('App.coupons')){
						if($coupon = Cache::read('coupon_user_'.$user_id)){
							$coupon = $this->Coupon->findByCode(strtoupper($coupon['Coupon']['code']));
						}
					}
				}

				$package = $this->Package->findById($package_id);

				if(!empty($package['PackagePoint']['points'])){
					$point = $this->User->Point->findByUserId($user_id);
					if(!empty($point)){
						$point['Point']['points'] += $package['PackagePoint']['points'];
					}else{
						$point['Point']['user_id'] = $user_id;
						$point['Point']['points']  = $package['PackagePoint']['points'];

						$this->User->Point->create();
					}

					$this->User->Point->save($point);
				}
			}
		}
	}

	/**
	 * Function for returning from payment gateway
	 */
	function returning($model){
		if(!empty($model)){
			switch($model){
				case 'auction':
					$this->Session->setFlash(__('Your payment was successful.  We will notify you when your item has been shipped.', true));
					$this->redirect(array('controller' => 'auctions', 'action' => 'won'));
					break;

				case 'package':
					$this->Session->setFlash(__('You payment was successful and your bids are available for you use.  If your bids are not available yet, please allow a couple of minutes for them to become available.', true), 'default', array('class'=>'success'));
					$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
					break;
			}
		}else{
			die('I hate you');
		}
	}

	/**
	 * Function for cancelling from payment gateway
	 */
	function cancel($model){
		if(!empty($model)){
			switch($model){
				case 'auction':
					$this->Session->setFlash(__('Your payment was canceled.', true));
					$this->redirect(array('controller' => 'auctions', 'action' => 'won'));
					break;

				case 'package':
					$this->Session->setFlash(__('Your payment was canceled.', true));
					$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
					break;
			}
		}else{
			die('I hate you');
		}
	}

	/**
	* Function for sending notification after auction payment
	*/
	function _sendAuctionNotification($auction, $user_id = null){
		// Get users
		$user = $this->User->findById($user_id);
		
		$data['template'] = 'payment_gateways/auction_pay';
		$data['layout']   = 'default';
		
		// Send to both user and admin
		$data['to'] 	  = array($user['User']['email'], $this->appConfigurations['email']);
		
		$data['subject']  = __('Won Auction Payment', true);
		$data['User']	  = $user['User'];
		
		$this->set('auction', $auction);
		$this->set('user', $data);
		
		if($this->_sendEmail($data)){
		    return true;
		}else{
		    return false;
		}
	}
	
	/**
	* Function for sending notification after package purchase
	*/
	function _sendPackageNotification($package, $user_id = null){
		// Get users
		$user = $this->User->findById($user_id);
		
		$data['template'] = 'payment_gateways/package_buy';
		$data['layout']   = 'default';
		
		// Send to both user and admin
		$data['to'] 	  = array($user['User']['email'], $this->appConfigurations['email']);
		
		$data['subject']  = __('Bid Package Purchased', true);
		$data['User']	  = $user['User'];
		
		$this->set('data', $data);
		$this->set('package', $package);
		
		if($this->_sendEmail($data)){
		    return true;
		}else{
		    return false;
		}
	}
	
    /**
     * Google Checkout function
     * In case the XML API contains multiple open tags
     * with the same value, then invoke this function and
     * perform a foreach on the resultant array.
     * This takes care of cases when there is only one unique tag
     * or multiple tags.Examples of this are "anonymous-address",
     * "merchant-code-string" from the merchant-calculations-callback API
     */
    function get_arr_result($child_node) {
        $result = array();
        if(isset($child_node)) {
            if(is_associative_array($child_node)) {
                $result[] = $child_node;
            } else {
                foreach($child_node as $curr_node){
                    $result[] = $curr_node;
                }
            }
        }
        return $result;
    }

    /* Returns true if a given variable represents an associative array */
    function is_associative_array( $var ) {
        return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
    }

    /**
     * Dotpay payment gateway
     * http://www.dotpay.eu
     */
    function dotpay($model = null, $id = null){
        if(!empty($model)){
            // Get gateway information
            $gateway = Configure::read('PaymentGateways.Dotpay');

            switch($model){
                case 'auction':
                    $auction   = $this->_getAuction($id, $this->Auth->user('id'));
                    $addresses = $this->_isAddressCompleted();
                    $user      = $auction['Winner'];

                    // for detection in ipn
                    // anti fraud
                    $security_code       = sha1(Configure::read('Security.salt') . $this->Auth->user('id'));
                    $data['control']     = 'auction#'.$auction['Auction']['id'].'#'.$this->Auth->user('id') . '#' . $security_code;

                    $data['id']          = $gateway['id'];
                    $data['currency']    = $gateway['currency'];
                    $data['lang']        = $gateway['lang'];
                    $data['URL']         = $gateway['URL'];
                    $data['URLC']        = $gateway['URLC'];
                    $data['amount']      = $auction['Auction']['total'];
                    $data['description'] = $auction['Product']['title'];

                    $data['firstname']   = $user['first_name'];
                    $data['lastname']    = $user['last_name'];
                    $data['email']       = $user['email'];
                    $data['street']      = $addresses['Billing']['address_1'];
                    $data['street_n1']   = $addresses['Billing']['address_2'];
                    $data['city']        = $addresses['Billing']['city'];
                    $data['postcode']    = $addresses['Billing']['postcode'];
                    $data['phone']       = $addresses['Billing']['phone'];
                    $data['country']     = $addresses['Billing']['country_name'];

                    break;
                case 'package':
                    $package   = $this->_getPackage($id);

                    // for detection in ipn
                    // anti fraud
                    $security_code       = sha1(Configure::read('Security.salt') . $this->Auth->user('id'));
                    $data['control']     = 'package#'.$package['Package']['id'].'#'.$this->Auth->user('id') . '#' . $security_code;

                    $data['id']          = $gateway['id'];
                    $data['currency']    = $gateway['currency'];
                    $data['lang']        = $gateway['lang'];
                    $data['URL']         = $gateway['URL'];
                    $data['URLC']        = $gateway['URLC'];
                    $data['amount']      = $package['Package']['price'];
                    $data['description'] = $package['Package']['name'];

                    $data['firstname']   = $this->Auth->user('first_name');
                    $data['lastname']    = $this->Auth->user('last_name');
                    $data['email']       = $this->Auth->user('email');

                    break;
                default:
                    $this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
                    $this->redirect('/');
            }

            $this->set('data', $data);
        }else{
            $this->Session->setFlash(__('Invalid payment gateway', true));
            $this->redirect('/');
        }
    }

    function dotpay_ipn(){
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $data = $_POST;

        if(!empty($data)){
            $control = explode('#', $data['control']);

            $model         = !empty($control[0]) ? $control[0] : null;
            $id            = !empty($control[1]) ? $control[1] : null;
            $user_id       = !empty($control[2]) ? $control[2] : null;
            $security_code = !empty($control[3]) ? $control[3] : null;

            // Anti fraud
            if(sha1(Configure::read('Security.salt') . $user_id) != $security_code){
                return false;
            }

            switch($model){
                case 'auction':
                    if(!empty($data['t_status']) && $data['t_status'] == 2){
                        $this->log('Start changing auction','payment');
                        
                        $this->log('Get the auction','payment');
                        $auction = $this->_getAuction($id, $user_id, false);

                        // Change auction status
                        $status = $this->_setAuctionStatus($id, 2);

                        // Check the first winners bonus
				$this->_firstWin($user_id, $id);
				
				eval(PluginManager::hook('controllers_paymentgateways_paid'));

                        // Lets deduct the spent credits
                        if(Configure::read('App.credit.active')){
                            $this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
                        }

                        // Send notification email
                        $this->_sendAuctionNotification($auction, $user_id);
                    }
                    break;

                case 'package':
                    if(!empty($data['t_status']) && $data['t_status'] == 2){
                        $package = $this->_getPackage($id, $user_id);

                        // Adding bids
                        $description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
                        $credit      = $package['Package']['bids'];
                        $this->_bids($user_id, $description, $credit, 0);

                        // Updating account
                        $name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
                        $bids  = $package['Package']['bids'];
                        $price = $package['Package']['price'];

                        // Add bonus if it's user first purchase
                        $this->_checkFirstPurchase($user_id, $bids);

                        $this->_account($user_id, $name, $bids, $price);

                        // Checking referral bonus
                        $this->_checkReferral($user_id);

						// Check and increase user reward points
						$this->_checkRewardPoints($id, $user_id);

                        // Send notification email
                        $this->_sendPackageNotification($package, $user_id);
                    }
                    break;
            }
        }
    }

    /**
     * Epayment payment gateway
     * http://www.epayment.ro
     */
    function epayment($model = null, $id = null){
		//
    }

    function epayment_ipn(){

    }

    /**
     * Google Checkout payment gateway
     * http://checkout.google.com
     */
    function google_checkout($model = null, $id = null){
		if(!empty($model)){
            // Get gateway information
            $gateway = Configure::read('PaymentGateways.GoogleCheckout');

            switch($model){
                case 'auction':
                    $auction   = $this->_getAuction($id, $this->Auth->user('id'));
                    $addresses = $this->_isAddressCompleted();
                    $user      = $auction['Winner'];

                    $data['merchant_id'] = $gateway['merchant_id'];
                    $data['local']       = $gateway['local'];
                    $data['charset']     = $gateway['charset'];
						$data['sandbox'] 	 = $gateway['sandbox'];
			
						$item['name'] 		 = $auction['Product']['title'];
						$item['description'] = $auction['Product']['title'];
						$item['quantity']    = 1;
						$item['price']       = number_format($auction['Auction']['total'], 2);
						$item['currency']    = $gateway['currency'];
						$item['merchant_id'] = 'auction#'.$id.'#'.$this->Auth->user('id');
			
						$data['items'][] 	 = $item;

                    break;
                case 'package':
                    $package   = $this->_getPackage($id);

                    $data['merchant_id'] = $gateway['merchant_id'];
                    $data['local']       = $gateway['local'];
                    $data['charset']     = $gateway['charset'];
						$data['sandbox'] 	 = $gateway['sandbox'];

						$item['name'] 		 = $package['Package']['name'];
						$item['description'] = $package['Package']['name'];
						$item['quantity']    = 1;
						$item['price']       = number_format($package['Package']['price'], 2);
						$item['currency']    = $gateway['currency'];
						$item['merchant_id'] = 'package#'.$package['Package']['id'].'#'.$this->Auth->user('id');

						$data['items'][] 	 = $item;

                    break;
                default:
                    $this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
                    $this->redirect('/');
            }

            $this->set('data', $data);
        }else{
            $this->Session->setFlash(__('Invalid payment gateway', true));
            $this->redirect('/');
        }
    }

    function google_checkout_ipn(){
        $this->log('Google Checkout : Got ipn request', 'payment');

		Configure::write('debug', 0);
		$this->layout = 'ajax';
        $this->autoRender = false;

        App::import('Vendor', 'googlecheckout/googleresponse');
        App::import('Vendor', 'googlecheckout/googlemerchantcalculations');
        App::import('Vendor', 'googlecheckout/googleresult');
        App::import('Vendor', 'googlecheckout/googlerequest');

        define('RESPONSE_HANDLER_ERROR_LOG_FILE', TMP.'logs'.DS.'googleerror.log');
        define('RESPONSE_HANDLER_LOG_FILE', TMP.'logs'.DS.'googlemessage.log');

        $gateway = Configure::read('PaymentGateways.GoogleCheckout');

        $merchant_id  = $gateway['merchant_id'];
        $merchant_key = $gateway['key'];
        $server_type  = $gateway['sandbox'] ? "sandbox" : false;
        $currency     = $gateway['currency'];  // set to GBP if in the UK

        $Gresponse = new GoogleResponse($merchant_id, $merchant_key);
        $Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);

        //Setup the log file
        $Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE,
                                              RESPONSE_HANDLER_LOG_FILE, L_ALL);

        // Retrieve the XML sent in the HTTP POST request to the ResponseHandler
        $xml_response = isset($HTTP_RAW_POST_DATA)?
                          $HTTP_RAW_POST_DATA:file_get_contents("php://input");

        if (get_magic_quotes_gpc()) {
          $xml_response = stripslashes($xml_response);
        }

        list($root, $data) = $Gresponse->GetParsedXML($xml_response);
        $Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);

        if (!$gateway['skipAuth']) {
		$status = $Gresponse->HttpAuthentication();
		if(! $status) {
		  $this->log('Authentication failed ('.$status.')', 'payment');
		  die('authentication failed');
		}
	}
	
        /* Commands to send the various order processing APIs
         * Send charge order : $Grequest->SendChargeOrder($data[$root]
         *    ['google-order-number']['VALUE'], <amount>);
         * Send process order : $Grequest->SendProcessOrder($data[$root]
         *    ['google-order-number']['VALUE']);
         * Send deliver order: $Grequest->SendDeliverOrder($data[$root]
         *    ['google-order-number']['VALUE'], <carrier>, <tracking-number>,
         *    <send_mail>);
         * Send archive order: $Grequest->SendArchiveOrder($data[$root]
         *    ['google-order-number']['VALUE']);
         *
         */

        $this->log('Google Checkout : Got notification -> '. $root, 'payment');

        switch ($root) {
            case "request-received": {
                break;
            }
            case "error": {
                break;
            }
            case "diagnosis": {
                break;
            }
            case "checkout-redirect": {
                break;
            }
            case "merchant-calculation-callback": {
                break;
            }
            case "new-order-notification": {
                $control = $data[$root]['shopping-cart']['items']['item']['merchant-item-id']['VALUE'];
                $control = explode('#', $control);
                $this->log('Google Checkout: control is '.$control, 'payment');
                
                $this->Order->create();
                $this->Order->save(array('Order'=>array(	'transaction_id'=>$data[$root]['google-order-number']['VALUE'],
                						'method'=>'googlecheckout',
                						'model'=>$control[0],
                						'item_id'=>$control[1],
                						'user_id'=>$control[2],
                						'fulfilled'=>0,
                						)));

                $this->log('Google Checkout ('.$data[$root]['google-order-number']['VALUE'].'): Got new order, Model: '.$control[0].', item: '.$control[1].', User: '.$control[2], 'payment');

                $Gresponse->SendAck();
                break;
            }
            case "order-state-change-notification": {
                $new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
                $new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];

                $this->log('Google Checkout : Financial state -> '. $new_financial_state,'payment');

                switch($new_financial_state) {
                    case 'REVIEWING': {
                        break;
                    }
                    case 'CHARGEABLE': {
                        $this->log('Google Checkout : Got CHARGEABLE status -- '.$data[$root]['google-order-number']['VALUE'], 'payment');

                        $Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
                        $Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE']);
                        break;
                    }
                    case 'CHARGING': {
                        break;
                    }
                    case 'CHARGED': {
                        //$control = Cache::read('googlecheckout_'.$data[$root]['google-order-number']['VALUE']);
                        //Cache::delete('googlecheckout_'.$data[$root]['google-order-number']['VALUE']);
                        $order=$this->Order->findByTransactionId($data[$root]['google-order-number']['VALUE']);
                        if (!$order or empty($order)) {
                        	$this->log('Google Checkout: Order record doesn\'t exist ('.$data[$root]['google-order-number']['VALUE'].')', 'payment');
                        	exit;
                        }
                        if ($order['Order']['fulfilled']) {
                        	$this->log('Google Checkout: Order already fulfilled ('.$data[$root]['google-order-number']['VALUE'].')', 'payment');
                        	exit;
                        }

                        $this->log('Google Checkout ('.$data[$root]['google-order-number']['VALUE'].'): Order charged, Model: '.$order['Order']['model'].', item: '.$order['Order']['item_id'].', User: '.$order['Order']['user_id'], 'payment');

                        // All goes here
                        /*$control = explode('#', $control);

                        $model         = !empty($control[0]) ? $control[0] : null;
                        $id            = !empty($control[1]) ? $control[1] : null;
                        $user_id       = !empty($control[2]) ? $control[2] : null;*/
                        
                        //it's been fulfilled
                        $order['Order']['fulfilled']=1;
                        $this->Order->create();
                        $this->Order->save($order);
                        
                        $model=$order['Order']['model'];
                        $id=$order['Order']['item_id'];
                        $user_id=$order['Order']['user_id'];

                        switch($model){
                            case 'auction':
                                $auction = $this->_getAuction($id, $user_id, false);

                                // Change auction status
                                $status = $this->_setAuctionStatus($id, 2);

                                // Check the first winners bonus
                                $this->_firstWin($user_id, $id);
                                
                                eval(PluginManager::hook('controllers_paymentgateways_paid'));

                                // Lets deduct the spent credits
                                if(Configure::read('App.credit.active')){
                                    $this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
                                }

                                // Send notification email
                                $this->_sendAuctionNotification($auction, $user_id);
                                break;

                            case 'package':
                                $package = $this->_getPackage($id, $user_id);

                                // Adding bids
                                $description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
                                $credit      = $package['Package']['bids'];
                                $this->_bids($user_id, $description, $credit, 0);

                                // Updating account
                                $name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
                                $bids  = $package['Package']['bids'];
                                $price = $package['Package']['price'];

                                // Add bonus if it's user first purchase
                                $this->_checkFirstPurchase($user_id, $bids);

                                $this->_account($user_id, $name, $bids, $price);

                                // Checking referral bonus
                                $this->_checkReferral($user_id);

                                // Check and increase user reward points
                                $this->_checkRewardPoints($id, $user_id);

                                // Send notification email
                                $this->_sendPackageNotification($package, $user_id);

                                break;
                        }

                        break;
                    }
                    case 'PAYMENT_DECLINED': {
                        break;
                    }
                    case 'CANCELLED': {
                        Cache::delete('googlecheckout_'.$data[$root]['google-order-number']['VALUE']);
                        break;
                    }
                    case 'CANCELLED_BY_GOOGLE': {
                        Cache::delete('googlecheckout_'.$data[$root]['google-order-number']['VALUE']);
                        //$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
                        //"Sorry, your order is cancelled by Google", true);
                        break;
                    }
                    default:
                        break;
                }

                switch($new_fulfillment_order) {
                    case 'NEW': {
                        break;
                    }
                    case 'PROCESSING': {
                        break;
                    }
                    case 'DELIVERED': {
                        break;
                    }
                    case 'WILL_NOT_DELIVER': {
                        break;
                    }
                    default:
                        break;
                }

                $Gresponse->SendAck();
                break;
            }
            case "charge-amount-notification": {
                //$Grequest->SendDeliverOrder($data[$root]['google-order-number']['VALUE'],
                //    <carrier>, <tracking-number>, <send-email>);
                //$Grequest->SendArchiveOrder($data[$root]['google-order-number']['VALUE'] );
                $Gresponse->SendAck();
                break;
            }
            case "chargeback-amount-notification": {
                $Gresponse->SendAck();
                break;
            }
            case "refund-amount-notification": {
                $Gresponse->SendAck();
                break;
            }
            case "risk-information-notification": {
                $Gresponse->SendAck();
                break;
            }
            default:
                $Gresponse->SendBadRequestStatus("Invalid or not supported Message -- ".$root);
                break;
        }
    }
	
	/**
	* Paypal payment gateway
	* http://www.paypal.com
	*/
	function paypal($model = null, $id = null){
		$gateway = Configure::read('PaymentGateways.Paypal') ? Configure::read('PaymentGateways.Paypal') : Configure::read('Paypal');
		$paypal  = array();

		if(!empty($model)){
			$paypal['cancel_return']= Configure::read('App.url') . '/users';
			$paypal['notify_url']   = Configure::read('App.url') . '/payment_gateways/paypal_ipn';
			$paypal['url']		= $gateway['url'];
			$paypal['business']	= $gateway['email'];
			$paypal['lc']		= $gateway['lc'];
			$paypal['currency_code']= Configure::read('App.currency');
			$paypal['custom'] 	= $model . '#' . $id . '#' . $this->Auth->user('id');
			$paypal['charset'] 	= Configure::read('App.encoding');
			
			switch($model){
				case 'auction':
					$auction   = $this->_getAuction($id, $this->Auth->user('id'));
					$addresses = $this->_isAddressCompleted();
					$user      = $auction['Winner'];
					
					// Formating the data
					$paypal['return'] 	     = Configure::read('App.url') . '/payment_gateways/returning/auction';
					$paypal['item_name']   = $auction['Product']['title'];
					$paypal['item_number'] = $auction['Auction']['id'];
					$paypal['amount']      = number_format($auction['Auction']['total'], 2);
					$paypal['first_name']  = $this->Auth->user('first_name');
					$paypal['last_name']   = $this->Auth->user('last_name');
					$paypal['email']       = $this->Auth->user('email');
					$paypal['address1']    = $addresses['Billing']['address_1'];
					$paypal['address2']    = $addresses['Billing']['address_2'];
					$paypal['city']    	   = $addresses['Billing']['city'];
					$paypal['zip']    	   = $addresses['Billing']['postcode'];
					
					break;
				case 'package':
					$package   = $this->_getPackage($id);
					
					// Formating the data
					$paypal['return'] 	     = Configure::read('App.url') . '/payment_gateways/returning/package';
					$paypal['item_name']   = $package['Package']['name'];
					$paypal['item_number'] = $package['Package']['id'];
					$paypal['amount']      = number_format($package['Package']['price'], 2);
					$paypal['first_name']  = $this->Auth->user('first_name');
					$paypal['last_name']   = $this->Auth->user('last_name');
					$paypal['email']       = $this->Auth->user('email');
					
					break;
				default:
				$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
				$this->redirect('/');
			}

			$this->Paypal->configure($paypal);
			$paypalData = $this->Paypal->getFormData();
			$this->set('paypalData', $paypalData);
		}else{
			$this->Session->setFlash(__('Invalid payment gateway', true));
		}
	}

	function paypal_ipn(){
			
		$this->log('PAYPALIPN: Started', 'payment');
		
		$gateway = Configure::read('PaymentGateways.Paypal') ? Configure::read('PaymentGateways.Paypal') : Configure::read('Paypal');
		$this->Paypal->configure($gateway);
		
		if($this->Paypal->validate_ipn()) {
			$this->log('PAYPALIPN: Validated', 'payment');

			if(strtolower($this->Paypal->ipn_data['payment_status']) == 'completed' || 
				(strtolower($this->Paypal->ipn_data['payment_status']) == 'pending' 
					&& strtolower($this->Paypal->ipn_data['pending_reason'])=='unilateral')) {
			
				$this->log('PAYPALIPN: Next step: payment_status is '.$this->Paypal->ipn_data['payment_status'], 'payment');
				
				// Read the info
				$control = explode('#', $this->Paypal->ipn_data['custom']);

				$model         = !empty($control[0]) ? $control[0] : null;
				$id            = !empty($control[1]) ? $control[1] : null;
				$user_id       = !empty($control[2]) ? $control[2] : null;

				$this->log("PAYPALIPN: product $model/$id/$user_id", 'payment');
				
				$this->log("PAYPALIPN: Checking duplicate transaction", 'payment');
				$orders=$this->Order->findByTransactionId($this->Paypal->ipn_data['txn_id']);
				if (!empty($orders)) {
					$this->log("FAILURE! Duplicate transaction ID: ".$this->Paypal->ipn_data['txn_id'], 'payment');
					return;
				}
				$this->log("PAYPALIPN: OK", 'payment');
				
				if ($this->Paypal->ipn_data['business']!=$gateway['email']) {
					$this->log("FAILURE! Payment sent to {$this->Paypal->ipn_data['business']}, expected {$gateway['email']}", 'payment');
					return;
				}
				
				$this->Order->create();
				$this->Order->save(array('Order'=>array('transaction_id'=>$this->Paypal->ipn_data['txn_id'],
									'method'=>'paypal',
									'model'=>$model,
									'item_id'=>$id,
									'user_id'=>$user_id,
									'fulfilled'=>1,
									)));
				$this->log("PAYPALIPN: Saved into order table.", 'payment');
				
				switch($model){
					case 'auction':
						$this->log('PAYPALIPN: Auction switch', 'payment');
						$auction = $this->_getAuction($id, $user_id, false);

						// Change auction status
						$status = $this->_setAuctionStatus($id, 2);
						$this->log('PAYPALIPN: Auction status changed', 'payment');
						
						// Check the first winners bonus
						$this->_firstWin($user_id, $id);

						// Lets deduct the spent credits
						if(Configure::read('App.credit.active')){
							$this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
							$this->log('PAYPALIPN: Credits deducted', 'payment');
						}
						
						// Send notification email
						$this->_sendAuctionNotification($auction, $user_id);
						
						eval(PluginManager::hook('controllers_paymentgateways_paid'));
						
						$this->log('PAYPALIPN: Auction notification sent', 'payment');
						break;

					case 'package':
						$this->log('PAYPALIPN: Package switch', 'payment');
						$package = $this->_getPackage($id, $user_id);

						if(!$this->Paypal->ipn_data['mc_gross'] == $package['Package']['price']) {
							$this->log('PAYPALIPN: FAILURE! Wrong price: '.$this->Paypal->ipn_data['mc_gross'].' == '.$package['Package']['price'], 'payment');
						} elseif ($gateway['lc']!=$this->Paypal->ipn_data['mc_currency']) {
							$this->log('PAYPALIPN: FAILURE! Wrong currency: '.$this->Paypal->ipn_data['mc_currency'].' == '.$gateway['lc'], 'payment');
						} else {
							$this->log('PAYPALIPN: price correct', 'payment');
							// Adding bids
							$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
							$credit      = $package['Package']['bids'];
							$this->_bids($user_id, $description, $credit, 0);

							// Updating account
							$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
							$bids  = $package['Package']['bids'];
							$price = $package['Package']['price'];

							// Add bonus if it's user first purchase
							$this->_checkFirstPurchase($user_id, $bids);

							$this->_account($user_id, $name, $bids, $price);
							$this->log('PAYPALIPN: account updated', 'payment');

							// Checking referral bonus
							$this->_checkReferral($user_id);
							$this->log('PAYPALIPN: referral checked', 'payment');

							// Check and increase user reward points
							$this->_checkRewardPoints($id, $user_id);
							$this->log('PAYPALIPN: reward points checked', 'payment');
							
							// Send notification email
							$this->_sendPackageNotification($package, $user_id);
							$this->log('PAYPALIPN: notification sent', 'payment');
							
							$this->log('PAYPALIPN: Complete!', 'payment');
						}
						break;
					default:
						$this->log('PAYPALIPN: Invalid model ('.$model.')!', 'payment');
				}
			} else {
				$this->log('PAYPALIPN: Not Completed, rather '.strtolower($this->Paypal->ipn_data['payment_status']), 'payment');
			}
		} else {
			$this->log('PAYPALIPN: VALIDATION FAILED', 'payment');
		}
		
		$this->log('PAYPALIPN: End function', 'payment');
		
	}
	
	/**
	* Paypal Pro payment gateway
	* http://www.paypal.com
	*/
	function paypal_pro($model = null, $id = null){
	
	}
	
	function paypal_pro_ipn(){
	
	}
	
	/**
	* Secure Pay payment gateway
	* http://www.securepay.com
	*/
	function secure_pay($model = null, $id = null){
	
	}
	
	function secure_pay_ipn(){
	
	}

	/**
	 * iDeal payment gateway through TargetPay
	 * http://www.targetpay.nl
	 */
	function ideal($model = null, $id = null){
		if(!empty($model)){
			App::import('Vendor', 'ideal'.DS.'ideal');

			// Get gateway information
			$gateway = Configure::read('PaymentGateways.iDeal');

			if(empty($gateway['layout']) or !$gateway['layout']){
				die('TargetPay Layout Number required');
			}

			// Set the layout number
			$rtlo = $gateway['layout'];
			$security = Security::hash($model.$id.$this->Auth->user('id'), 'sha1', true);

			switch($model){
				case 'auction':
					$auction   = $this->_getAuction($id, $this->Auth->user('id'));
					$addresses = $this->_isAddressCompleted();
					$user      = $auction['Winner'];
					
					$amount 	 = $auction['Auction']['total']  * 100; // targetpay accept in eurocent
					$description = $auction['Product']['title'];
			
					// Writing cache for type
					$control = 'auction#'.$auction['Auction']['id'].'#'.$this->Auth->user('id');
					Cache::write('ideal_control_'.$security, $control);
					
					break;
				case 'package':
					$package   = $this->_getPackage($id);
					
					$amount 	 = $package['Package']['price']  * 100; // targetpay accept in eurocent
					$description = $package['Package']['name'];
			
					// Writing cache for type
					$control = 'package#'.$package['Package']['id'].'#'.$this->Auth->user('id');
					Cache::write('ideal_control_'.$security, $control);
					
					break;
				default:
					$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
					$this->redirect('/');
			}

			// Create the iDEAL object
			$ideal       = new iDEAL($rtlo);
			$return_url  = Configure::read('App.url').'/payment_gateways/ideal_return/'.$security.'/';

			// This will be called after user select their bank
			if(!empty($_POST['bank'])){
				$result = $ideal->GetLink($_POST["bank"], $description, $amount, $return_url);
				if(!empty($result)){
					// writing cookie for return use
					Cache::write('ideal_trx_id_'.$security, $ideal->trxid);

					$this->set('result', $result);
					$this->set('ideal_trxid', $ideal->trxid);
					$this->set('ideal_url', $ideal->url);
				}else{
					Cache::delete('ideal_control_'.$this->Auth->user('id'));
					$this->set('ideal_error', $ideal->error);
				}

				$this->render('ideal_bank');
			}
		}else{
		    $this->Session->setFlash(__('Invalid payment gateway', true));
		    $this->redirect('/');
		}
	}

	function ideal_return($security = null){
		$this->layout = 'ajax';
		App::import('Vendor', 'ideal'.DS.'ideal');

		if(empty($security)){
			$this->Session->setFlash(__('Invalid payment. Please contact administrator.', true));
			$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
		}

		// Get gateway information
		$gateway = Configure::read('PaymentGateways.iDeal');

		if(empty($gateway['layout'])){
			$this->log('TargetPay Layout Number required', 'payment');
			die('TargetPay Layout Number required');
		}

		// Set the layout number
		$rtlo   = $gateway['layout'];

		// Create ideal object
		$ideal  = new iDEAL($rtlo);

		// Get the control var
		$control = Cache::read('ideal_control_'.$security);
		$trx_id  = Cache::read('ideal_trx_id_'.$security);

		if(empty($trx_id) || empty($control)){
			$this->Session->setFlash(__('Payment has been canceled because there is no transaction id recorded. Please contact administrator.', true));
			$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
		}

		// Get the result
		$result = $ideal->CheckPayment($trx_id, 1);

		$control = explode('#', $control);
		$model   = !empty($control[0]) ? $control[0] : null;
		$id      = !empty($control[1]) ? $control[1] : null;
		$user_id = !empty($control[2]) ? $control[2] : null;

		if(!empty($result)){
			switch($model){
				case 'auction':
					$this->log('Start changing auction', 'payment');
					$this->log('Get the auction', 'payment');
					$auction = $this->_getAuction($id, $user_id, false);

					// Change auction status
					$status = $this->_setAuctionStatus($id, 2);

					// Check the first winners bonus
					$this->_firstWin($user_id, $id);

					eval(PluginManager::hook('controllers_paymentgateways_paid'));

					// Lets deduct the spent credits
					if(Configure::read('App.credit.active')){
						$this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
					}

                    // Send notification email
                    $this->_sendAuctionNotification($auction, $user_id);
					break;

				case 'package':
					$package = $this->_getPackage($id, $user_id);

					// Adding bids
					$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
					$credit      = $package['Package']['bids'];
					$this->_bids($user_id, $description, $credit, 0);

					// Updating account
					$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
					$bids  = $package['Package']['bids'];
					$price = $package['Package']['price'];

					// Add bonus if it's user first purchase
					$this->_checkFirstPurchase($user_id, $bids);

					$this->_account($user_id, $name, $bids, $price);

					// Checking referral bonus
					$this->_checkReferral($user_id);

					// Check and increase user reward points
					$this->_checkRewardPoints($id, $user_id);

                    // Send notification email
                    $this->_sendPackageNotification($package, $user_id);
					break;

				default:
					$this->Session->setFlash(sprintf(__('There is no handler for this type.', true)));
					$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
			}

			// Remove the savings variables
			Cache::delete('ideal_trx_id_'.$security);
			Cache::delete('ideal_control_'.$security);

			$this->Session->setFlash(__('The payment has been made. Thank you.', true), 'default', array('class' => 'success'));
			$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
		}else{
			$this->Session->setFlash(sprintf(__('The payment cannot be processed. %s', true), $ideal->error));
			$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
		}
	}

	public function usaepay($model = null, $id = null)
	{
		$cfg = Configure::read('PaymentGateways.Usaepay');
		$enabled = $cfg['active'];

		if(!$enabled)
		{
			$this->Session->setFlash('There is no handler for USA ePay payments.');
			$this->redirect('/');
		}

		// check if user has plugin for usaepay...
		$test = $this->requestAction(array('plugin' => 'usaepay', 'controller' => 'usaepay', 'action' => 'test'));
		if($test!=1)
		{
				$this->Session->setFlash('There is no handler for USA ePay payments.');
				$this->redirect('/');
		}

		// payment info entered - try to make a payment
		if(!empty($this->data))
		{
			switch($model)
			{
				case 'auction':
					$auction   = $this->_getAuction($id, $this->Auth->user('id'));
					$addresses = $this->_isAddressCompleted();
					$user      = $auction['Winner'];
					
					$amount 	 = $auction['Auction']['total'];
					$description = $auction['Product']['title'];
					$control     = 'auction#'.$auction['Auction']['id'].'#'.$this->Auth->user('id');
				break;

				case 'package':
					$package   = $this->_getPackage($id, $this->Auth->user('id'));

					$amount 	 = $package['Package']['price'];
					$description = $package['Package']['name'];
					$control     = 'package#'.$package['Package']['id'].'#'.$this->Auth->user('id');
				break;

				default:
					$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
					$this->redirect('/');
			}
			$cfg['model'] = $model;
			$cfg['amount'] = $amount;

			$cfg['card'] = $this->data['Usaepay']['card'];
			$cfg['cardholder'] = $this->data['Usaepay']['cardholder'];
			$cfg['street'] = $this->data['Usaepay']['street'];
			$cfg['zip'] = $this->data['Usaepay']['zip'];
			$cfg['exp'] = $this->data['Usaepay']['exp']['month'].substr($this->data['Usaepay']['exp']['year'], 2, 2);
			$cfg['cvv2'] = $this->data['Usaepay']['cvv2'];
			$cfg['invoice'] = date("YmdHiS"); //$this->getOrderNumber('usaepay');
			
			$usaepaytmp = $this->requestAction(array('plugin' => 'usaepay', 'controller' => 'usaepay', 'action' => 'pay'), $cfg); //, array('return'));
			$status = $usaepaytmp['status'];
			$error = $usaepaytmp['error'];
			
			if($status)
			{
				$user_id = $this->Auth->user('id');

				switch($model){
					case 'auction':
						$auction = $this->_getAuction($id, $user_id, false);

						// Change auction status
						$status = $this->_setAuctionStatus($id, 2);

						// Give 'em first winning bonus bid
						$this->_firstWin($user_id);

						eval(PluginManager::hook('controllers_paymentgateways_paid'));

						// Lets deduct the spent credits
						if(Configure::read('App.credit.active')){
							$this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
						}

						// Send notification email
						$this->_sendAuctionNotification($auction, $user_id);
						break;

					case 'package':
						$package = $this->_getPackage($id, $user_id);

						// Adding bids
						$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
						$credit      = $package['Package']['bids'];
						$this->_bids($user_id, $description, $credit, 0);

						// Updating account
						$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
						$bids  = $package['Package']['bids'];
						$price = $package['Package']['price'];

						// Add bonus if it's user first purchase
						$this->_checkFirstPurchase($user_id, $bids);

						$this->_account($user_id, $name, $bids, $price);

						// Checking referral bonus
						$this->_checkReferral($user_id);

						// Check and increase user reward points
						$this->_checkRewardPoints($id, $user_id);

						// Send notification email
						$this->_sendPackageNotification($package, $user_id);
						break;
				}
				$this->Session->setFlash(__('The payment has been made. Thank you.', true), 'default', array('class' => 'success'));
				//Not in MVC paradigm, but $this->render() sometimes breaks authorize.net callback
				echo '<script>window.location=\''.Configure::read('App.url').'/users\';</script>'; flush(); exit;
			}
			else
			{
				// payment failed
				$this->set('message', 'Error occured.');
				$this->log('Error occured when trying usa ePay payment, error: '.$error, 'payment');
			}
		}

		$this->set('formurl', $this->here);
	}

	public function ipay88($model = null, $id = null)
	{
		// check signature on:
//		http://www.mobile88.com/epayment/testing/testsignature.asp

//		$presignature = $merchantKey.$merchantCode.$refNo.$amount.$currency;
//		$signature = sha1($presignature);

		$gateway = Configure::read('PaymentGateways.iPay88');
		$enabled = $gateway['active'];

		if(!$enabled)
		{
			$this->Session->setFlash('There is no handler for iPay88 payments.');
			$this->redirect('/');
		}

		// check if user has plugin for ipay88...
		$test = $this->requestAction(array('plugin' => 'ipay88', 'controller' => 'ipay88', 'action' => 'test'));
		if($test!=1)
		{
				$this->Session->setFlash('There is no handler for iPay88 payments.');
				$this->redirect('/');
		}
		
		if(!empty($model))
		{
			$gateway['model'] = $model;
			$gateway['id'] = $id;
			
			switch($model)
			{
				case 'auction':
					$auction   = $this->_getAuction($id, $this->Auth->user('id'));
					$addresses = $this->_isAddressCompleted();
					$user      = $auction['Winner'];
					$amount    = number_format($auction['Auction']['total'], 0);

					$gateway['item_id']    = $id;
					$gateway['ProdDesc'] = $auction['Product']['title'];
				break;

				case 'package':
					$package   = $this->_getPackage($id);
					$amount    = number_format($package['Package']['price'], 0);

					$gateway['item_id']    = $id;
					$gateway['ProdDesc'] = $package['Package']['name'];
				break;

				default:
					$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
					$this->redirect('/');
				break;
			}
			
			
			$gateway['amount'] = str_replace(array(',', '.', ' '), '', $amount);

			$data = $this->requestAction(array('plugin' => 'ipay88', 'controller' => 'ipay88', 'action' => 'generate_data'), $gateway);
			$this->set('data', $data);
		}
		else
		{
			$this->Session->setFlash(__('Invalid payment gateway', true));
		}
	}


	public function ipay88_ipn($model = null, $id = null)
	{
//		$_POST variables:
		/**
		 * PaymentId = optional, like sended
		 * RefNo = Unique merchant transaction number / order id
		 * Amount = payment amount with two decimals
		 * Currency = reffer to appendix
		 * Status = 1=success or 0=fail
		 * ErrDesc = optional, error description
		 * Signature - Signature, refer to 1.1.11
		 *
		 */

		 $gateway = Configure::read('PaymentGateways.iPay88');
		$enabled = $gateway['active'];

		if(!$enabled)
		{
			$this->Session->setFlash('There is no handler for iPay88 payments.');
			$this->redirect('/');
		}

		// check if user has plugin for ipay88...
		$test = $this->requestAction(array('plugin' => 'ipay88', 'controller' => 'ipay88', 'action' => 'test'));
		if($test!=1)
		{
				$this->Session->setFlash('There is no handler for iPay88 payments.');
				$this->redirect('/');
		}


		$verified = $this->requestAction(array('plugin' => 'ipay88', 'controller' => 'ipay88', 'action' => 'verify'), Configure::read('PaymentGateways.iPay88')); //, array('return'));

		if($verified!==FALSE && is_array($verified) && count($verified)>0)
		{
			list($user, $model, $id, $time) = $verified;
		
			switch($model)
			{
				case 'auction':
					$this->log('Start changing auction','payment');

					$this->log('Get the auction','payment');
					$auction = $this->_getAuction($id, $user, false);

					// Change auction status
					$status = $this->_setAuctionStatus($id, 2);

					// Check the first winners bonus
					$this->_firstWin($user, $id);

					eval(PluginManager::hook('controllers_paymentgateways_paid'));

					// Lets deduct the spent credits
					if(Configure::read('App.credit.active')){
					 $this->_credit($id, 0, $auction['Credit']['debit'], $user);
					}

					// Send notification email
					$this->_sendAuctionNotification($auction, $user);

				break;

				case 'package':

					$package = $this->_getPackage($id, $user);

					// Adding bids
					$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
					$credit      = $package['Package']['bids'];
					$this->_bids($user, $description, $credit, 0);

					// Updating account
					$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
					$bids  = $package['Package']['bids'];
					$price = $package['Package']['price'];

					// Add bonus if it's user first purchase
					$this->_checkFirstPurchase($user, $bids);

					$this->_account($user, $name, $bids, $price);

					// Checking referral bonus
					$this->_checkReferral($user);

					// Check and increase user reward points
					$this->_checkRewardPoints($id, $user);

					// Send notification email
					$this->_sendPackageNotification($package, $user);
				break;
			}
			$this->returning($model);
		}
		else
		{
			exit();
				$this->Session->setFlash(__('Error occured durning payment process.', true), 'default', array('class' => 'warning'));
				//Not in MVC paradigm, but $this->render() sometimes breaks authorize.net callback
				echo '<script>window.location=\''.Configure::read('App.url').'/users\';</script>'; flush(); exit;
		}
	}

	public function firstdata($model = null, $id = null)
	{
		if(!$model || !$id)
		{
			$this->Session->setFlash(__('Invalid payment gateway', true));
			$this->redirect('/');
		}

//		$this->layout = 'ajax';
		$gateway = Configure::read('PaymentGateways.Firstdata');
//				'merchant_id' => 'test3636',
//				'url'			  => 'https://www.onlinepayment.com.my/NBepay/pay/',
////				'url'			  => 'https://www.onlinepayment.com.my/NBepay/pay/test3636/?amount=12.99&orderid=DG873'
//				'country'     => '',
//				'cur'         => 'rm', // possible values: rm, myr, usd, ntd, twd, rmb, cny
		$enabled = $gateway['active'];

		// check if gateway is enabled in config and if user has plugin for nbepay
		if(!$enabled || $this->requestAction(array('plugin' => 'firstdata', 'controller' => 'firstdata', 'action' => 'test'))!=1)
		{
			$this->Session->setFlash('There is no handler for firstdata payments.');
			$this->redirect('/');
		}

		if(!empty($this->data))
		{
//			echo "data: <br/><pre>";
//			print_r($this->data);
//			echo "</pre><br/>";
			$status = $this->requestAction(array('plugin' => 'firstdata', 'controller' => 'firstdata', 'action' => 'pay'), array_merge($gateway, $this->data));
			//send request

			if($status==true)
			{
//				list($user, $model, $id, $time) = $verified;

				switch($model)
					{
						case 'auction':
							$this->log('Start changing auction','payment');

							$this->log('Get the auction','payment');
							$auction = $this->_getAuction($id, $user, false);

							// Change auction status
							$status = $this->_setAuctionStatus($id, 2);

							// Check the first winners bonus
							$this->_firstWin($user, $id);

							eval(PluginManager::hook('controllers_paymentgateways_paid'));

							// Lets deduct the spent credits
							if(Configure::read('App.credit.active')){
							 $this->_credit($id, 0, $auction['Credit']['debit'], $user);
							}

							// Send notification email
							$this->_sendAuctionNotification($auction, $user);

						break;

						case 'package':

							$package = $this->_getPackage($id, $user);

							// Adding bids
							$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
							$credit      = $package['Package']['bids'];
							$this->_bids($user, $description, $credit, 0);

							// Updating account
							$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
							$bids  = $package['Package']['bids'];
							$price = $package['Package']['price'];

							// Add bonus if it's user first purchase
							$this->_checkFirstPurchase($user, $bids);

							$this->_account($user, $name, $bids, $price);

							// Checking referral bonus
							$this->_checkReferral($user);

							// Check and increase user reward points
							$this->_checkRewardPoints($id, $user);

							// Send notification email
							$this->_sendPackageNotification($package, $user);
						break;
					}
					$this->returning($model);
			}
			else
			{
				
					$this->Session->setFlash(__('Error occured durning payment process.', true), 'default', array('class' => 'warning'));
					//Not in MVC paradigm, but $this->render() sometimes breaks authorize.net callback
					echo '<script>window.location=\''.Configure::read('App.url').'/users\';</script>'; flush(); exit;
					
			}













		}
		else
		{
			if(!empty($model))
			{
				$gateway['model'] = $model;
				$gateway['id'] = $id;

				switch($model)
				{
					case 'auction':
						$auction   = $this->_getAuction($id, $this->Auth->user('id'));
						$addresses = $this->_isAddressCompleted();
						$user      = $auction['Winner'];
						$amount    = number_format($auction['Auction']['total'], 0);

						$gateway['item_id']    = $id;
						$gateway['item_title'] = $auction['Product']['title'];
					break;

					case 'package':
						$package   = $this->_getPackage($id);
						$amount    = number_format($package['Package']['price'], 0);

						$gateway['item_id']    = $id;
						$gateway['item_title'] = $package['Package']['name'];
					break;

					default:
						$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
						$this->redirect('/');
					break;
				}
				$gateway['order_id'] = time();

				$gateway['amount'] = $amount;
				$this->set('data', $gateway);
				$this->set('formurl', $this->here);
//				$url = $this->requestAction(array('plugin' => 'firstdata', 'controller' => 'firstdata', 'action' => 'generate_data'), $gateway);
	//			$this->redirect($url);

			}
		}
	}

	public function nbepay($model = null, $id = null)
	{
		$this->layout = 'ajax';
		$gateway = Configure::read('PaymentGateways.Nbepay');
//				'merchant_id' => 'test3636',
//				'url'			  => 'https://www.onlinepayment.com.my/NBepay/pay/',
////				'url'			  => 'https://www.onlinepayment.com.my/NBepay/pay/test3636/?amount=12.99&orderid=DG873'
//				'country'     => '',
//				'cur'         => 'rm', // possible values: rm, myr, usd, ntd, twd, rmb, cny
		$enabled = $gateway['active'];

		// check if gateway is enabled in config and if user has plugin for nbepay
		if(!$enabled || $this->requestAction(array('plugin' => 'nbepay', 'controller' => 'nbepay', 'action' => 'test'))!=1)
		{
			$this->Session->setFlash('There is no handler for NBePay payments.');
			$this->redirect('/');
		}
		
		if(!empty($model))
		{
			$gateway['model'] = $model;
			$gateway['id'] = $id;

			switch($model)
			{
				case 'auction':
					$auction   = $this->_getAuction($id, $this->Auth->user('id'));
					$addresses = $this->_isAddressCompleted();
					$user      = $auction['Winner'];
					$amount    = number_format($auction['Auction']['total'], 0);

					$gateway['item_id']    = $id;
					$gateway['item_title'] = $auction['Product']['title'];
				break;

				case 'package':
					$package   = $this->_getPackage($id);
					$amount    = number_format($package['Package']['price'], 0);

					$gateway['item_id']    = $id;
					$gateway['item_title'] = $package['Package']['name'];
				break;

				default:
					$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
					$this->redirect('/');
				break;
			}
			$gateway['order_id'] = time();
			
			$gateway['amount'] = $amount;

			$url = $this->requestAction(array('plugin' => 'nbepay', 'controller' => 'nbepay', 'action' => 'generate_url'), $gateway);
			$this->redirect($url);
			
		}
		else
		{
			$this->Session->setFlash(__('Invalid payment gateway', true));
			$this->redirect('/');
		}
	}

	public function nbepay_ipn()
	{
		$this->log('Nbepay: nbepay_ipn, yeah, returning... ','payment');

		Configure::write('debug', 1);
		$this->layout = 'ajax';

		$verified = $this->requestAction(array('plugin' => 'nbepay', 'controller' => 'nbepay', 'action' => 'verify'), Configure::read('PaymentGateways.Nbepay')); //, array('return'));
		
		if($verified!==FALSE && is_array($verified) && count($verified)>0)
		{
			list($user, $model, $id, $time) = $verified;
			
			switch($model)
				{
					case 'auction':
						$this->log('Start changing auction','payment');

						$this->log('Get the auction','payment');
						$auction = $this->_getAuction($id, $user, false);

						// Change auction status
						$status = $this->_setAuctionStatus($id, 2);

						// Check the first winners bonus
						$this->_firstWin($user, $id);

						eval(PluginManager::hook('controllers_paymentgateways_paid'));

						// Lets deduct the spent credits
						if(Configure::read('App.credit.active')){
						 $this->_credit($id, 0, $auction['Credit']['debit'], $user);
						}

						// Send notification email
						$this->_sendAuctionNotification($auction, $user);

					break;

					case 'package':

						$package = $this->_getPackage($id, $user);

						// Adding bids
						$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
						$credit      = $package['Package']['bids'];
						$this->_bids($user, $description, $credit, 0);

						// Updating account
						$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
						$bids  = $package['Package']['bids'];
						$price = $package['Package']['price'];

						// Add bonus if it's user first purchase
						$this->_checkFirstPurchase($user, $bids);

						$this->_account($user, $name, $bids, $price);

						// Checking referral bonus
						$this->_checkReferral($user);

						// Check and increase user reward points
						$this->_checkRewardPoints($id, $user);

						// Send notification email
						$this->_sendPackageNotification($package, $user);
					break;
				}
				$this->returning($model);
		}
		else
		{
			exit();
				$this->Session->setFlash(__('Error occured durning payment process.', true), 'default', array('class' => 'warning'));
				//Not in MVC paradigm, but $this->render() sometimes breaks authorize.net callback
				echo '<script>window.location=\''.Configure::read('App.url').'/users\';</script>'; flush(); exit;
		}

		//this method is designed for requests from paybox servers - one of requirements is to return nothing. :-)
		//TODO: or maybe display some thank you..?
		
    }

	public function paybox_ipn()
	{
		$this->log('requested paybox_ipn.. ', 'paybox');
		Configure::write('debug', 1);
		$this->layout = 'ajax';

		$this->log('before verify.. ', 'paybox');
		$verified = $this->requestAction(array('plugin' => 'paybox', 'controller' => 'paybox', 'action' => 'verify')); //, array('return'));
		$this->log('after verify: '.(int)$verified.' '.$verified, 'paybox');

		if($verified===TRUE)
		{
				switch($model)
				{
					case 'auction':
						$this->log('Start changing auction','payment');

						$this->log('Get the auction','payment');
						$auction = $this->_getAuction($id, $user, false);

						// Change auction status
						$status = $this->_setAuctionStatus($id, 2);

						// Check the first winners bonus
						$this->_firstWin($user, $id);

						eval(PluginManager::hook('controllers_paymentgateways_paid'));

						// Lets deduct the spent credits
						if(Configure::read('App.credit.active')){
						 $this->_credit($id, 0, $auction['Credit']['debit'], $user);
						}

						// Send notification email
						$this->_sendAuctionNotification($auction, $user);
					
					break;

					case 'package':

						$package = $this->_getPackage($id, $user);

						// Adding bids
						$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
						$credit      = $package['Package']['bids'];
						$this->_bids($user, $description, $credit, 0);

						// Updating account
						$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
						$bids  = $package['Package']['bids'];
						$price = $package['Package']['price'];

						// Add bonus if it's user first purchase
						$this->_checkFirstPurchase($user, $bids);

						$this->_account($user, $name, $bids, $price);

						// Checking referral bonus
						$this->_checkReferral($user);

						// Check and increase user reward points
						$this->_checkRewardPoints($id, $user);

						// Send notification email
						$this->_sendPackageNotification($package, $user);
					break;
				}
		}

		//this method is designed for requests from paybox servers - one of requirements is to return nothing. :-)
		
    }

	public function pagseguro_ipn()
	{
		Configure::write('debug', 0);
		$this->layout = 'ajax';

		$gateway = Configure::read('PaymentGateways.Pagseguro');
		$enabled = $gateway['active'];

		if(!$enabled)
		{
			$this->Session->setFlash('There is no handler for Pagseguro payments.');
			$this->redirect('/');
		}

		// check if user has plugin for paybox...
		$test = $this->requestAction(array('plugin' => 'pagseguro', 'controller' => 'pagseguro', 'action' => 'test'));
		if($test!=1)
		{
				$this->Session->setFlash('There is no handler for pagseguro payments.');
				$this->redirect('/');
		}

		$verified = $this->requestAction(array('plugin' => 'pagseguro', 'controller' => 'pagseguro', 'action' => 'verify'));

		if($verified)
		{
			$this->Session->setFlash(__('The payment has been made. Thank you.', true), 'default', array('class' => 'success'));
			$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
		}
		else
		{
			exit();
				$this->Session->setFlash(__('Error occured durning payment process.', true), 'default', array('class' => 'warning'));
				//Not in MVC paradigm, but $this->render() sometimes breaks authorize.net callback
				echo '<script>window.location=\''.Configure::read('App.url').'/users\';</script>'; flush(); exit;
		}
	}

	public function pagseguro($model = null, $id = null)
	{

		$gateway = Configure::read('PaymentGateways.Pagseguro');
		$enabled = $gateway['active'];

		if(!$enabled)
		{
			$this->Session->setFlash('There is no handler for Pagseguro payments.');
			$this->redirect('/');
		}

		// check if user has plugin for paybox...
		$test = $this->requestAction(array('plugin' => 'pagseguro', 'controller' => 'pagseguro', 'action' => 'test'));
		if($test!=1)
		{
				$this->Session->setFlash('There is no handler for pagseguro payments.');
				$this->redirect('/');
		}

		if(!empty($model))
		{
			$gateway['model'] = $model;
			$gateway['id'] = $id;

			switch($model)
			{
				case 'auction':
					$auction   = $this->_getAuction($id, $this->Auth->user('id'));
					$addresses = $this->_isAddressCompleted();
					$user      = $auction['Winner'];
					$amount    = number_format($auction['Auction']['total'], 0);

					$gateway['item_id']    = $id;
					$gateway['item_title'] = $auction['Product']['title'];
				break;

				case 'package':
					$package   = $this->_getPackage($id);
					$amount    = number_format($package['Package']['price'], 0);

					$gateway['item_id']    = $id;
					$gateway['item_title'] = $package['Package']['name'];
				break;

				default:
					$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
					$this->redirect('/');
				break;
			}

			// paybox needs amount without any commas/separator/spaces and with cents. For $10.00 it will be: 1000
//			$amount = str_replace(array(',', '.', ' '), '', $amount);
//			$amount 	 = number_format($amount, 2);

			$gateway['amount'] = $amount;

			$data = $this->requestAction(array('plugin' => 'pagseguro', 'controller' => 'pagseguro', 'action' => 'generate_data'), $gateway);
			$this->set('data', $data);
		}
		else
		{
			$this->Session->setFlash(__('Invalid payment gateway', true));
		}
	}

	public function paybox($model = null, $id = null)
	{
		$gateway = Configure::read('PaymentGateways.Paybox');
		$enabled = $gateway['active'];

		if(!$enabled)
		{
			$this->Session->setFlash('There is no handler for Paybox payments.');
			$this->redirect('/');
		}

		// check if user has plugin for paybox...
		$test = $this->requestAction(array('plugin' => 'paybox', 'controller' => 'paybox', 'action' => 'test'));
		if($test!=1)
		{
				$this->Session->setFlash('There is no handler for Paybox payments.');
				$this->redirect('/');
		}
		
		if(!empty($model))
		{
			$gateway['model'] = $model;
			$gateway['id'] = $id;
			
			switch($model)
			{
				case 'auction':
					$auction   = $this->_getAuction($id, $this->Auth->user('id'));
					$addresses = $this->_isAddressCompleted();
					$user      = $auction['Winner'];
					$amount    = number_format($auction['Auction']['total'], 0);

					$gateway['item_id']    = $id;
					$gateway['item_title'] = $auction['Product']['title'];
				break;

				case 'package':
					$package   = $this->_getPackage($id);
					$amount    = number_format($package['Package']['price'], 0);

					$gateway['item_id']    = $id;
					$gateway['item_title'] = $package['Package']['name'];
				break;

				default:
					$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
					$this->redirect('/');
				break;
			}
			
			// paybox needs amount without any commas/separator/spaces and with cents. For $10.00 it will be: 1000
			$amount = str_replace(array(',', '.', ' '), '', $amount);
//			$amount 	 = number_format($amount, 2);
			
			$gateway['PBX_TOTAL'] = $amount;

			$data = $this->requestAction(array('plugin' => 'paybox', 'controller' => 'paybox', 'action' => 'generate_data'), $gateway);
			$this->set('data', $data);
		}
		else
		{
			$this->Session->setFlash(__('Invalid payment gateway', true));
		}
	}

	/**
	 * Authorize.net Payment Gateway
	 * http://www.authorize.net
	 */
	function authorizenet($model = null, $id = null){
		if(!empty($model)){
			App::import('Vendor', 'ideal'.DS.'ideal');

			// Get gateway information
			$gateway = Configure::read('PaymentGateways.AuthorizeNet');

			switch($model){
				case 'auction':
					$auction   = $this->_getAuction($id, $this->Auth->user('id'));
					$addresses = $this->_isAddressCompleted();
					$user      = $auction['Winner'];

					$amount 	 = number_format($auction['Auction']['total'], 2);
					$description = $auction['Product']['title'];
					$control     = 'auction#'.$auction['Auction']['id'].'#'.$this->Auth->user('id');
					break;

				case 'package':
					$package   = $this->_getPackage($id, $this->Auth->user('id'));

					$amount 	 = number_format($package['Package']['price'], 2);
					$description = $package['Package']['name'];
					$control     = 'package#'.$package['Package']['id'].'#'.$this->Auth->user('id');
					break;

				default:
					$this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
					$this->redirect('/');
			}

			$timestamp			= time();
			$sequence			= rand(1, 1000);
			$login      		= $gateway['login'];
			$key        		= $gateway['key'];
			$test               = $gateway['test'];
			$user_id			= $this->Auth->user('id');
			$x_relay_url 		= Configure::read('App.url').'/payment_gateways/authorizenet_ipn';
			$fingerprint 		= $this->_hmac($login . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $key);

			$this->set('timestamp', $timestamp);
			$this->set('sequence', $sequence);
			$this->set('login', $login);
			$this->set('key', $key);
			$this->set('test', $test);
			$this->set('amount', $amount);
			$this->set('description', $description);
			$this->set('control', $control);
			$this->set('x_relay_url', $x_relay_url);
			$this->set('fingerprint', $fingerprint);
			
			$user=$this->User->findById($this->Auth->user('id'));
			$this->set('user', $user);

		}else{
			$this->Session->setFlash(__('Invalid payment gateway', true));
			$this->redirect('/');
		}

	}

	function authorizenet_ipn(){
		Configure::write('debug', 0);
		$this->layout = 'ajax';

		echo 'Processing...'; flush(); //authorize.net must get an immediate response or it errors out
		
		if(!empty($_POST)){
			$login       = Configure::read('PaymentGateways.AuthorizeNet.login');
			$key         = Configure::read('PaymentGateways.AuthorizeNet.key');
			$amount      = $_POST['x_amount'];
			$timestamp   = $_POST['timestamp'];
			$sequence    = $_POST['sequence'];
			$response    = $_POST['x_response_code'];
			$fingerprint = $this->_hmac($login . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $key);
			
			if($fingerprint == $_POST['fingerprint']){
				if($response == 1){
					$control 	   = explode('#', $_POST['control']);

					$model         = !empty($control[0]) ? $control[0] : null;
					$id            = !empty($control[1]) ? $control[1] : null;
					$user_id       = !empty($control[2]) ? $control[2] : null;

					switch($model){
						case 'auction':
							$auction = $this->_getAuction($id, $user_id, false);

							// Change auction status
							$status = $this->_setAuctionStatus($id, 2);

							// Give 'em first winning bonus bid
							$this->_firstWin($user_id);
							
							eval(PluginManager::hook('controllers_paymentgateways_paid'));

							// Lets deduct the spent credits
							if(Configure::read('App.credit.active')){
								$this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
							}

							// Send notification email
							$this->_sendAuctionNotification($auction, $user_id);
							break;

						case 'package':
							$package = $this->_getPackage($id, $user_id);

							// Adding bids
							$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
							$credit      = $package['Package']['bids'];
							$this->_bids($user_id, $description, $credit, 0);

							// Updating account
							$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
							$bids  = $package['Package']['bids'];
							$price = $package['Package']['price'];

							// Add bonus if it's user first purchase
							$this->_checkFirstPurchase($user_id, $bids);

							$this->_account($user_id, $name, $bids, $price);

							// Checking referral bonus
							$this->_checkReferral($user_id);

							// Check and increase user reward points
							$this->_checkRewardPoints($id, $user_id);

							// Send notification email
							$this->_sendPackageNotification($package, $user_id);
							break;
					}
				}

				$message = $_POST['x_response_reason_text'];
				$next_url = Configure::read('App.url').'/accounts/index';

				$this->set('message', $message);
				$this->set('next_url', $next_url);
			}else{
				$message = 'Got authorize.net incorrect hash fingerprint. Probably fraud.';
				$this->log($message, 'payment');
				$this->set('message', $message);
			}
		}else{
			$message = 'Got authorize.net ipn request but no post data. Probably fraud.';
			$this->log($message, 'payment');
			$this->set('message', $message);
		}
		
		//Not in MVC paradigm, but $this->render() sometimes breaks authorize.net callback
		echo '<script>window.location=\''.Configure::read('App.url').'/users\';</script>'; flush(); exit;
	}

	/**
	 * Plimus payment gateway
	 * http://www.plimus.com
	 */
	function plimus_ipn($model = 'package'){
		$this->layout = 'ajax';

		$user_id 		 = !empty($_POST['user_id']) ? $_POST['user_id'] : null;
		$id      		 = !empty($_POST['package_id']) ? $_POST['package_id'] : null;
		$transactionType = !empty($_POST['transactionType']) ? strtoupper($_POST['transactionType']) : null;

		if(empty($id)) {
			// if $id is empty then lets see if the auction_id is there
			$id = !empty($_POST['auction_id']) ? $_POST['auction_id'] : null;
		}

		if(!empty($user_id) && !empty($id) && !empty($transactionType) && $transactionType == 'CHARGE') {
			switch($model){
				case 'auction':
					$auction = $this->_getAuction($id, $user_id, false);

					// Change auction status
					$status = $this->_setAuctionStatus($id, 2);

					// Check the first winners bonus
					$this->_firstWin($user_id, $id);

					eval(PluginManager::hook('controllers_paymentgateways_paid'));

					// Lets deduct the spent credits
					if(Configure::read('App.credit.active')){
						$this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
					}

                    // Send notification email
                    $this->_sendAuctionNotification($auction, $user_id);
					break;

				case 'package':
					$package = $this->_getPackage($id, $user_id);

					// Adding bids
					$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
					$credit      = $package['Package']['bids'];
					$this->_bids($user_id, $description, $credit, 0);

					// Updating account
					$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
					$bids  = $package['Package']['bids'];
					$price = $package['Package']['price'];

					// Add bonus if it's user first purchase
					$this->_checkFirstPurchase($user_id, $bids);

					$this->_account($user_id, $name, $bids, $price);

					// Checking referral bonus
					$this->_checkReferral($user_id);

					// Check and increase user reward points
					$this->_checkRewardPoints($id, $user_id);

                    // Send notification email
                    $this->_sendPackageNotification($package, $user_id);
					break;

				default:
					$message = 'Plimus IPN : Invalid model';
					$this->log($message, 'payment');
			}
		}else{
			$message = sprintf('Plimus IPN : There is no id for %s or user id in posted data. I need both of them!', $model);
			$this->log($message, 'payment');
		}
	}

	/**
     * DIBS payment gateway
     * http://www.dibs.dk
     */
    function dibs($model = null, $id = null){
		$gateway = Configure::read('PaymentGateways.DIBS');

		if(!empty($model)){
			$data['merchant']	 = $gateway['merchant'];
			$data['currency'] 	 = $gateway['currency'];
			$data['lang'] 	  	 = $gateway['lang'];
			$data['test']     	 = $gateway['test'];
			$data['callbackurl'] = Configure::read('App.url') . '/payment_gateways/dibs_callback';
			$data['orderid']	 = $model . $id;

			// Calculating security
			$security 			  = Security::hash($this->Auth->user('id') + $id);
			$data['item_control'] = $model . '#' . $id . '#' . $this->Auth->user('id') . '#' . $security;

			switch($model){
                case 'auction':
                    $auction   = $this->_getAuction($id, $this->Auth->user('id'));
                    $addresses = $this->_isAddressCompleted();
                    $user      = $auction['Winner'];

					$data['amount']    = number_format($auction['Auction']['total'], 2);
					$data['name']  	   = $this->Auth->user('first_name').' '.$this->Auth->user('last_name');
					$data['address']   = $addresses['Billing']['address_1'].' '.$addresses['Billing']['address_2'];
					$data['accepturl'] = Configure::read('App.url') . '/payment_gateways/return/auction';
					$data['cancelurl'] = Configure::read('App.url') . '/payment_gateways/cancel/auction';

					$data['item_id']    = $id;
					$data['item_title'] = $auction['Product']['title'];

                    break;
                case 'package':
                    $package   = $this->_getPackage($id);

					$data['amount']    = number_format($package['Package']['price'], 2);
					$data['name']      = $this->Auth->user('first_name').' '.$this->Auth->user('last_name');
					$data['accepturl'] = Configure::read('App.url') . '/payment_gateways/return/package';
					$data['cancelurl'] = Configure::read('App.url') . '/payment_gateways/cancel/package';

					$data['item_id']    = $id;
					$data['item_title'] = $package['Package']['name'];

                    break;
                default:
                    $this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
                    $this->redirect('/');
            }

			$this->set('data', $data);
		}else{
			$this->Session->setFlash(__('Invalid payment gateway', true));
		}
    }

	function dibs_callback(){
		$gateway = Configure::read('PaymentGateways.DIBS');
		$data = $_POST;

		if(!empty($data)){
			$control = explode('#', $data['ordline1-3']);

			$model    = !empty($control[0]) ? $control[0] : null;
			$id       = !empty($control[1]) ? $control[1] : null;
			$user_id  = !empty($control[2]) ? $control[2] : null;
			$security = !empty($control[2]) ? $control[2] : null;

			$security_check = Security::hash($user_id + $id);
			if($security_check == $security){
				switch($model){
					case 'auction':
						$auction = $this->_getAuction($id, $user_id, false);

						// Change auction status
						$status = $this->_setAuctionStatus($id, 2);

						// Check the first winners bonus
						$this->_firstWin($user_id, $id);

						eval(PluginManager::hook('controllers_paymentgateways_paid'));

						// Lets deduct the spent credits
						if(Configure::read('App.credit.active')){
							$this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
						}

                        // Send notification email
                        $this->_sendAuctionNotification($auction, $user_id);
						break;

					case 'package':
						$package = $this->_getPackage($id, $user_id);

						// Adding bids
						$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
						$credit      = $package['Package']['bids'];
						$this->_bids($user_id, $description, $credit, 0);

						// Updating account
						$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
						$bids  = $package['Package']['bids'];
						$price = $package['Package']['price'];

						// Add bonus if it's user first purchase
						$this->_checkFirstPurchase($user_id, $bids);

						$this->_account($user_id, $name, $bids, $price);

						// Checking referral bonus
						$this->_checkReferral($user_id);

						// Check and increase user reward points
						$this->_checkRewardPoints($id, $user_id);

                        // Send notification email
                        $this->_sendPackageNotification($package, $user_id);
						break;
				}
			}else{
				$this->log('DIBS Payment Gateway: callback security check failed', 'payment');
			}
		}else{
			$this->log('DIBS Payment Gateway: empty callback posted data', 'payment');
		}
    }

	/**
	 * Ogone payment gateway
	 * http://www.ogone.com
	 */
	function ogone($model = null, $id = null){

	}

	function ogone_ipne(){

	}

    /**
     * Payment Network Gateway
     * http://www.payment-network.com
     */
    function payment_network($model = null, $id = null){
        // Get gateway information
        $gateway = Configure::read('PaymentGateways.PaymentNetwork');

        switch($model){
            case 'auction':
                $auction   = $this->_getAuction($id, $this->Auth->user('id'));
                $addresses = $this->_isAddressCompleted();
                $user      = $auction['Winner'];

                $amount 	 = $auction['Auction']['total'];
                $description = $auction['Product']['title'];

                // Writing cache for type
                $control = 'auction#'.$auction['Auction']['id'].'#'.$this->Auth->user('id');

                break;
            case 'package':
                $package   = $this->_getPackage($id);

                $amount 	 = $package['Package']['price'];
                $description = $package['Package']['name'];

                // Writing cache for type
                $control = 'package#'.$package['Package']['id'].'#'.$this->Auth->user('id');

                break;
            default:
                $this->Session->setFlash(sprintf(__('There is no handler for %s in this payment gateway.', true), $model));
                $this->redirect('/');
        }

        $hash_array = array($gateway['user_id'], $gateway['project_id'], '', '', '', '', $amount, $gateway['currency_id'],
                            $description, '', $control, '', '', '', '', '', $gateway['project_password']);
        $hash_string = implode('|', $hash_array);
        $hash = sha1($hash_string);

        $this->set('gateway', $gateway);
        $this->set('amount', $amount);
        $this->set('description', $description);
        $this->set('control', $control);
        $this->set('hash', $hash);
    }

    function payment_network_ipn(){
        // Get gateway information
        $gateway = Configure::read('PaymentGateways.PaymentNetwork');

        Configure::write('debug', 0);
		$this->layout = 'ajax';

		if(!empty($_POST)){
			$data    = $_POST;
			$control = explode('#', $_POST['user_variable_0']);
			$model   = !empty($control[0]) ? $control[0] : null;
			$id      = !empty($control[1]) ? $control[1] : null;
			$user_id = !empty($control[2]) ? $control[2] : null;
			
			switch($model){
				case 'auction':
				$auction = $this->_getAuction($id, $user_id, false);
				
				// Change auction status
				$status = $this->_setAuctionStatus($id, 2);
				
				// Check the first winners bonus
				$this->_firstWin($user_id, $id);
				
				eval(PluginManager::hook('controllers_paymentgateways_paid'));
				
				// Lets deduct the spent credits
				if(Configure::read('App.credit.active')){
				$this->_credit($id, 0, $auction['Credit']['debit'], $user_id);
				}
				break;
				
				case 'package':
				$package = $this->_getPackage($id, $user_id);
				
				// Adding bids
				$description = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
				$credit      = $package['Package']['bids'];
				$this->_bids($user_id, $description, $credit, 0);
				
				// Updating account
				$name  = __('Bids purchased - package name:', true).' '.$package['Package']['name'];
				$bids  = $package['Package']['bids'];
				$price = $package['Package']['price'];
				
				// Add bonus if it's user first purchase
				$this->_checkFirstPurchase($user_id, $bids);
				
				$this->_account($user_id, $name, $bids, $price);
				
				// Checking referral bonus
				$this->_checkReferral($user_id);
				
				// Check and increase user reward points
				$this->_checkRewardPoints($id, $user_id);
				break;
			}
		}else{
			$message = 'Got payment network ipn request but no post data. Probably fraud.';
			$this->log($message, 'payment');
			$this->set('message', $message);
		}
    }
}
?>
