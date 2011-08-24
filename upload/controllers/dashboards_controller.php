<?php
	class DashboardsController extends AppController{
		var $name = 'Dashboards';
		var $uses = array('Bid', 'Account');
		
		
		function beforeFilter(){
			parent::beforeFilter();
			
			if(!empty($this->Auth)) {
				$this->Auth->allow('template_switch');
			}
		}
		
		function _getOnlineUsers(){
			$dir   = TMP . DS . 'cache' . DS;
			
			$files = scandir($dir);
			$count = 0;
			
			foreach($files as $filename){
				if(is_dir($dir . $filename)){
					continue;
				}
				
				if(substr($filename, 0, 16) == 'cake_user_count_') {
					$count++;
				}
			}
			
			return $count;
		}
		
		function _income($type, $past = false, $options = null) {
			if($type == 'days') {
				$date = date('Y-m-d');
				
				if($past == true) {
				$date = date('Y-m-d', time() - 86400 * $options);
				}
				
				if($options == 1) {
					$income = $this->Account->find('all', array('conditions' => "Account.created BETWEEN '$date 00:00:00' AND '$date 23:59:59'", 'fields' => "SUM(Account.price) as 'income'"));
					
					if(empty($income[0][0]['income'])) {
						$income[0][0]['income'] = 0;
					}
				} else {
					$past = date('Y-m-d', strtotime($date) - 86400 * $options);
					
					$income = $this->Account->find('all', array('conditions' => "Account.created BETWEEN '$past 00:00:00' AND '$date 23:59:59'", 'fields' => "SUM(Account.price) as 'income'"));
					if(empty($income[0][0]['income'])) {
						$income[0][0]['income'] = 0;
					}
				}
			} elseif($type == 'month') {
				$lastDay = date('t');
				$month = date('m');
				$year = date('y');
				
				$rollback = date('d') + 1;
				
				if($past == true) {
					$lastDay = date('t', time() - 86400 * $rollback);
					$month = date('m', time() - 86400 * $rollback);
					$year = date('y', time() - 86400 * $rollback);
				}
				
				$income = $this->Account->find('all', array('conditions' => "Account.created BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-$lastDay 23:59:59'", 'fields' => "SUM(Account.price) as 'income'"));
				if(empty($income[0][0]['income'])) {
					$income[0][0]['income'] = 0;
				}
			}
			
			return $income[0][0]['income'];
		}
		
		function admin_dbupgrade() {
			
		}
		
		function admin_index(){
			//execute any hooks
			eval(PluginManager::hook('controllers_dashboards_adminindex_top'));
			
			
			//*** Check database version
			
			$version=$this->Setting->findByName('phppa_version', array('Setting.value'));	//load fresh in case old version # is cached
			$version=@$version['Setting']['value'];
			
			if (!$version) {
				$this->log('Using unversioned database 2.4.0 or before', 'upgrade');
				$this->__saveDBVersion();
				
			} elseif ($version!=PHPPA_VERSION) {
				//Are they using an older version?
				if ($version=='2.4.1') {
					$this->log('Upgrading from 2.4.1 to 2.4.2 DB', 'upgrade');
					//2.4.1, drop Gender and UserAddressType tables
					$this->Setting->updateQuery("DROP TABLE `genders`");
					$this->Setting->updateQuery("DROP TABLE `user_address_types`");
					
					//settings table change
					$this->Setting->updateQuery("ALTER TABLE `settings` ADD `type` TINYINT NOT NULL AFTER `description`");
					$this->Setting->updateQuery("ALTER TABLE `settings` ADD `options` VARCHAR( 255 ) NOT NULL AFTER `type`");
					if (Configure::read('Settings.site_live')=='no') $site_live=0; else $site_live=1;
					$this->Setting->updateQuery("UPDATE `settings` SET `type`='".SETTING_TYPE_YESNO."', `value`='$site_live' WHERE `name`='site_live'");
					$this->Setting->updateQuery("INSERT INTO `settings` (`name`,`value`,`description`,`type`) VALUES ('home_page_future_auctions', '1', 'Show future auctions on the home page?', '4')");
					
					//add autobidders config setting
					$this->Setting->create();
					$this->Setting->save(array(	'name'=>'autobidders',
										'value'=>'0',
										'type'=>SETTING_TYPE_ONOFF,
										'options'=>'',
										'description'=>__('Turn on to allow site testing using autobidders.', true)));
					
					//auctions table changes
					$this->Setting->updateQuery("ALTER TABLE `auctions` ADD `free` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `penny`");
					$this->Setting->updateQuery("ALTER TABLE `products` DROP `free`");
					
					$this->__saveDBVersion();
					
					list($errors, $successes)=$this->_clearDir(TMP);
					$this->log('Cleared TMP cache: '.count($errors).' errors, '.$successes.' successes', 'upgrade');
					list($errors, $successes)=$this->_clearDir(TMP . DS . 'cache');
					$this->log('Cleared TMP/CACHE cache: '.count($errors).' errors, '.$successes.' successes', 'upgrade');	
					list($errors, $successes)=$this->_clearDir(TMP . DS . 'cache' . DS . 'models');
					$this->log('Cleared TMP/CACHE/MODELS cache: '.count($errors).' errors, '.$successes.' successes', 'upgrade');
					
					$this->redirect('/admin/dashboards/dbupgrade');
				} elseif ($version=='2.4.2') {
					$this->log('Upgrading from 2.4.2 to 2.5.0 DB', 'upgrade');
					exit('???');
				} else {
					$this->log('ERROR: I don\'t recognize your database version (DB '.$version.' vs. software '.PHPPA_VERSION.')', 'upgrade');
					$this->set('warn_version', true);
				}
			}
			
			$conditions = array(
				'Bid.debit >' => 0,
				'User.autobidder' => 0,
				'Auction.id <>' => 0
			);
			$this->set('bids', $this->Bid->find('all', array('conditions' => $conditions, 'limit' => 10, 'contain' => array('Auction' => array('Product'), 'User'), 'order' => array('Bid.id' => 'desc'))));
			
			$this->set('onlineUsers', $this->_getOnlineUsers());
			
			$this->set('dailyIncome', $this->_income('days', false, 1));
			$this->set('yesterdayIncome', $this->_income('days', true, 1));
			
			$this->set('weeklyIncome', $this->_income('days', false, 7));
			$this->set('lastweekIncome', $this->_income('days', true, 7));
			
			$this->set('monthlyIncome', $this->_income('month'));
			$this->set('lastmonthIncome', $this->_income('month', true));
			
			$this->set('config', $this->appConfigurations);
			
			if (file_exists(APP . 'install-data') || file_exists(APP . 'setup.php')) {
				$this->set('installWarning', true);
			}
			
			//*** Check if we're running the latest
			if (($latest_version=Cache::read('latest_version', 'day'))===FALSE) {
				$latest_version=file_get_contents('http://members.phppennyauction.com/latest.txt');
				Cache::write('latest_version', $latest_version);
			}
			if ($latest_version) {
				$current_version=str_replace('.', '', PHPPA_VERSION);
				
				if ($latest_version>$current_version) {
					$this->set('upgrade_available', true);
				}
			}
		}
		
		function admin_clear_cache() {
		
			list($errors, $successes)=$this->_clearDir(TMP);
			$this->set('tmp_errors', $errors);
			$this->set('tmp_successes', $successes);
			
			list($errors, $successes)=$this->_clearDir(TMP . DS . 'cache');
			$this->set('cache_errors', $errors);
			$this->set('cache_successes', $successes);
			
			list($errors, $successes)=$this->_clearDir(TMP . DS . 'cache' . DS . 'models');
			$this->set('models_errors', $errors);
			$this->set('models_successes', $successes);
		
		}
		
		
		function _clearDir($dir) {
			
			$successes=0;
			$errors=array();
			
			$d = dir($dir);
			while (false !== ($entry = $d->read())) {
			if (preg_match("/^cake_/", $entry)) {
			$file=$dir . DS . $entry;
			unlink($file);
			if (file_exists($file)) {
			$errors[]='Could not delete: '.$file;
			} else {
			$successes++;
			}
			}
			}
			$d->close();
			
			return array($errors, $successes);
			
		
		}
		
		function admin_stats() {
			if (Configure::read('Stats') && Configure::read('Stats.enabled')===true) {
				$this->set('enabled', true);
			} else {
				$this->set('enabled', false);
			}
		}
		
		function template_switch($template) {
			if (Configure::read('SCD') && Configure::read('SCD.isSCD')===true) {
				$template_list=Configure::read('SCD.templates');
				if (!isset($template_list[$template])) {
					$this->Session->setFlash('Invalid template specified');
				} else {
					$this->Session->write('switch_template', $template);
				}
			}
			$this->redirect($this->referer());
		}
		
		function __saveDBVersion() {
			$setting=$this->Setting->findByName('phppa_version', array(	'Setting.id',
													'Setting.name',
													'Setting.description'));
			if (empty($setting)) {
				//create whole new record
				$setting=array('Setting'=>array(	'value'=>PHPPA_VERSION,
										'name'=>'phppa_version',
										'description'=>'Internal use only, modifying this value can cause software instability!'));
			} else {
				//just update value
				$setting['Setting']['value']=PHPPA_VERSION;
			}
			
			$this->Setting->create();
			$this->Setting->save($setting);
			$this->log('Saved new version number to settings table', 'upgrade');
		}
	}
?>