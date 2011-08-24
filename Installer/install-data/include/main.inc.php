<?php

/******************************************************************************************
 ******************************************************************************************
 *
 * phpPennyAuction Installer v0.2
 * Last updated: 10-Mar-2010
 *
 * DO NOT EDIT BELOW THIS LINE UNLESS YOU ARE SURE WHAT YOU ARE DOING!
 *
 *******************************************************************************************
 *******************************************************************************************/

if (FROM_SETUP!==true) exit;

define('DEFAULT_SALT', '07a6b2214c954ba069dbf8196d315f83a30baef9');
define('DS', '/');

if (!isset($_SESSION['setup']) || !is_array($_SESSION['setup'])) {
	//load defaults
	$_SESSION['setup']['config']['db_host']='localhost';
	$_SESSION['setup']['config']['time_zone']='America/New_York';
	$_SESSION['setup']['config']['site_currency']='USD';
	$_SESSION['setup']['config']['site_url']=(strpos($_SERVER['HTTP_HOST'], 'www.')!==FALSE)?$_SERVER['HTTP_HOST']:'www.'.$_SERVER['HTTP_HOST'];
	$_SESSION['setup']['config']['site_domain']=str_replace('www.', '', $_SERVER['HTTP_HOST']);
	$_SESSION['setup']['config']['site_name']='Your Site Name';
	$_SESSION['setup']['config']['admin_login']='admin';
}

if (isset($_POST['step'])) {
	switch ($_POST['step']) {
		case 'install1':
			//*** final validation
			$_SESSION['setup']['config']=array_merge($_SESSION['setup']['config'],$_POST['config']);
			
			$err=false;
			foreach ($_SESSION['setup']['config'] as $k=>$v) {
				if (!$v) {
					$err='Please complete all required fields.';
					continue;
				}
				if ($k=='license_number' && !preg_match('/[A-Z0-9]{4}-[A-Z0-9]{10}/Ui', $v)) {
					$err="Please enter a valid license number.";
					break;
			   }
			}
		
			if (!$err) {
				//*** Proceed with installation
				require('views/install.php');
				exit;
			}
		
		case 'genparams':
			//store and test dbparams settings
			$_SESSION['setup']['config']=array_merge($_SESSION['setup']['config'],$_POST['config']);
			@mysql_connect($_SESSION['setup']['config']['db_host'], $_SESSION['setup']['config']['db_user'], $_SESSION['setup']['config']['db_pass']);
			$conn_err=mysql_error();
			@mysql_select_db($_SESSION['setup']['config']['db_name']);
			$sel_err=mysql_error();
			
			$res=@mysql_query("SHOW TABLES;");
			if (@mysql_num_rows($res)>0) {
				$existing_tables=true;
			} else {
				$existing_tables=false;
			}
			@mysql_free_result($res);
			
			if ($conn_err || $sel_err) {
				$err="Could not connect to database. The following error(s) occured:<br>$conn_err $sel_err";
			} elseif ($existing_tables) {
				$err="The database you specified has one or more tables in it already. You cannot use this installer to upgrade an existing installation.";
			} else {
				//OK to continue, go to general parameters
				$config=$_SESSION['setup']['config'];
				require('install-data/include/views/genparams.php');
				exit;
			}
		
		case 'dbparams':
			$config=$_SESSION['setup']['config'];
			require('install-data/include/views/dbparams.php');
			exit;
		
		case 'servercheck':
			if ($_POST['install_radio']=='2') {
				$install_path=$_POST['custom_dir'];
			} else {
				$install_path=$_SERVER['DOCUMENT_ROOT'];
			}
			
			if ($install_path[strlen($install_path)-1]=='/') {
				//*chomp*
				$install_path=substr($install_path, 0, strlen($install_path)-1);
			}
			
			if (existingInstallation($install_path)) {
				fatalError('Sorry, an existing phpPennyAuction installation already exists in the path you specified.<br>'.
					'You <strong>cannot use this installer</strong> to upgrade an existing installation. Please contact phpPennyAuction support for help.');
				
			}
			
			$_SESSION['setup']['config']['install_path']=$install_path;
			
			$stop_error=false;
			
			//*** PHP version check
			if (strnatcmp(phpversion(),'5.2.1') >= 0 && !preg_match('/^6/', phpversion())) {
				$check['php_version']=array('ok', 'You\'re running PHP v'.phpversion());
			} else {
				$check['php_version']=array('error', 'Script requires PHP 5.2.x or 5.3.x. You\'re running '.phpversion());
				$stop_error=true;
			}
			
			//*** PHP safe mode check, non blocking error
			if (ini_get('safe_mode')=='On') {
				$check['safe_mode']=array('warn', 'Safe mode is on. Whilst the software will operate in safe mode, this setting can cause issues down the road. We recommend disabling safe mode before continuing.');
			} else {
				$check['safe_mode']=array('ok', 'Safe mode is off');
			}
			
			//*** PHP magic quotes check, non blocking error
			if (get_magic_quotes_gpc()) {
				$check['magic_quotes']=array('warn', 'Magic quotes are on. Whilst the software will operate with magic_quotes, this setting can cause issues down the road. We recommend disabling magic_quotes before continuing.');
			} else {
				$check['magic_quotes']=array('ok', 'Magic quotes are off');
			}

			//*** Can we write to the install path?
			if (is_writable($install_path)) {
				$check['install_path']=array('ok', 'Install path is writable');
			} elseif (!is_dir($install_path)) {
				$check['install_path']=array('error', 'Install path doesn\'t exist');
				$stop_error=true;
			} else {
				$check['install_path']=array('error', 'Cannot write to install path '.$install_path);
				$stop_error=true;
			}
			
			//*** Is ioncube installed?
			if (extension_loaded('ionCube Loader')) {
				$check['ioncube']=array('ok', 'ionCube loaders are installed');
			} else {
				$check['ioncube']=array('error', 'ionCube loaders NOT installed. They must be installed before continuing. Please review the <a href="https://members.phppennyauction.com/index.php?_m=knowledgebase&_a=view&parentcategoryid=4&pcid=0&nav=0" target="_blank">KB Articles</a> for help with installing them, or open a ticket at the <a href="https://members.phppennyauction.com/" target="_blank">phpPennyAuction Support Center.</a>');
				$stop_error=true;
			}
			
			//*** is curl installed on the server?
			if (commandExists('curl --version')) {
				$check['curl']=array('ok', 'cURL command installed');
			} else {
				$check['curl']=array('error', 'Shell cURL command not present. Please install it with apt-get install curl or yum install curl.');
				$stop_error=true;
			}
			
			//*** is curl php module installed?
			if (function_exists('curl_setopt')) {
				$check['curl_php']=array('ok', 'PHP cURL module is installed');
			} else {
				$check['curl_php']=array('error', 'PHP cURL module is NOT installed. It must be installed before continuing.');
				$stop_error=true;
			}
			
			//*** is unzip installed?
			if (commandExists('unzip')) {
				$check['unzip']=array('ok', 'Shell unzip command exists');
			} else {
				$check['unzip']=array('error', 'Shell unzip command not present. Please install it with \'apt-get install unzip\' or \'yum install unzip\'.');
				$stop_error=true;
			}

			//*** is unzip installed?
			if (!file_exists($install_path.DS.'.htaccess')) {
				$check['htaccess']=array('ok', 'No .htaccess conflict');
			} else {
				$check['htaccess']=array('warn', '.htaccess file exists in your installation path. Instalation process will rename this file to: \'.htaccess.backup\' and install a new one');
			}

			
			require('install-data/include/views/servercheck.php');
			exit;
		default:
	}
	
}


