<script type="text/javascript">

$(document).ready(function () {
});

</script>


<div class="span-30 prepend-top">


</head>
<body>
  
<div id="progressbar" style="width:982px"></div>

<div id="status" class="span-15" style="font-weight:bold; font-size:16px">
<p id="progress_message">Initializing...</p>
</div>

<p style="clear:both">&nbsp;</p>

<?php
sleep(0);

?>
  <script>
    $("#progressbar").progressbar({ value: 0 });
  </script>
<?php
updateProgress(5, 'Preparing to unpack...');

sleep(2);

//*** If .htaccess exists - rename it.
if(file_exists($_SESSION['setup']['config']['install_path'].DS.'.htaccess'))
{
	updateProgress(5, 'Renaming .htaccess file...');

	sleep(2);

	if(!rename($_SESSION['setup']['config']['install_path'].DS.'.htaccess', $_SESSION['setup']['config']['install_path'].DS.'.htaccess.backup'))
	{
		 fataInstallError('Cannot rename .htaccess file!');
	}
}


//*** go through and unzip each level
for ($x=1; $x<11; $x++) {
	//does this level exist?
	$dir=$_SERVER['DOCUMENT_ROOT'].'/install-data/packages/'.str_pad($x, 2, '0',STR_PAD_LEFT);
	if (is_dir($dir)) {
		
		updateProgress(5+($x*6), "Unpacking level ".$x);
		
		
		$d=dir($dir);
		while (false !== ($entry = $d->read())) {
			if (!preg_match('/.zip$/', $entry)) continue;
			$handle = popen('unzip '.$dir.'/'.$entry.' -d '.$_SESSION['setup']['config']['install_path'].DS, 'r');
			$read = fread($handle, 2096);
			//echo $read;
			pclose($handle);	
		}
		$d->close();
		
	}
}




//*** Make/permission temp directories
updateProgress(60, 'Creating temp directories/permissioning');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/tmp');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/tmp/cache');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/tmp/logs');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/webroot/img/product_images');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/webroot/img/product_images/max');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/webroot/img/product_images/thumbs');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/webroot/img/rewards');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/webroot/img/rewards/max');
createTmpDir($_SESSION['setup']['config']['install_path'].DS.'/webroot/img/rewards/thumbs');

sleep(1);


//*** Write config file
updateProgress(70, 'Writing config file...');

$config=file_get_contents($_SERVER['DOCUMENT_ROOT'].DS.'install-data'.DS.'misc'.DS.'config-template.php');

//substitute tokens
$from=array(
	'%db_host%',
	'%db_user%',
	'%db_pass%',
	'%db_name%', 
	'%license_number%',
	'%site_encoding%',
	'%site_name%',
	'%site_url%',
	'%time_zone%',
	'%admin_email%',
	'%site_currency%',
	'%site_domain%',
	);
$to=array(
	$_SESSION['setup']['config']['db_host'],
	$_SESSION['setup']['config']['db_user'],
	$_SESSION['setup']['config']['db_pass'],
	$_SESSION['setup']['config']['db_name'],
	$_SESSION['setup']['config']['license_number'],
	$_SESSION['setup']['config']['site_encoding'],
	$_SESSION['setup']['config']['site_name'],
	$_SESSION['setup']['config']['site_url'],
	$_SESSION['setup']['config']['time_zone'],
	$_SESSION['setup']['config']['admin_email'],
	$_SESSION['setup']['config']['site_currency'],
	$_SESSION['setup']['config']['site_domain']
	);

$config=str_replace($from, $to, $config);

file_put_contents($_SESSION['setup']['config']['install_path'].'/config/config.php', $config);



//*** Create database
updateProgress(70, 'Creating database...');
sleep(1);
@mysql_connect($_SESSION['setup']['config']['db_host'], $_SESSION['setup']['config']['db_user'], $_SESSION['setup']['config']['db_pass']);
if (mysql_error()) fataInstallError('Cannot connect to database');
@mysql_select_db($_SESSION['setup']['config']['db_name']);
if (mysql_error()) fataInstallError('Cannot select database');

