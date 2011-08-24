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
 * Last updated: 11-Mar-2011 /  http://www.phppennyauction.com 
 *
 * DISTRIBUTION DENIED WITHOUT EXPRESS PERMISSION. 
 * We will prosecute all offenders to the maximum possible possible extend allowed under law.
 * File must retain this copyright notice. Use of the software signifies acceptance of the
 * Terms of Use and License Agreement.
 * 
 *
 * NEED HELP?
 * For all support-related inquiries, please refer to the Support Center at
 * https://members.phppennyauction.com. You will find a  Knowledge Base (KB), Wiki,
 * Member Forums, Video Tutorials and much more available to all members - at no cost.
 *
 *********************************************************************************************
 *********************************************************************************************/
 
 
 
 
 // Important:
 // We recommend using the Automated Installer to populate all the needed fields here. If you
 // are installing manually, please go through and check that these settings are populated in
 // line with the Knowledge Base (KB) articles available. Manual Installation method is 
 // recommended for advanced users only. 
 

 
 
 // *******************************************************************************************
 
 
 
 
 // 
 // MySQL Database Settings
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
            'host'       => 'localhost', // MySQL Hostname, usually 'localhost'
            'login'      => 'root', // MySQL Username
            'password'   => '', // MySQL Password
            'database'   => 'penny', // MySQL Database Name
            'prefix'     => '', // not usually required, leave blank
            'encoding'	 => 'utf8' 
        ),
		
		
		
//  *******************************************************************************************	
		
		
		
