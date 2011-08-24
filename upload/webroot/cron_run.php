<?php
log_msg('Launching cron_run');

if (!function_exists('pcntl_fork')) {
	$err='Can\'t load pcntl functions.';
	log_msg($err);
	exit($err);
	
}


//load PHPPA configuration
require('../config/config.php');

//old routes or new?
$routes=file_get_contents('../config/routes.php');
if (strstr($routes, 'dcleaner')) {
	$new_routes=true;
} else {
	$new_routes=false;
}
unset($routes);



$processes=array(	'bidbutler',
			'extend',
			'close',
			'winner',
			'cleaner');

$pids=array();

foreach ($processes as $process) {
	$pid = pcntl_fork();
	if ($pid == -1) {
		log_msg("ERROR! Process fork failed for ".$process);
	} else if ($pid) {
		//parent
		$pids[]=$pid;
		continue;
	} else {
		//child
		log_msg("Running $process");
		
		if (preg_match('/^(winner|cleaner)$/', $process)) {
			//*** cake daemons
			
			$url=$config['App']['url'].$config['App']['base']."/".( $new_routes  ? '/d'.$process : '/daemons/'.$process );
			
			if (function_exists('curl_init')) {
				//we can use curl
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$fil = curl_exec($ch);
				if (curl_error($ch)) {
					log_msg("cURL failure on ".$process.": ".curl_error($ch));
				}
				curl_close($ch);
				unset($fil);
				
				
			} elseif (ini_get('allow_url_fopen')) {
				//we can use built in file_get_contents()
				
				$fil=file_get_contents($url);
				if ($fil===FALSE) {
					log_msg("file_get_contents() failure on ".$process);
				}
				unset($fil);
			} else {
				log_msg("ERROR: curl module not found and allow_url_fopen set to false. Can't run $process process.");
			}
			
		} else {
			//standard daemons
			$_REQUEST['type']=$_GET['type']=$process;
			require('daemons.php');
		}
		
		exit;
	}
	
	
}


//loop through until all children are finished
log_msg("Waiting for children to finish...");

foreach ($pids as $pid) {
	pcntl_waitpid($pid, $status);
	if (pcntl_wifexited($status)) {
		log_msg('Process '.$pid.': normal exit'); 
	} else {
		log_msg('Process '.$pid.': WARNING: abnormal process termination ('.pcntl_wexitstatus($status).')');
	}
}






function log_msg($msg) {
	//echo $msg."";
	ob_flush();
	
	$pf=@fopen('../tmp/logs/cron.log', 'a');
	@fwrite($pf, date("Y-m-d H:i:s").": ".$msg."\n");
	@fclose($pf);
}

?>
