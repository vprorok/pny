<?php
	uses('L10n');
	Configure::load('config');

	// Get the configuration in one read
	$appConfigurations = Configure::read('App');
    
	// lets check for multiversions
	$_SERVER['SERVER_NAME'] = str_replace('www.', '', $_SERVER['SERVER_NAME']);

	
	//See if we're multiversion
	if (Configure::read('serverName') !== $_SERVER['SERVER_NAME']) {
		$multiversions=Configure::read('App.multiVersions');
		if (!empty($multiversions)) {
			foreach ($multiversions as $url => $details) {
				if($url == $_SERVER['SERVER_NAME']) {
					//pr($appConfigurations['multiVersions'][$url]);exit;
					Configure::write('App.serverName', $url);
					Configure::write('App.name', $details['name']);
					Configure::write('App.url', $details['url']);
					if ($details['timezone']) {
						Configure::write('App.timezone', $details['timezone']);
					}
					if ($details['language']) {
						Configure::write('App.language', $details['language']);
					}
					if ($details['currency']) {
						Configure::write('App.currency', $details['currency']);
					}
					Configure::write('App.noCents', $details['noCents']);
					if ($details['theme']) {
						Configure::write('App.theme', $details['theme']);
					}
					
					$lang=$details['language'];
					
					$appConfigurations = Configure::read('App');
				}
			}
		}
	}
	
	//sslRedirect
	if(!empty($appConfigurations['sslRedirect'])) {
		if($_SERVER['HTTPS']<>'on') {
			header('location: '.$appConfigurations['sslUrl'].$_SERVER['REQUEST_URI']);
		}
	}
	
	// wwwRedirect
	if(!empty($appConfigurations['wwwRedirect']) && !isset($lang)) {
		if(substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.') {
			header('location:'.$appConfigurations['url'].$_SERVER['REQUEST_URI']);
		}
	}

	// used for setting default config values
	if(empty($appConfigurations['adminPageLimit'])) {
		Configure::write('App.adminPageLimit', 100);
	}
	
	// Do not change any line below
	if(!empty($appConfigurations['timezone'])){
		@putenv("TZ=".$appConfigurations['timezone']);
	}
	
	// Set the internationalization
	if (isset($lang)) {
		Configure::write('Config.language', $lang);
	} else {
		Configure::write('Config.language', $appConfigurations['language']);
	}
	
	ini_set('memory_limit', $appConfigurations['memoryLimit']);
	
	// define the image thumb constant
	define('IMAGE_THUMB_WIDTH', $appConfigurations['Image']['thumb_width']);
	define('IMAGE_THUMB_HEIGHT', $appConfigurations['Image']['thumb_height']);
	define('IMAGE_MAX_WIDTH', $appConfigurations['Image']['max_width']);
	define('IMAGE_MAX_HEIGHT', $appConfigurations['Image']['max_height']);

	//*** Setting type constants
	define('SETTING_TYPE_TEXT', 0);
	define('SETTING_TYPE_TEXTAREA', 1);
	define('SETTING_TYPE_ONOFF', 2);
	define('SETTING_TYPE_TRUEFALSE', 3);
	define('SETTING_TYPE_YESNO', 4);
	
	//*** Path info
	define('PLUGIN', VENDORS.'plugins/');
	
	//*** Version info
	define('PHPPA_VERSION', '2.4.2');
	define('PHPPA_BUILD', '100910');
	
	//*** Load Plugin manager
	App::import('vendor', 'plugins/plugin_manager');
	PluginManager::loadPlugins();
?>