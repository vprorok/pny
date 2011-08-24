<?php
/*=============================================================================
|| ##################################################################
||	contact importers 
|| ##################################################################
||	
||	Copyright		: (C) 2007-2008 adluo.com
||	Contact			: support@adluo.com
||
||	- all of source code and files are protected by Copyright Laws. 
||
|| ##################################################################
=============================================================================*/
/**
 *  Base importer class
 */
class Importer {
	/**
    * Private
    * $login webmail login
    */
    var $login;
	/**
    * Private
    * $password webmail password
    */
    var $password;
    
    /**
    * Private
    * $emailAddress webmail email address
    */
    var $emailAddress;
    
    /**
    * Private
    * $name webmail name
    */
    var $name;
    
   
    /**
    * Private
    * $cookie_array cookie array
    */
    var $curl;
    
    /**
    * Private
    * $cookie_file cookie file
    */
    var $cookie_file;
    
    /**
    * Private
    * $cookie_path cookie file path
    */
    var $cookie_path;
    
    var $agent;

	
	function init() {
		global $_CONFIG;		
		$this->curl = curl_init();		
		$this->cookie_path = $_CONFIG['COOKIE_DIR'];//set up cookie path
		$this->agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";		
	}
	
	/**
    * Abstract function implemented by children
    * @return void
    */    
    function run($login, $password) {
	
	}
	
	/**
	* get email address
	* @return String
	*/
	function getEmailAddress() {
		return $this->emailAddress;
	}
	
	/**
    * Assigns a value to email address
    * @param $emailAddress email address
    * @return void
	*/
	function setEmailAddress ($emailAddress) {
        $this->emailAddress=$emailAddress;
    }
    
    /**
	* get name
	* @return String
	*/
	function getName() {
		return $this->name;
	}
	
	/**
    * Assigns a value to name
    * @param $name name
    * @return void
	*/
	function setName ($name) {
        $this->name=$name;
    }
    
    function generateCookie ($name) {    	
        srand((double)microtime()*1000000);
		$counter_id = rand(0,1000);
		if (!is_dir($this->cookie_path)) {
			@mkdir($this->cookie_path);
			@chmod($this->cookie_path, 0777);
		}
		$this->cookie_file = $this->cookie_path."/cookie_".$name."_".$counter_id;		
		$fileHandle = fopen($this->cookie_file, 'w') or die("can't open file, please check access right for cookie path! ");
		fclose($fileHandle);		
    }
	
}

function trimvals($val)
{
  return trim ($val, '" ');
}

?>