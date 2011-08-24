<?php
	class User extends AppModel {

		var $name = 'User';

		var $actsAs = array('Containable');

		var $hasOne = array('Point');

		var $hasMany = array(
			'Address',
			'Bid' => array(
				'className'  => 'Bid',
				'limit' => 10
			),
			'Bidbutler',
			'Account',
			'Affiliate',
			'AffiliateCode',
			'Referral',
			'Referred' => array(
				'className'  => 'Referral',
				'foreignKey' => 'referrer_id'
			),

			'Auction' => array(
				'className'  => 'Auction',
				'foreignKey' => 'winner_id',
				'limit' => 10
			),

			'Credit' => array(
				'className'  => 'Credit',
				'foreignKey' => 'user_id',
				'limit' => 10
			),

			'Watchlist' => array(
				'className'  => 'Watchlist',
				'foreignKey' => 'user_id',
				'limit' => 10
			),

			'Reminder'
		);

		var $belongsTo = array('Source');

		/**
		 * Constructor, redefine to use __() in validate message
		 */
		function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'username' => array(
					'checkUnique' => array(
						'rule' => array('checkUnique', 'username'),
						'message' => __('The username is already taken.', true)
					),
					'alphaNumeric' => array(
						'rule' => 'alphaNumeric',
						'message' => __('The username can contain letters and numbers only.', true)
					),
					'between' => array(
		        		'rule' => array('between', 3, 16),
		        		'message' => __('Username must be between 3 and 16 characters long.', true)
		        	),
					'minlength' => array(
						'rule' => array('minLength', '1'),
						'message' => __('A username is required.', true)
					)
				),

				'old_password' => array(
					'oldPass' => array(
		        		'rule' => array('oldPass'),
		           		'message' => 'The old password you entered is incorrect.'
		    		),
					'minlength' => array(
						'rule' => array('minLength', '1'),
						'message' => 'Please enter in your old password.'
					)
				),

				'before_password' => array(
					'between' => array(
						'rule' => array('between', 6, 20),
						'message' => __('Password must be between 6 and 20 characters long.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Password is a required field.', true)
					)
				),

				'retype_password' => array(
					'matchFields' => array(
						'rule' => array('matchFields', 'before_password'),
						'message' => __('Password and Retype Password do not match.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Retype Password is a required field.', true)
					)
				),

				'first_name' => array(
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('First name is required.', true)
					),
					'alphaNumeric' => array(
						'rule' => '/.+/',
						'message' => __('First name may only contain alphabets and numbers only', true)
					)
				),

				'last_name' => array(
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Last name is required.', true)
					),
					'alphaNumeric' => array(
						'rule' => '/.+/',
						'message' => __('Last name may only contain alphabets and numbers only', true)
					)
				),

				'mobile' => array(
					'rule'=> 'numeric',
					'message' => __('Mobile can be a number only.', true),
					'allowEmpty' => true
				),

				'email' => array(
					'checkUnique' => array(
						'rule' => array('checkUnique', 'email'),
						'message' => __('The email was already used by another user.', true)
					),
					'email' => array(
						'rule' => 'email',
						'message' => __('The email address you entered is not valid.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Email address is required.', true)
					)
				),
				'confirm_email' => array(
					'matchFields' => array(
						'rule' => array('matchFields', 'email'),
						'message' => __('Email and confirm email do not match.', true)
					),
					'email' => array(
						'rule' => 'email',
						'message' => __('The confirm email you entered is not valid.', true)
					),
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Confirm email is required.', true)
					)
				),
				'referrer' => array(
					'referrer' => array(
						'rule' => array('referrer', 'referrer'),
						'message' => __('The referrer username or email address you entered does not exist.', true)
					)
				),
				'affiliate' => array(
					'affiliate' => array(
						'rule' => array('affiliate', 'affiliate'),
						'message' => __('The referrer code you entered does not exist.', true)
					)
				),
				/*'source_id' => array(
					'rule' => array('sourceRequired'),
					'message' => __('Source is required.', true)
				),
				'source_extra' => array(
					'rule' => array('sourceNeedExtra'),
					'message' => __('Site name is required.', true)
				),*/
				'terms' => array(
					'minLength' => array(
						'rule' => array('minLength', 1),
						'message' => __('Please accept the terms and conditions.', true)
					)
				),
			);
		}
		function beforeSave() {
			App::import('Sanitize');
			$this->data = Sanitize::clean($this->data);
			return true;
		}

		/**
		 * Function to reset user password. User will get a new password by email.
		 *
		 * @param array $data Data containing user information which will be verified
		 * @return mixed User and email parameter array if success, false otherwise
		 */
		function reset($data, $newPasswordLength = 8){
			$conditions = array();

			if(is_array($data)){
				if(!empty($data['User'])){
					// Loop through given data array and put it as condition to check
					foreach($data['User'] as $key => $datum){
						if($this->hasField($key)){
							$conditions[$key] = $datum;
						}
					}

					// Find the user
					$user = $this->find('first', array('conditions' => $conditions));
					if(!empty($user)){
						// Formating the data for email sending
						// Put the reset link inside the user array
						$user['User']['before_password'] = substr(sha1(uniqid(rand(), true)), 0, $newPasswordLength);
						$user['to'] 				     = $user['User']['email'];
						$user['subject'] 			     = sprintf(__('Account Reset - %s', true), $this->appConfigurations['name']);
						$user['template'] 			     = 'users/reset';

						// Set the password
						$user['User']['password'] = Security::hash(Configure::read('Security.salt').$user['User']['before_password']);

						// Save the user info
						$this->save($user, false);

						return $user;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}


		/**
		 * Function to register a user. User will get an activation link by email.
		 *
		 * @param array $data An array which containing user information
		 * @return mixed User and email parameter array if success, false otherwise
		 */
		function register($data, $admin = false, $id = null) {
			if(is_array($data)){
				if(!empty($data['User'])){

					$data['User']['key'] = Security::hash(uniqid(rand(), true));
					$data['User']['ip']  = $_SERVER['REMOTE_ADDR'];

					if(!empty($data['User']['before_password'])) {
						$data['User']['password'] = Security::hash(Configure::read('Security.salt').$data['User']['before_password']);
					}

					if(empty($data['User']['source_id'])) {
						$data['User']['source_id'] = 0;
					}

					// Saving user
					if(!empty($id)) {
						$data['User']['id'] = $id;
					} else {
						$this->create();
					}

					if($this->save($data)) {
						// Get the last inserted user
						$user = $this->read(null, $this->getLastInsertID());

						// now lets check if there was a referred
						if(!empty($data['User']['referrer'])) {
							$referrer = $this->find('first', array('conditions' => array('or' => array('User.username' => $data['User']['referrer'], 'User.email' => $data['User']['referrer']))));
							$referralData['Referral']['user_id'] = $user['User']['id'];
							$referralData['Referral']['referrer_id'] = $referrer['User']['id'];
							$this->Referral->create();
							$this->Referral->save($referralData);
						}

						// and also check for a affiliate code
						if(!empty($data['User']['affiliate'])){
							$affiliateCode = $this->AffiliateCode->find('first', array('conditions' => array('code' => $data['User']['affiliate'])));
							$affiliate['Affiliate']['user_id'] 		= $user['User']['id'];
							$affiliate['Affiliate']['affiliate_id'] = $affiliateCode['AffiliateCode']['user_id'];
							$affiliate['Affiliate']['credit'] 		= $affiliateCode['AffiliateCode']['credit'];
							$affiliate['Affiliate']['description'] 	= __('Referral Made', true);
							$this->Affiliate->create();
							$this->Affiliate->save($affiliate);
						}

						// Formating the data for email sending
						// Put the reset link inside the user array
						$user['User']['username'] 		= $data['User']['username'];
						$user['User']['password'] 		= $data['User']['before_password'];
						$user['User']['activate_link'] 	= $this->appConfigurations['url'] . '/users/activate/' . $user['User']['key'];
						$user['to'] 				  	= $user['User']['email'];
						if($admin == true) {
							$user['subject'] 			= sprintf(__('Account Created by Admin - %s', true), $this->appConfigurations['name']);
						} else {
							$user['subject'] 			= sprintf(__('Account Activation - %s', true), $this->appConfigurations['name']);
						}
						$user['template'] 			   	= 'users/activate';

						return $user;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}


		/**
		 * Function to activate a user
		 *
		 * @param string $key Forty characters long key
		 * @return array User array who just been activated
		 */
		function activate($key){
			$user = $this->find('first', array('conditions' => array('User.key' => $key, 'User.active' => 0),'recursive'=>-1));
			if(!empty($user)){
				$this->save(array('User'=>array(	'id'=>$user['User']['id'],
									'key'=>'',
									'active'=>1)));
				
				return $user;
			} else {
				return false;
			}
		}

		/**
		 * Function to check if the referrer exists
		 *
		 * @param array $data The users data
		 * @return booleen true is it's right, false otherwise
		 */
		function referrer($data) {
			if(!empty($data['referrer'])) {
				$user = $this->find('count', array('conditions' => array('or' => array('User.username' => $data['referrer'], 'User.email' => $data['referrer']))));
				if($user > 0) {
					return 1 ;
				} else {
					return 0;
				}
			} else {
				return 1;
			}
		}

		/**
		 * Function to check the users old password is correct
		 *
		 * @param array $data The users data
		 * @return booleen true is it's right, false otherwise
		 */
		function oldPass($data) {
			if(!empty($data['old_password'])) {
				$valid = false;
				$userData = $this->read();
				$oldPass = Security::hash(Configure::read('Security.salt') . $data['old_password']);
				if ($userData['User']['password'] == $oldPass) {
					$valid = true;
				}
				return $valid;
			} else {
				return true;
			}
		}
		
		/**
		 * Function to check if the affiliate code exists
		 *
		 * @param array $data The users data
		 * @return booleen true is it's right, false otherwise
		 */
		function affiliate($data) {
			if(!empty($data['affiliate'])) {
				$affiliateCode = $this->AffiliateCode->find('count', array('conditions' => array('code' => $data['affiliate'])));
				if($affiliateCode > 0) {
					return 1 ;
				} else {
					return 0;
				}
			} else {
				return 1;
			}
		}


		function afterFind($results, $primary = false){
			// Parent method redefined
			$results = parent::afterFind($results, $primary);
		
			if(!empty($results)){
				// This for find('all')
				if(!empty($results[0]['User'])){
					// Loop over find result and convert the price with rate
					foreach($results as $key => $result){
						if(empty($result['User']['email']) && !empty($result['User']['mobile'])){
							$results[$key]['User']['username'] = substr($result['User']['username'], 0, (strlen($result['User']['username']) - 4)) . 'xxxx';
						}
					}
		
				// This for find('first')
				}elseif(!empty($results['Package'])){
					if(empty($results['User']['email']) && !empty($results['User']['mobile'])){
						$results['User']['username'] = substr($results['User']['username'], 0, (strlen($results['User']['username']) - 4)) . 'xxxx';
					}
				}
			}
		
			// Return back the results
			return $results;
		}

		/**
		* This function generates random password for user
		*
		* @param int $length How long the new password will be
		* @param string $random_string The string to be used when generate the password
		* @return string New generated password
		*/
		function generateRandomPassword($length = 8, $randomString = null) {
			if(empty($randomString)){
			    $randomString = 'pqowieurytlaksjdhfgmznxbcv1029384756';
			}
			$newPassword = '';
			
			for($i=0;$i<$length;$i++){
			    $newPassword .= substr($randomString, mt_rand(0, strlen($randomString)-1), 1);
			}
			
			return $newPassword;
		}
		
		//***** validation rules		
		function sourceRequired($data) {
			if(!empty($this->appConfigurations['sourceRequired'])) {
				if(empty($data['source_id'])) {
					return false;
				} else {
					return true;
				}
			} else {
				return true;
			}
		}
		
		function sourceNeedExtra($data){
			if(!empty($this->data['User']['source_id'])){
				$source = $this->Source->findById($this->data['User']['source_id']);

				if(!empty($source)){
					if(empty($this->data['User']['source_extra']) && $source['Source']['extra'] == 1){
						return false;
					}
				}
			}

			return true;
		}
	}
	
	
?>
