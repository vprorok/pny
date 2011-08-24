<?php
/**
 * Custom error handler which support themeable view
 */
class AppError extends ErrorHandler {
	function __construct($method, $messages) {
		$this->controller = new AppController();
		$params = Router::getParams();
		$appConfigurations = Configure::read('App');
		
		// Setup the view path using main theme
		$viewPath = 'errors';
		if($this->controller->view == 'Theme'){
			$viewPath = 'themed'.DS.$appConfigurations['theme'].DS.'errors';
		}
		
		// If debug equal to 0 then all error should be 404
		if(Configure::read('debug') == 0){
			$method = 'error404';
		}
		
		$checkView = VIEWS.$viewPath.DS.Inflector::underscore($method).'.ctp';
		if (file_exists($checkView)) {
			$this->controller->_set(Router::getPaths());
			$this->controller->viewPath  = $viewPath;
			$this->controller->theme     = $appConfigurations['theme'];
			$this->controller->pageTitle = __('Error', true);
			
			// Set the message to error page
			$this->controller->set('message', $messages[0]['url']);
			$this->controller->set('appConfigurations', $appConfigurations);
			
			$this->controller->render($method);
			e($this->controller->output);
		}else{
			parent::__construct($method, $messages);
		}
	}
	
	function error404($address) {
		//SCD mode
		if (Configure::read('SCD') && Configure::read('SCD.isSCD')===true) {
			if ($this->controller->Session->check('switch_template')) {
				Configure::write('App.theme', $this->controller->Session->read('switch_template'));
			}
		}
		
		//see if this file is in the theme webroot
		$theme=Configure::read('App.theme');
		
		$url=str_replace(array('&#45;', '/'), array('-', DS), $address['url']);
		
		$file='..'. DS .$theme . DS . 'webroot' . DS . $url;
		if (file_exists($file) && is_readable($file)) {
			//this is a theme file, dump its contents
			$this->_outFile($file);
		} else {
			if (strstr($file, DS.'admin')) {
				$file='..'. DS . 'admin' . DS . 'webroot' . DS . $url;
				if (file_exists($file) && is_readable($file)) {
					//this is a theme file, dump its contents
					if (preg_match('/.php$/', $file)) {
						require($file);
					} else {
						$this->_outFile($file);
					}
				}
				$file='..'. DS . 'admin' . DS . 'webroot' . DS . str_replace('admin'.DS, '', $url);
				if (file_exists($file) && is_readable($file)) {
					//this is a theme file, dump its contents
					if (preg_match('/.php$/', $file)) {
						require($file);
					} else {
						$this->_outFile($file);
					}
				}
			}
			parent::error404($address);
		}
	}
	
	function _outFile($file) {
		if (Configure::read('debug')==2) {
			Configure::write('debug', 1);
		}
		
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
	
	function missingController($address) {
		$this->error404($address);
	}
}
?>
