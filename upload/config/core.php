<?php
	define('LOG_ERROR', 2);

	Configure::write('Routing.admin', 'admin');
	
	$session = array(
		'save' => 'php',
		'cookie' => 'AUCTION',
		'timeout' => '120',
		'start' => true,
		'checkAgent' => true
		//'table' => 'cake_sessions',
		//'database' => 'default'
	);
	Configure::write('Session', $session);

	$security = array(
		'level' => 'medium',
		'salt' => '07a6b2214c954ba069dbf8196d315f83a30baef9'
	);
	Configure::write('Security', $security);

	//Configure::write('Asset.filter.css', 'css.php');
	//Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');

	//Configure::write('Acl.classname', 'DbAcl');
	//Configure::write('Acl.database', 'default');

	//define('CACHE_DIR', TMP.'cache'.DS.$_SERVER['SERVER_NAME']);
	define('CACHE_DIR', TMP.'cache');
	if (!is_dir(TMP.'cache')) {
		@mkdir(TMP.'cache');
		@chmod(TMP.'cache', 0777);
	}
	if (!is_dir(TMP.'cache'.DS.'models')) {
		@mkdir(TMP.'cache'.DS.'models');
		@chmod(TMP.'cache'.DS.'models', 0777);
	}	
	if (!is_dir(CACHE_DIR)) {
		@mkdir(CACHE_DIR);
		@chmod(CACHE_DIR, 0777);
	}
	

	Cache::config('default', array('engine' => 'File', 'path'=>TMP.'cache'));
	Cache::config('minute', array('engine' => 'File', 'path'=>TMP.'cache', 'duration'=> '+1 minute'));
	Cache::config('five_minute', array('engine' => 'File', 'path'=>TMP.'cache', 'duration'=> '+5 minute'));
	Cache::config('hour', array('engine' => 'File', 'path'=>TMP.'cache', 'duration'=> '+1 hours'));
	Cache::config('day', array('engine' => 'File', 'path'=>TMP.'cache', 'duration'=> '+1 day'));
	Cache::config('week', array('engine' => 'File', 'path'=>TMP.'cache', 'duration'=> '+7 day'));
	Cache::config('year', array('engine' => 'File', 'path'=>TMP.'cache', 'duration'=> '+1 year'));
	Configure::write('viewPaths', 'asgas');

?>
