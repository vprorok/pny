<?php
 /********************************************************************************************
 *********************************************************************************************
       .                                               .                
       |                                              _|_   o           
  .,-. |--. .,-. .,-.  .-. .--. .--. .  . .-.  .  . .-.|    .  .-. .--. 
  |   )|  | |   )|   )(.-' |  | |  | |  |(   ) |  |(   |    | (   )|  | 
  |`-' '  `-|`-' |`-'  `--''  `-'  `-`--| `-'`-`--`-`-'`-'-' `-`-' '  `-
  |         |    |                      ;                               
  '         '    '                   `-'                      
 * phpPennyAuction version 2.4.2 - (C) 2011 Scriptmatix Ltd. All Rights Reserved.
 * Last updated: 12-Mar-2011 /  http://www.phppennyauction.com 
 *
 * DISTRIBUTION DENIED WITHOUT EXPRESS PERMISSION. 
 * We will prosecute all offenders to the maximum possible possible extend allowed under law.
 * File must retain this copyright notice. Use of the software signifies acceptance of the
 * Terms of Use and License Agreement.
 * 
 * For all support-related inquiries, please refer to the Support Center at
 * https://members.phppennyauction.com. You will find a  Knowledge Base (KB), Wiki,
 * Member Forums, Video Tutorials and much more available to all members at no cost.
 *
 *********************************************************************************************
 *********************************************************************************************/
 
 
 
 
 // IMPORTANT
 // You installed using the Automated Installer method. The fields below have been populated 
 // already and we only recommend editing below this line if you are sure what you are doing!
 
 
 

 // *******************************************************************************************

 
 
 
 
 // 
 // MySQL settings
 //
 // If using the auto-install script, these will be filled out already and you don't need to
 // touch if your site is working! If you are installing manually, you will need to
 // obtain these settings from your host. You don't need to enter a 'prefix' normally,
 // although it varies from host to host. If you require assistance with setting up a MySQL
 // database then you should ideally contact your hosting provider. You will need to adjust the 
 // host, login, password and database fields only in most cases, if installing manually. 
    $config = array(
        'Database' => array(
            'driver'     => 'mysql',
            'persistent' => false,
            'host'       => '%db_host%',
            'login'      => '%db_user%',
            'password'   => '%db_pass%',
            'database'   => '%db_name%', 
            'prefix'     => '',
            'encoding'	 => '%site_encoding%'
        ),
		
		
		
		
//  *******************************************************************************************			
		
		
		
//
// Main website settings
// These fields should be auto-populated by the installation script. If you are installing
// manually, please consult with the Install Guide and other documentation in the phpPennyAuction
// Support Center for assistance. Generally, you don't need to change anything here if you used
// the self-installation script.

        'App' => array(
		'license'                => '%license_number%', // you don't need to change this, it's automatically added by the Installer
		'encoding'               => '%site_encoding%', // the default encoding/charset, detected by the installer
		'baseUrl'                => '',
		'base'                   => '',
		'dir'		    		 => '',
		'webroot'				 => 'webroot',
		'name'                   => '%site_name%', // your website title
		'url'                    => 'http://%site_url%',
		'ref_url'                => 'http://%site_url%',
		'nml_url'                => 'http://%site_url%',
		'serverName'             => '',
		'timezone'               => '%time_zone%',  // check the Time Zone article in the Knowledge Base for the correct 
		'language'               => 'eng', // Three-letter only, eng = English
		'email'                  => '%admin_email%',
		'currency'               => '%site_currency%', // Three-letter currency code, i.e. GBP, USD, CAD, for more information see the Knowledge base article on custom currencies.
		
		
		
		
//  *******************************************************************************************			
		
		
		
//		
// Template (theme)
// If you have purchased templates/themes alongside your order, please
// refer to the documentation available inside the download. The default template
// is already set up below, you don't need to adjust this if this is the one you
// want to use. You can switch this to 'template1' to use the other default
// template that we include with EVERY purchase of the software. 

		'theme'                  => 'template_frog',
		
		
		
 // *******************************************************************************************
		
		
		
		
//		
// Remainder of website settings
// These fields should be adjusted to customize your website, but please be sure
// to make a backup of this file BEFORE editing below. phpPennyAuction Support cannot
// be held responsible if you mess up your website by not reading the relevant
// documentation beforehand. Please refer to our Disclaimer for more information.
		
		'noCents'        		 => true, // false = show prices in European format (,01c), true = show prices in American format (.01p)
		'pageLimit'              => 25, // number of pages that can be viewed in very quick succession to prevent spam harvesting, default is '25'
		'adminPageLimit'         => 100, // number of pages that the admin user(s) can view in a session before you need to sign back in. To prevent bots/hacks.
		'bidHistoryLimit'        => 10, // default number of bids to show in the 'bid box' when viewing an auction
		'remember_me'            => '+30 days', // default cookie session timeout
		'auctionUpdateFrequency' => 1, // how often auction status updates. leave at '1' unless you are sure what you're doing!
		'timeSyncFrequency'      => 9, // leave at '9' unless you are sure what you're doing!
		'memoryLimit'      	  	 => '256M', //needs to be mirrored in your php.ini file - advanced users ONLY!
		'autobidTime'      		 => 10, 
		'gateway' 	       		 => true, //leave this enabled or it will mess up your website! :)
		'demoMode' 	       		 => false, // 'admin mode' - please see Knowledge Base (KB) documentation on this.
		'autobids'               => false, //use autobidding/test bidders on your website?
		'smartAutobids'          => true, // makes the autobidders act in more realistic way, recommended to enable if autobids are enabled
		'bidIncrements' 		 => 'dynamic', // options are: single, dynamic, product
		'bidButlerType'      	 => 'simple', // options are: simple, advanced
		'bidButlerDeploy'		 => 'single', // options are: single, grouped.  Grouped only works when bid increments not dynamic
		// 'bidButlerRapidDeploy'	 => '10', // advanced users only, dsiabled by default
		'homeEndingLimit'     	 => 10, //the number of auctions to show in the 'ending soon' part of the homwepage
		'homeFeaturedLimit'      => 5, // the number of auctions to show in the 'Featured' top part of the homepage
		'homeFeaturedAuction'    => false, //enable 'featured' auctions to show on your homepage?
		'newsletterSelected'     => true, // default subcription mode for the newsletter when customers sign up

		'sourceRequired'	     => true, // force the source/referrer to be a mandatory option when users register?
		'phoneRequired'	 		 => false, // force the phone number field to be entered, when your customers register?
		'taxNumberRequired' 	 => true, // if included in your template, force the customer to enter a tax ID or VAT #?
		'endedLimit'			 => 30, // the number of auctions to show in the 'Ended' auctions page? set to 0 for unlimited
		'flashMessage'           => false,
		'simpleBids'			 => false,
		'rewardsPoint'           => false,
		'coupons'                => false, // enable the coupons module?
		'affiliates'			 => false, // enable the affiliates module?

		'hiddenReserve'			 => true, // enable the hidden reserve module?
		'emailWinner'			 => true, // send an email to the winner of the auction?
		'timeFormat'             => 24, // can be 12 or 24

//		
// MadBid Style Bidding module
		'maxCounterTime' 		 => 0, // the countdown will not go past this point once this time is met. Set to '0' to disable!
		'ipBlock'				 => 0, // set to 1 to enable IP address blocking for multiple registrations from same IP address
		'delayedStart'			 => false,
		'cronTime' 	        	 => 1, // the cron job execution time, in minutes. Set to 20 if you can only run crons every 20th minute, for example. Default is 1 (minute)
		'bidButlerSleep'		 => 2, // higher for decreased server load, lower for more accurate bid butlers, default 4
	
	
	
//
// Deprecated features
// These features require additional work to use and are here for legacy purposes only, or are 
// very advanced. We do not recommend using them unless required as they may have bugs or have
// other undesirable effects.

		'forum'                  => false, // use the bundled Forum software? please contact support for assistance with this.
		'affiliates'			 => false, // enable the affiliates module?
		'daemons_log'			 => false,	//turn on only for cronjob debugging, log files can get HUGE, open a ticket if unsure!
		'wwwRedirect'			 => false, // if enabled, forces the 'www.' in all URLs, cannot be used if installing to a sub-domain
		'sslUrl'				 => '', // add your https:// secure URL here, and in the ref_url setting above, if you want to use SSL secure pages.
		'registerOff'			 => false,	//disable site registration (not recommended)	
		
		
		
		
 // *******************************************************************************************	
		
		
		

// 
// Buy Now Module settings
// Please refer to documentation on this in our Knowledge Base (KB), or seethe basic notes below

		'buyNow' => array(
			'enabled'=>true,		// if false, buy-it-now feature is not available
			'split'=>true,			// if true when buy-it-now used, new auction is created and existing continues
			'bid_discount'=>true,		// give a discount for every bid that's been placed 
			'bid_price'=>0.75,		// how much each bid costs (used to calc discount)
			'before_closed'=>true,		// allow b-i-n before the auction closes
			'after_closed'=>true,		// allow b-i-n after the auction closes, 'SPLIT' *MUST* be set to true
			'hours_after_closed'=>1,	// can b-i-n up to X hours after listing closes
			'must_bid_before'=>true,	// can only b-i-n after closed if they bid before close
		),
 

		
		
 // *******************************************************************************************	
		
		
		

//
// Custom Image Array
// If you want to adjust the size of the product images

		'Image' => array(
		'thumb_width'  => 100,
		'thumb_height' => 100,
		'max_width'    => 340,
		'max_height'   => 230
		),
		
		
		
 // *******************************************************************************************	
		
		
		
		
//		
// Date of Birth array
// Adjust the Age requirements for your website
// to prevent minors from signing up and so forth	

		'Dob' => array(
			'year_min' => date('Y') - 100,
			'year_max' => date('Y') - 17
		),
		
		
		
// Credits
// Give your users a number of credit(s) off of
// their total purchase price.

		'credits' => array(
			'active'	=> false,
			'value'     => 1,
			'expiry'    => 45,
		),
		
		
		
 // *******************************************************************************************
 
		
		

//
// Winning Limits
// Disabled by default

		'limits' => array(
			'active' => false,
				'limit' => 8,
				'expiry' => 28, // the number of days
		),
		
	
	
		
 // *******************************************************************************************	
		
		
		
		
//
// Cleaner 
// Clears out the previously ended auctions from your website and keeps
// everything running smoothly. Please only adjust if you are sure what you
// are doing!

		'cleaner' => array(
			'active' => true,
			'clear' => 30,     // the number of days the auctions should remain
			'clear_all' => 35, // the number of days until ALL auctions are deleted
		),
		
		
		
		
 // *******************************************************************************************	
		
			
		
//
// Multi Versions
// Use the software with varying languages, currencies, timezones
// and much more. See the KB for the full introduction to this feature.

		'multiVersions' => array(
			'domain.com' => array(
			'name'                   => 'Telebid',
	        'url'                    => 'http://telebid',
	        'timezone'               => 'Europe/London',
			'language'               => 'en',
			'currency'               => 'GBP',
			'noCents'        	 	 => true,
			'theme'              	 => ''
			)
		),
		
        ),
		
		
		
		
 // *******************************************************************************************	
		
		
		

//
// Payment gateways
// Refer to the Knowledge Base (KB) for assistance with these. PayPal is automatically
// set up if you are using the self-installer using your default email
// address. If you want to use other payment gateways, please refer to the
// Knowledge Base (KB) for guidance and notes, or contact Support.

        'Paypal' => array(
            'url'   	 => 'https://www.paypal.com/cgi-bin/webscr',
            'email' 	 => '%admin_email%',
            'lc'    	 => 'USD',
        ),

        'PaypalProUk' => array(
            'username' 		=> '',
            'password' 		=> '',
            'signature' 	=> '',
            'endpoint' 		=> 'https://api-3t.paypal.com/nvp',
            'use_proxy'	 	=> false,
            'proxy_host' 	=> '127.0.0.1',
            'proxy_port' 	=> 80,
            'version' 		=> '52.0',
            'ssl_url'		=> 'https://www.domain.com'
        ),

        'ePayment' => array(
            'merchant'  => '', // merchant identifier
            'key'       => '', // key signature
            'testorder' => 'TRUE', // empty this out to get live
            'language'  => 'en' // can be = en, ro, fr, it, es de
        ),



 // *******************************************************************************************




// 
// Email settings
// These are autopopulated, by the Installer script. If you are
// having difficulty with emails and they are not being sent/received
// please de-comment out the section immediately below and try the
// third-party option below (currently commented out).

        'Email' => array( 
            'IsSMTP'     => false,
            'IsHTML'     => true,
            'SMTPAuth'   => false,
            'CharSet'   => 'UTF-8',
            'Host'       => 'localhost',
            'Port'       => 25,
            'WordWrap'   => 50,
            'From'       => 'no-reply@%site_url%', // IMPORTANT: make sure there's no "www." here
            'FromName'   => '%site_name%',
            'ReplyTo'    => '%admin_email%'
            
        ),
   /*
        'Email' => array(
            'IsSMTP'     => true,
            'IsHTML'     => true,
            'SMTPAuth'   => true,
            'SMTPSecure' => 'ssl',
            'Host'       => 'smtp.gmail.com',
            'Port'       => 465,
            'Username'   => 'phppennyauctiondemo@gmail.com',
            'Password'   => 'sH7!2lcmJznx8dhAv',
            'WordWrap'   => 50,
            'From'       => 'phppennyauctiondemo@gmail.com',
            'FromName'   => '%site_name%',
            'ReplyTo'    => 'info@%site_url%'
        ),*/





 // *******************************************************************************************




//
// Disable the website cache - NOT recommended
// Be very careful here!
        'Cache' => array(
            'disable' => false,
            'check' => false,
            'time' => '' // relative time such as +1 day, +2 months, +3 minutes
        ),




 // *******************************************************************************************




//
// Postcode/Zip validation rules
// If you want to allow RegEx expressions such as only 5 digit zip codes, if you
// want to block non-US users for example, then you can adjust the RegEx settings here
// to do so. Note - for Advanced Users only

        'Validation' => array(
		'postcode' => '', // Only accept numbers
		'custom_rule_postcode' => false, // regex rule, ex: '/^[0-9a-zA-Z]{4}-[0-9a-zA-Z]{3}$/'
        ),




 // *******************************************************************************************




//
// Debug Mode
// Enable this by setting it to '1' or disable it by setting it to '0'. '2' will allow
// debugging of your database and output SQL commands.
// Do NOT leave this setting enabled on live websites, always set back to '0' 

        'debug' => 0,
	
	
	
	
//  *******************************************************************************************	

	
	
	
// 
// For advanced tracking only
// Do not edit these settings unless you are sure what you are doing

	'GoogleTracking' => array(
		'registration' => array(
			'active' => false,
			'id' => '',
			'language' => '',
			'format' => '',
			'color' => '',
			'label' => '',
		),
		'bidPurchase' => array(
			'active' => false,
			'id' => '',
			'language' => '',
			'format' => '',
			'color' => '',
			'label' => '',
		)
	),




//  *******************************************************************************************	




//
// Custom Payment gateways
// Add your own custom gateway here if you need to. 

        'PaymentGateways' => array(
            'Dotpay' => array(
                'id'       => '',        // Merchant id
                'currency' => 'USD',          // Currency
                'lang'     => 'en',           // Can be pl, en, de, it, fr, es, cz, ru
                'URL'      => 'http://www.',  // URL where the user will be redirected after payment succeded
                'URLC'     => 'http://www.',  // URL where the confirmation will be called (IPN)
            ),

            'GoogleCheckout' => array(
                'merchant_id' => '',
                'key'         => '',
                'currency'    => 'USD',
                'local'       => 'en_US',
                'charset'     => 'utf-8',
                'sandbox'     => true
            ),

            'iDeal' => array(
                'layout' => ''
            ),

            'AuthorizeNet' => array(
                'login' => '',
                'key'   => '',
                'test'  => true,
                'url'   => 'https://test.authorize.net/gateway/transact.dll',// Test URL
                //'url'   => 'https://secure.authorize.net/gateway/transact.dll',// Real Url
            ),

	    'DIBS' => array(
	    	// Merchant identity
	    	'merchant' => '',
	    
	    	// For currency check the CODE (number) on
	    	// http://tech.dibs.dk/?id=2656 or
	    	// http://en.wikipedia.org/wiki/ISO_4217 for complete ISO number
	    	// ex: if you use USD, then put 840 here
	    	'currency' => '',
	    
	    	// lang can be one of these da = Danish (default), sv = Swedish, no = Norwegian, en = English
	    	// nl = Dutch, de = German, fr = French, fi = Finnish, es = Spanish, it = Italian, fo = Faroese
	    	// pl = Polish
	    	'lang' => 'en',
	    
	    	'test' => false // 'yes' or false
	    ),

            'custom' => array(
            	'active' => false,
            	'won_url' => 'http://www.custom.com?user_id=[user_id]&auction_id=[auction_id]&price=[price]'
            )
        ),




//  *******************************************************************************************	



		
//
// SMS gateways 
// Allows SMS bidding through approved providers. Please refer to the 
// documentation available if your package supports this.

        'SmsGateways' => array(
            // Main Configurations
			'replyStatus' => false,


            // Clickatell.com SMS Gateway
            'Clickatell' => array(
                'user' => '', // Clickatell user id
                'password' => '', // Clickatell password id
                'api_id' => '' // Clickatell api id
            ),


            // Aspiro SMS Gateway
            'Aspiro' => array(
                'username' => '', // Aspiro username
				'password' => '',
                'phone_country_code' => '',
				'reply' => array(
					'text'		         => "",
                    'invalidAuctionText' => "Invalid Auction ID. Please try again.",
					'country'	         => '',
					'accessNumber'       => '',
					'senderNumber'       => '',
					'price'		         => ''
				)
            )
        ),


//  *******************************************************************************************	



//
// Recpatcha (Captcha/Turing Number) settings
// These settings control the reCaptcha module on the Register page
// to prevent automated/spammy registrations. You don't need to adjust these
// settings and can just use the global key as standard

        'Recaptcha' => array(
            'enabled' => true,
            'secure_server' => false,
            'public_key' => '6LeK8wgAAAAAAIAf8adQkBEm5lwk5wgWRii4a9wE',
            'private_key' => '6LeK8wgAAAAAAKaiZlLVLAwlHPtFmF6JLcWFCTCq'
        ),
        
		
		
		
//  *******************************************************************************************	
		
		
		

//
// Stats tracking
// Advanced statistics for your website, including page views, sources of traffic and
// much more. Needs to be enabled first by changing 'false' to 'true'. Viewable from your Admin
// Panel once enabled.

        'Stats'=>array(
		'enabled'=>false,		//if set to false, stats logging is disabled. Reduces MySQL load
		'log_admin'=>false,		//if set to true, administrator's actions will be logged
        ),
		
		
		
		
//  *******************************************************************************************	
		
		
		
		
//		
// Twitter
// Tweet this feature, allowing you to automatically Tweet auction data.
// Firstly, sign up for an account at http://twitter.com, and place the information
// for that account in the box below (username, password)

        'Twitter'=>array(
        	'enabled'=>false,		//set to false to shut off twitter integration
        	'username'=>'',			//your twitter.com username
        	'password'=>'',			//your twitter.com pasword
        	'tweet'=>'#%PRODUCT% for just %START-PRICE% %LINK%',
		
		// Bit.ly / URL Shortening
		// Within your Tweets, auto-append short URLs i.e., bit.ly/s8dJksm, to save on space. 
		// Sign up for an API account at http://bit.ly, and place the information
		// for that account in the box below
        	'short_url'=>array(
			'append_short_url'=>false,	
			'short_url_provider'=>'bit.ly',	  // current option: bit.ly only, more soon!
			'bitly'=>array(
				'login'=>'login here',
				'api_key'=>'key here'
				),
			)
	),
		
		);
		
		
		
// Automated Installation Method
//  *******************************************************************************************
//  End of config.php file. Make sure there is nothing below this line except the PHP close tag
//  *******************************************************************************************
?>