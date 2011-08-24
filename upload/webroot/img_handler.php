<?php

define(DS, '/');
require('..' . DS . 'config' . DS . 'config.php');


$theme=$config['App']['theme'];

$url=$_GET['arg'];

$url=str_replace(array('&#45;', '/'), array('-', DS), $url);
		
$main_file="." . DS .$url;
$sub_file='..'. DS .$theme . DS . 'webroot' . DS . $url;

if (file_exists($main_file) && is_readable($main_file)) {
	//this is a main webroot file, dump its contents
	outFile($main_file);
	
} elseif (file_exists($sub_file) && is_readable($sub_file)) {
	//this is a theme file, dump its contents
	outFile($sub_file);
	
} else {
	if (strstr($sub_file, DS.'admin')) {
		$file='..'. DS . 'admin' . DS . 'webroot' . DS . $url;
		if (file_exists($file) && is_readable($file)) {
			//this is a theme file, dump its contents
			if (preg_match('/.php$/', $file)) {
				require($file);
			} else {
				outFile($file);
			}
		}
		$file='..'. DS . 'admin' . DS . 'webroot' . DS . str_replace('admin'.DS, '', $url);
		if (file_exists($file) && is_readable($file)) {
			//this is a theme file, dump its contents
			if (preg_match('/.php$/', $file)) {
				require($file);
			} else {
				outFile($file);
			}
		}
	}
	error404();
}

function error404() {
	header("Error: 404");
	exit('404 File Not Found');
}
	
function outFile($file) {
	
	if (preg_match('/css$/i', $file)) {
		//mime_content_type() mishandles css on some installations
		$content_type='text/css';
	} elseif (function_exists('mime_content_type')) {
		$content_type=mime_content_type($file);
	} elseif (preg_match('/js$/i', $file)) {
		$content_type='application/javascript';
	} elseif (preg_match('/jpg$/i', $file)) {
		$content_type='image/jpeg';
	} elseif (preg_match('/gif$/i', $file)) {
		$content_type='image/gif';
	} elseif (preg_match('/png$/i', $file)) {
		$content_type='image/png';
	} elseif (preg_match('/svg$/i', $file)) {
		$content_type='image/svg';
	}
	error_reporting(0);
	$junk=ob_get_clean();
	
	header("Content-type: ".$content_type);
	readfile($file);
	exit;
}
?>