for ($x=1; $x<11; $x++) {
	//does this level exist?
	$dir=$_SERVER['DOCUMENT_ROOT'] . DS . 'install-data'. DS .'sql'. DS .str_pad($x, 2, '0',STR_PAD_LEFT);
	if (is_dir($dir)) {
		
		updateProgress(70+($x*5), "Installing SQL ".$x);
		
		
		$d=dir($dir);
		while (false !== ($entry = $d->read())) {
			if (!preg_match('/.sql$/', $entry)) continue;
			
			$sql_contents=file($dir.DS.$entry);
			foreach ($sql_contents as &$lin) {
				if (preg_match('/^--/', trim($lin))) $lin='';
			}
			$sql_contents=implode($sql_contents);
			
			
			$queries=explode_quoted(';', $sql_contents, "'", true);
			
			foreach ($queries as $query) {
				if (preg_match('@^(--|\/\*)@', $query) or trim($query)=='') continue;
				mysql_query($query);
				if (mysql_error()) {
					fatalInstallError('Database error: '.mysql_error());
				}
			}
		}
		$d->close();
		
	}
}

//*** Write admin user
updateProgress(80, 'Creating admin user...');
$hash_pass=hashPass($_SESSION['setup']['config']['admin_password'], null, DEFAULT_SALT);

@mysql_query("INSERT INTO `users` (`username`,`password`,`email`,`active`,`newsletter`,`admin`,`ip`,`created`,`modified`) 
			VALUES (	'".addslashes($_SESSION['setup']['config']['admin_login'])."', 
					'$hash_pass', 
					'".addslashes($_SESSION['setup']['config']['admin_email'])."', 
					'1',
					'1',
					'1',
					'{$_SERVER['REMOTE_ADDR']}',
					NOW(),
					NOW());");
if (mysql_error()) {
	fatalInstallError('Database error: '.mysql_error());
}


//*** DONE!


sleep(2);
updateProgress(100, 'Installation complete!');
?>

<p><strong>Success!</strong> Thank you for installing phpPennyAuction. You can now administer your <a href="/">website</a> by <a href="/users/login">logging as the admin user</a> you just created.<br>
<br>
<em>Don't forget, you still need to manually:</em>
<ol>
<li>Set up the <strong><a href="https://members.phppennyauction.com/link.php?id=8" target="_blank">cron jobs</a></strong></li>
<li>Delete this  folder (<strong>/install-data/</strong>)</li>
</ol></p>
</div>




<?php
exit;


function createTmpDir($dir) {
if (is_dir($dir)) {
//already exists, just permission it
	if (!is_writable($dir) && !chmod($dir, 0777)) {
			fatalInstallError('Could not permission tmp dir: '.$dir);
		}
	} else {
		if (!@mkdir($dir) || !chmod($dir, 0777)) {
			fatalInstallError('Could not create/permission tmp dir: '.$dir);
		}
	}
}

function updateProgress($value, $message) {
	sleep(1);
	?>
	<script type="text/javascript">
	$("#progressbar").progressbar({ value: <?=$value ?> });
	$("p#progress_message").html('<?= addslashes($message) ?>');
	</script>
	<?php
	flush();
}

function hashPass($string, $type = null, $salt = false) {
	if ($salt) {
		if (is_string($salt)) {
			$string = $salt . $string;
		}
	}

	$type = strtolower($type);

	if ($type == 'sha1' || $type == null) {
		if (function_exists('sha1')) {
			$return = sha1($string);
			return $return;
		}
		$type = 'sha256';
	}

	if ($type == 'sha256' && function_exists('mhash')) {
		return bin2hex(mhash(MHASH_SHA256, $string));
	}

	if (function_exists('hash')) {
		return hash($type, $string);
	}
	return md5($string);
}

function fatalInstallError($message) {
	?>
	<script type="text/javascript">
	$("p#progress_message").html('<span style="color:red">ERROR!</span>');
	</script>
	
	<p><strong>A Fatal Error Has Occurred</strong></p>
	<p>Installation cannot continue. Please contact your host, or open a ticket at the <a href="https://members.phppennyauction.com" target="_blank">phpPennyAuction Support Center</a> for assistance.</p>
	<p>ERROR: <?= $message ?></p>
	
	<?php
	exit;
}

?>