//show default page
require('install-data/include/views/welcome.php');



function commandExists($cmd) {
	$handle = popen($cmd, 'r');
	$read = fread($handle, 2096);
	pclose($handle);
	if (!$read or strstr($read, 'not found')) {
		return false;
	} else {
		return true;
	}
}

function existingInstallation($install_path) {
	$key_files=array(	'app/config/config.php',
				'views/helpers/paypal.php',
				'app/views/helpers/paypal.php',
				'controllers/users_controller.php',
				'models/bid.php',
				'app/models/bid.php',
				'app_controller.php',
				'/app/app_controller.php'
				);
	
	foreach ($key_files as $file) {
		if (file_exists($install_path . DS . $file)) {
			return true;	
		}
	}
	
	return false;
}

function explode_quoted($delim=',', $str, $enclose='"', $preserve=false){
	$resArr = array();
	$n = 0;
	$expEncArr = explode($enclose, $str);
	foreach($expEncArr as $EncItem){
		if($n++%2){
			array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
		}else{
			$expDelArr = explode($delim, $EncItem);
			array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
			$resArr = array_merge($resArr, $expDelArr);
		}
	}
	return $resArr;
}

function fatalError($err) {
	require('views/fatalerror.php');
	exit;
}

function pr($arr) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}
function getEncodings()	{
	return array(
		"utf-8"				=>"utf-8 (recommended)",
		"ISO-8859-1"			=>"ISO-8859-1",
		"ISO-8859-2"			=>"ISO-8859-2",
		"ISO-8859-3"			=>"ISO-8859-3",
		"ISO-8859-4"			=>"ISO-8859-4",
		"ISO-8859-5"			=>"ISO-8859-5",
		"ISO-8859-6"			=>"ISO-8859-6",
		"ISO-8859-7"			=>"ISO-8859-7",
		"ISO-8859-8"			=>"ISO-8859-8",
		"ISO-8859-9"			=>"ISO-8859-9",
		"ISO-8859-10"			=>"ISO-8859-10",
		"ISO-8859-11"			=>"ISO-8859-11",
		"ISO-8859-12"			=>"ISO-8859-12",
		"ISO-8859-13"			=>"ISO-8859-13",
		"ISO-8859-14"			=>"ISO-8859-14",
		"ISO-8859-15"			=>"ISO-8859-15",
		"ISO-8859-16"			=>"ISO-8859-16",
		"windows-1250"			=>"windows-1250",
		"windows-1251"			=>"windows-1251",
		"windows-1252"			=>"windows-1252",
		"windows-1253"			=>"windows-1253",
		"windows-1254"			=>"windows-1254",
		"windows-1255"			=>"windows-1255",
		"windows-1256"			=>"windows-1256",
		"windows-1257"			=>"windows-1257",
		"windows-1258"			=>"windows-1258",
		"GB 2312"			=>"GB 2312",
		"GB 18030"			=>"GB 18030",
		"GBK"				=>"GBK",
		"KS X 1001"			=>"KS X 1001",
		"EUC-KR"			=>"EUC-KR",
		"ISO-2022-KR"			=>"ISO-2022-KR",

		);
}
function getCurrencies()	{
	return array(
		"USD"				=>"USD",
		"GBP"				=>"GBP",
		"EUR"				=>"EUR",
		"CAD"				=>"CAD",
		"AUD"				=>"AUD",
		"JPY"				=>"JPY",
		"BRL"				=>"BRL",
		"ZAR"				=>"ZAR",
		"CHF"				=>"CHF",
		"INR"				=>"INR",
		"MXN"				=>"MXN",
		"SEK"				=>"SEK",
		);
}
function getTimezones()	{
	return array(
		"Pacific/Midway"                 => "(GMT-11:00) Midway Island, Samoa",
		"America/Adak"                   => "(GMT-10:00) Hawaii-Aleutian",
		"Etc/GMT+10"                     => "(GMT-10:00) Hawaii",
		"Pacific/Marquesas"              => "(GMT-09:30) Marquesas Islands",
		"Pacific/Gambier"                => "(GMT-09:00) Gambier Islands",
		"America/Anchorage"              => "(GMT-09:00) Alaska",
		"America/Ensenada"               => "(GMT-08:00) Tijuana, Baja California",
		"Etc/GMT+8"                      => "(GMT-08:00) Pitcairn Islands",
		"America/Los_Angeles"            => "(GMT-08:00) Pacific Time (US &amp; Canada)",
		"America/Denver"                 => "(GMT-07:00) Mountain Time (US &amp; Canada)",
		"America/Chihuahua"              => "(GMT-07:00) Chihuahua, La Paz, Mazatlan",
		"America/Dawson_Creek"           => "(GMT-07:00) Arizona",
		"America/Belize"                 => "(GMT-06:00) Saskatchewan, Central America",
		"America/Cancun"                 => "(GMT-06:00) Guadalajara, Mexico City, Monterrey",
		"Chile/EasterIsland"             => "(GMT-06:00) Easter Island",
		"America/Chicago"                => "(GMT-06:00) Central Time (US &amp; Canada)",
		"America/New_York"               => "(GMT-05:00) Eastern Time (US &amp; Canada)",
		"America/Havana"                 => "(GMT-05:00) Cuba",
		"America/Bogota"                 => "(GMT-05:00) Bogota, Lima, Quito, Rio Branco",
		"America/Caracas"                => "(GMT-04:30) Caracas",
		"America/Santiago"               => "(GMT-04:00) Santiago",
		"America/La_Paz"                 => "(GMT-04:00) La Paz",
		"Atlantic/Stanley"               => "(GMT-04:00) Faukland Islands",
		"America/Campo_Grande"           => "(GMT-04:00) Brazil",
		"America/Goose_Bay"              => "(GMT-04:00) Atlantic Time (Goose Bay)",
		"America/Glace_Bay"              => "(GMT-04:00) Atlantic Time (Canada)",
		"America/St_Johns"               => "(GMT-03:30) Newfoundland",
		"America/Araguaina"              => "(GMT-03:00) UTC-3",
		"America/Montevideo"             => "(GMT-03:00) Montevideo",
		"America/Miquelon"               => "(GMT-03:00) Miquelon, St. Pierre",
		"America/Godthab"                => "(GMT-03:00) Greenland",
		"America/Argentina/Buenos_Aires" => "(GMT-03:00) Buenos Aires",
		"America/Sao_Paulo"              => "(GMT-03:00) Brasilia",
		"America/Noronha"                => "(GMT-02:00) Mid-Atlantic",
		"Atlantic/Cape_Verde"            => "(GMT-01:00) Cape Verde Is",
		"Atlantic/Azores"                => "(GMT-01:00) Azores",
		"Europe/Belfast"                 => "(GMT) Greenwich Mean Time : Belfast",
		"Europe/Dublin"                  => "(GMT) Greenwich Mean Time : Dublin",
		"Europe/Lisbon"                  => "(GMT) Greenwich Mean Time : Lisbon",
		"Europe/London"                  => "(GMT) Greenwich Mean Time : London",
		"Africa/Abidjan"                 => "(GMT) Monrovia, Reykjavik",
		"Europe/Amsterdam"               => "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
		"Europe/Belgrade"                => "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague",
		"Europe/Brussels"                => "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris",
		"Africa/Algiers"                 => "(GMT+01:00) West Central Africa",
		"Africa/Windhoek"                => "(GMT+01:00) Windhoek",
		"Asia/Beirut"                    => "(GMT+02:00) Beirut",
		"Africa/Cairo"                   => "(GMT+02:00) Cairo",
		"Asia/Gaza"                      => "(GMT+02:00) Gaza",
		"Africa/Blantyre"                => "(GMT+02:00) Harare, Pretoria",
		"Asia/Jerusalem"                 => "(GMT+02:00) Jerusalem",
		"Europe/Minsk"                   => "(GMT+02:00) Minsk",
		"Asia/Damascus"                  => "(GMT+02:00) Syria",
		"Europe/Moscow"                  => "(GMT+03:00) Moscow, St. Petersburg, Volgograd",
		"Africa/Addis_Ababa"             => "(GMT+03:00) Nairobi",
		"Asia/Tehran"                    => "(GMT+03:30) Tehran",
		"Asia/Dubai"                     => "(GMT+04:00) Abu Dhabi, Muscat",
		"Asia/Yerevan"                   => "(GMT+04:00) Yerevan",
		"Asia/Kabul"                     => "(GMT+04:30) Kabul",
		"Asia/Yekaterinburg"             => "(GMT+05:00) Ekaterinburg",
		"Asia/Tashkent"                  => "(GMT+05:00) Tashkent",
		"Asia/Kolkata"                   => "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
		"Asia/Katmandu"                  => "(GMT+05:45) Kathmandu",
		"Asia/Dhaka"                     => "(GMT+06:00) Astana, Dhaka",
		"Asia/Novosibirsk"               => "(GMT+06:00) Novosibirsk",
		"Asia/Rangoon"                   => "(GMT+06:30) Yangon (Rangoon)",
		"Asia/Bangkok"                   => "(GMT+07:00) Bangkok, Hanoi, Jakarta",
		"Asia/Krasnoyarsk"               => "(GMT+07:00) Krasnoyarsk",
		"Asia/Hong_Kong"                 => "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
		"Asia/Irkutsk"                   => "(GMT+08:00) Irkutsk, Ulaan Bataar",
		"Australia/Perth"                => "(GMT+08:00) Perth",
		"Australia/Eucla"                => "(GMT+08:45) Eucla",
		"Asia/Tokyo"                     => "(GMT+09:00) Osaka, Sapporo, Tokyo",
		"Asia/Seoul"                     => "(GMT+09:00) Seoul",
		"Asia/Yakutsk"                   => "(GMT+09:00) Yakutsk",
		"Australia/Adelaide"             => "(GMT+09:30) Adelaide",
		"Australia/Darwin"               => "(GMT+09:30) Darwin",
		"Australia/Brisbane"             => "(GMT+10:00) Brisbane",
		"Australia/Hobart"               => "(GMT+10:00) Hobart",
		"Asia/Vladivostok"               => "(GMT+10:00) Vladivostok",
		"Australia/Lord_Howe"            => "(GMT+10:30) Lord Howe Island",
		"Etc/GMT-11"                     => "(GMT+11:00) Solomon Is, New Caledonia",
		"Asia/Magadan"                   => "(GMT+11:00) Magadan",
		"Pacific/Norfolk"                => "(GMT+11:30) Norfolk Island",
		"Asia/Anadyr"                    => "(GMT+12:00) Anadyr, Kamchatka",
		"Pacific/Auckland"               => "(GMT+12:00) Auckland, Wellington",
		"Etc/GMT-12"                     => "(GMT+12:00) Fiji, Kamchatka, Marshall Is",
		"Pacific/Chatham"                => "(GMT+12:45) Chatham Islands",
		"Pacific/Tongatapu"              => "(GMT+13:00) Nuku'alofa",
		"Pacific/Kiritimati"             => "(GMT+14:00) Kiritimati"
	);
}
?>