//
// Core Website Settings
// 
// These fields should be auto-populated by the installation script. If you are installing
// manually, please consult with the Install Guide and other documentation in the phpPennyAuction
// Support Center for assistance.

        'App' => array(
		'license'                => '', // Paste your license key from the Support Center here (begins with 'phpPA-')
		'encoding'               => 'UTF-8',
		'baseUrl'                => '', // Required only if using subdomain/folder
		'base'                   => '', // Required only if using subdomain/folder
		'dir'		    		 => '', // Required only if using subdomain/folder
		'webroot'				 => 'webroot',
		'name'                   => 'Site Title Here',
		'url'                    => 'http://penny.dev', // fill this out
		'ref_url'                => 'http://penny.dev', // fill this out
		'nml_url'                => 'http://penny.dev', // fill this out
		'serverName'             => '', //  this isn't usually needed 
		'timezone'               => 'London/England', // check the Time Zone article in the Knowledge Base for the correct syntax
		'language'               => 'eng', // Three-letter only, eng = English. 
		'email'                  => 'info@YOURDOMAINHERE.com', // fill this out and change it to desired email
		'currency'               => 'USD', // Three-letter currency code, i.e. GBP, USD, CAD. For other currencies, please see Knowledge Base
		
		
		
		
//  *******************************************************************************************
		
		
		
		
//		
// Templates Setting (Skin/Theme)
// 
// If you have purchased templates alongside your order, please refer to
// rhe documentation available inside the download. The default template is
// already set up below, you don't need to adjust this if this is the one you
// want to use. 
		'theme'                  => 'template_frog',
		
		
		
		
//  *******************************************************************************************	
		
		
		
//		
// Rest of Software Settings
//
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
		'memoryLimit'      	  	 => '256M', // needs to be mirrored in your php.ini file - advanced users ONLY!
		'autobidTime'      		 => 10, 
		'gateway' 	       		 => true, // leave this enabled or it will mess up your website! :)
		'demoMode' 	       		 => true, // 'admin mode' - please see Knowledge Base (KB) documentation on this.
		'autobids'               => false, // use autobidding/test bidders on your website?
		'smartAutobids'          => true, // makes the autobidders act in more realistic way, recommended to enable if autobids are enabled
		'bidIncrements' 		 => 'dynamic', // options are: single, dynamic, product
		'bidButlerType'      	 => 'simple', // options are: simple, advanced
		'bidButlerDeploy'		 => 'single', // options are: single, grouped.  Grouped only works when bid increments not dynamic
		// 'bidButlerRapidDeploy'	 => '10', // advanced users only, dsiabled by default
		'homeEndingLimit'     	 => 10, // the number of auctions to show in the 'ending soon' part of the homwepage
		'homeFeaturedLimit'      => 5, // the number of auctions to show in the 'Featured' top part of the homepage
		'homeFeaturedAuction'    => true, //enable 'featured' auctions to show on your homepage?
		'newsletterSelected'     => true, // default subcription mode for the newsletter when customers sign up
		'uniqueAuctionLayout'    => false, // please see documentation in the Knowledge Base (KB) for this, leave to 'false' or you may mess your site up
		'sourceRequired'	     => true, // force the 
		'phoneRequired'	 		 => false, // force the phone number field to be entered, when your customers register?
		'taxNumberRequired' 	 => true, // if included in your template, force the customer to enter a tax ID or VAT #?
		'endedLimit'			 => 30, // the number of auctions to show in the 'Ended' auctions page? set to 0 for unlimited
		'flashMessage'           => false,
		'simpleBids'			 => false,
		'rewardsPoint'           => false,
		'coupons'                => false, // enable the coupons module?
		'hiddenReserve'			 => true, // enable the hidden reserve module?
		'emailWinner'			 => true, // send an email to the winner of the auction?
		'timeFormat'             => 24, // can be 12 or 24
		'ipBlock'				 => 0,
		'delayedStart'			 => false,
		'cronTime' 	        	 => 1, // the cron job execution time, in minutes. Set to 20 if you can only run crons every 20th minute, for example. Default is 1 (minute)
		'bidButlerSleep'		 => 2, // higher for decreased server load, lower for more accurate bid butlers, default 4
		'wwwRedirect'			 => false, // if enabled, forces the 'www.' in all URLs, cannot be used if installing to a sub-domain
		'sslUrl'				 => '', // add your https:// secure URL here, and in the ref_url setting above, if you want to use SSL secure pages.
		'registerOff'			 => false,	// require login/registration to bid? NOT all templates are supported


//
// Madbid Counter Settings
//
// If you want the timer to never go beyond X number of
// seconds/minutes, set that here. 0 = disabled

		'maxCounterTime' 		 => 0, // if greater than zero, the time will not go past this point once this time is met.
		
		
		
//
// Deprecated features
//
// These features require additional work to use and are here for legacy purposes only, or are 
// very advanced. We do not recommend using them unless required as they may have bugs or have
// other undesirable effects.

		'forum'                  => false, // use the bundled Forum software? please contact support for assistance with this.
		'affiliates'			 => false, // enable the affiliates module?
		'daemons_log'			 => false, // turn on only for cronjob debugging, log files can get HUGE, open a ticket if unsure!





//  *******************************************************************************************




// 
// Buy Now Module settings
// 
// Please refer to documentation on this in our Knowledge Base (KB), or see the basic notes below	
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
 

		
		
		
//  *******************************************************************************************
		
		
		

//
// Custom Image Settings (Thumbnails)
//
// If you want to adjust the size of the product images / thumbs. All
// sizes are in pixels

		'Image' => array(
		'thumb_width'  => 100,
		'thumb_height' => 100,
		'max_width'    => 340,
		'max_height'   => 230
		),
		
		
		
		
		
//  *******************************************************************************************	
		
		
		
		
//		
// Date of Birth Settings
//
// Adjust the Age requirements for your websiteto prevent minors from signing up etc	

		'Dob' => array(
			'year_min' => date('Y') - 100,
			'year_max' => date('Y') - 18
		),
		
		'credits' => array(
			'active'	=> false,
			'value'     => 1,
			'expiry'    => 45,
		),
		
		
		
		
//  *******************************************************************************************
		
		
		
		

//
// Winning Limits
//
// Use Limits to reduce the number of possible wins by your site visitors on specific
// products and categories. NB: Disabled by default.

		'limits' => array(
			'active' => false,
				'limit' => 8,
				'expiry' => 28, // the number of days
		),
		
		
		
		
		
//  *******************************************************************************************
		
		
		
		
//
// Cleaner Settings
// 
// Clears out the previously ended auctions from your website and keeps
// everything running smoothly. Please only adjust if you are sure what you
// are doing!

		'cleaner' => array(
			'active' => true,
			'clear' => 30,     // the number of days the auctions should remain
			'clear_all' => 35, // the number of days until ALL auctions are deleted
		),
		
		
		
		
//  *******************************************************************************************
		
			
			
		
//
// Multi-Versions
//
// Use the software with varying languages, currencies, timezones
// and much more. See the KB for the full introduction to this feature. If you 
// only want to use one single site, you can ignore these settings. 

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
		
		
		
				
//  *******************************************************************************************	
			
	
		

//
// Payment Gateways Settings
// Refer to the Knowledge Base (KB) for assistance with these. PayPal is automatically
// set up if you are using the self-installer using your default email
// address. If you want to use other payment gateways, please refer to the
// Knowledge Base (KB) for guidance and notes, or contact phpPennyauctio support.

// PayPal 
        'Paypal' => array(
            'url'   	 => 'https://www.paypal.com/cgi-bin/webscr',
            'email' 	 => 'paypal@YOURDOMAINHERE.com', //enter your default PayPal email address here. 
            'lc'    	 => 'USD',
        ),

// PayPal Pro Gateway
        'PaypalProUk' => array(
            'username' 		=> '',
            'password' 		=> '',
            'signature' 	=> '',
            'endpoint' 		=> 'https://api-3t.paypal.com/nvp',
            'use_proxy'	 	=> false,
            'proxy_host' 	=> '127.0.0.1',
            'proxy_port' 	=> 80,
            'version' 		=> '52.0',
            'ssl_url'		=> ''
        ),


// ePayment Gateway
        'ePayment' => array(
            'merchant'  => '', // merchant identifier
            'key'       => '', // key signature
            'testorder' => 'TRUE', // empty this out to get live
            'language'  => 'en' // can be = en, ro, fr, it, es de
        ),





//  *******************************************************************************************




// 
// Email settings
// 
// These are autopopulated, by the Installer script. If you are
// having difficulty with emails and they are not being sent/received
// please de-comment out the section immediately below and try the
// third-party option below (commented out by default).

        'Email' => array( 
            'IsSMTP'     => false,
            'IsHTML'     => true,
            'SMTPAuth'   => false,
            'CharSet'   => 'UTF-8',
            'Host'       => 'localhost',
            'Port'       => 25,
            'WordWrap'   => 50,
            'From'       => 'no-reply@YOURDOMAINHERE.com',  //fill this in
            'FromName'   => 'YOURDOMAINHERE.com', //fill this in with your chosen business/website name
            'ReplyTo'    => 'info@YOURDOMAINHERE.com' //fill this in
            
        ),
		
		
// The section below uses Gmail's IMAP Servers, you don't need to use
// these settings unless you are having difficulties with the above default
// SMTP settings for your server. Make sure to comment the above out if you 
// want to use the below (de-comment that too). 
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
            'FromName'   => 'Website Name',
            'ReplyTo'    => 'info@YOURDOMAINHERE.com'
        ),*/




