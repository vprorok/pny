<?php

//**************************************
//*** phpPennyAuction Plugin Manager ***
//**************************************

class PluginManager {
		
	private static $plugins;
	private static $hooks;
	
	function loadPlugins() {
		
		//initialize
		self::$plugins=array();
		self::$hooks=array();
		
		$plugins=glob(APP.'vendors'.DS.'plugins'.DS.'*', GLOB_ONLYDIR);
		foreach ($plugins as $plugin) {
			//load this plugin's info file
			
			
			$files=glob($plugin.DS.'*.php');
			if (FALSE==($info=self::loadInfo($plugin.DS.'phppa.inf'))) {
				//something's wrong with our info file, abort
				continue;
			}
			
			foreach ($files as $plug) {
				//load plug code contents
				$contents=file_get_contents($plug);
				
				//get base names
				$plug=basename($plug);
				$plug=strtolower(substr($plug, 0, strlen($plug)-4));
				$plugin=basename($plugin);
				$plugin=strtolower($plugin);
				
				self::createHook($plugin, $plug, $contents);
			}
			
			unset($info);
		}
			
	}
	
	private function loadInfo($filename) {
		$inf_file=@file_get_contents($filename);
		eval($inf_file);
		if (!isset($info) or empty($info)) {
			$this->log('BAD INFO FILE: '.$filename, 'plugins');
			return false;
		} else {
			//check that we're compatible
			if (PHPPA_VERSION<$info['min_version']) {
				$this->log($info['name'].' not compatible (min_version)', 'plugins');
				return false;
			} elseif (PHPPA_VERSION>$info['max_version']) {
				$this->log($info['name'].' not compatible (max_version)', 'plugins');
				return false;
			}
			
			return $info;
		}
	}


	//pushes a plugin to the hook stack
	private function createHook($plugin, $hook, $code) {
		if (!isset(self::$hooks[$hook][$plugin])) {
			self::$hooks[$hook][$plugin] = $code;
		}
	}

	//returns code for any plugins on the supplied $hook	
	public function hook($hook) {
		$hook=strtolower($hook);
		$content='';

		if (isset(self::$hooks[$hook])) {
			foreach (self::$hooks[$hook] as $plugin=>$hook) {
				$content .= $hook;
			}
		}
		
		return $content;
		
	}
}

?>