//  *******************************************************************************************




//
// Disable the website cache
// Be very careful here, this will slow your website down and is
// meant for trouble-shooting only.

        'Cache' => array(
            'disable' => false,
            'check' => false,
            'time' => '' // relative time such as +1 day, +2 months, +3 minutes
        ),



//  *******************************************************************************************




//
// Postcode/Zip Validation 
// 
// If you want to allow RegEx expressions such as only 5 digit zip codes, if you
// want to block non-US users for example, then you can adjust the RegEx settings here
// to do so. Please see the Knowledge Base for RegEx values.

        'Validation' => array(
		'postcode' => '', // Only accept numbers
		'custom_rule_postcode' => false, // regex rule, ex: '/^[0-9a-zA-Z]{4}-[0-9a-zA-Z]{3}$/'
        ),




//  *******************************************************************************************




//
// Debug Mode
//
// Enable this by setting it to '1' or disable it by setting it to '0'. '2' will allow
// debugging of your database and output SQL commands.
// Do NOT leave this setting enabled on live websites, always set back to '0' 
        'debug' => 0,
	
	
	
	
//  *******************************************************************************************
	
		
	

//
// Custom Payment gateways
// 
// Add your own custom gateway here if you need to. 
        'PaymentGateways' => array(
								   
// DotPay					   
            'Dotpay' => array(
                'id'       => '',        // Merchant id
                'currency' => 'USD',          // Currency
                'lang'     => 'en',           // Can be pl, en, de, it, fr, es, cz, ru
                'URL'      => 'http://www.',  // URL where the user will be redirected after payment succeded
                'URLC'     => 'http://www.',  // URL where the confirmation will be called (IPN)
            ),
			
// Google Checkout
            'GoogleCheckout' => array(
                'merchant_id' => '',
                'key'         => '',
                'currency'    => 'USD',
                'local'       => 'en_US',
                'charset'     => 'utf-8',
                'sandbox'     => true
            ),
			
// iDEAL/TargetPay
            'iDeal' => array(
                'layout' => ''
            ),

// Authorize.NET
            'AuthorizeNet' => array(
                'login' => '',
                'key'   => '',
                'test'  => true,
                'url'   => 'https://test.authorize.net/gateway/transact.dll',// Test URL
                //'url'   => 'https://secure.authorize.net/gateway/transact.dll',// Real Url
            ),

// DIBS Danish Gateway
	    'DIBS' => array(
	    	// Merchant identity
	    	'merchant' => '',
	    
// For currency check the CODE (number) on http://tech.dibs.dk/?id=2656 or
// http://en.wikipedia.org/wiki/ISO_4217 for complete ISO. If you use USD, then put 840 here
	    	'currency' => '',
	    
// Accept languages: da = Danish (default), sv = Swedish, no = Norwegian, en = English nl = Dutch,
// de = German, fr = French, fi = Finnish, es = Spanish, it = Italian, fo = Faroese, pl = Polish
	    	'lang' => 'en',
	    	'test' => false // 'yes' or 'false'
	    ),

// Add Custom Payment Gateway ?
// Using the default settings and format here
			'custom' => array(
            	'active' => false,
            	'won_url' => 'http://www.custom.com?user_id=[user_id]&auction_id=[auction_id]&price=[price]'
            )
        ),





//  *******************************************************************************************


		
		
//
// SMS gateways 
// Allows SMS bidding through approved providers. Please refer to the 
// Knowledge Base & docs available if your package supports this.

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
// Recpatcha (Captcha Number) Settings
//
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
// Stats Settings
//
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
// 
// Tweet this feature, allowing you to automatically Tweet auction data.
// Firstly, sign up for an account at http://twitter.com, and place the information
// for that account in the box below (username, password)
//
// Note: POSTING to Twitter no longer works with 2.4.2, as Twitter changed their OAuth
// authentiation system. We will make this fully compatible in the next release. All
// other features with Twitter work however. 

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
			'short_url_provider'=>'bit.ly',	  // current option: bit.ly only (default), more soon!
			'bitly'=>array(
				'login'=>'login here',
				'api_key'=>'key here'
				),
			)
	),
        
    );
	
	
	
	
	
	
	
//  Installation method	: Manual
//  *******************************************************************************************
//  End of config.php file. Make sure there is nothing below this line except the PHP close tag
//  *******************************************************************************************
?>