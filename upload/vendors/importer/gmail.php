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
 *  gmail.com importer class
 */
class gmail extends Importer {
	
	function gmail () {
		$this->setEmailAddress('@gmail.com');
		$this->setName('GMail');
		$this->init();
		$this->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MEGAUPLOAD 2.0)";		
	}
	
	function run($login, $password) {

		$this->generateCookie ('gmail');
		$this->login = urlencode($login);
		$this->password = urlencode($password);		
		
		curl_setopt($this->curl, CURLOPT_URL,"http://www.gmail.com");
		curl_setopt($this->curl, CURLOPT_REFERER, "");
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);	
	  	$html = curl_exec($this->curl);	  	
	  	curl_close ($this->curl);
	  	$location = "https://www.google.com/accounts/ServiceLoginAuth?service=mail";		
		$matches = array();

		preg_match_all('/<input type\="hidden"[^>]*name\="([^"]+)"[^>]*value\="([^"]*)"[^>]*>/', $html, $matches);		
		
		$values = $matches[2];
		$params = "";
		
		$i=0;
		foreach ($matches[1] as $name)
		{
		  $params .= "$name=" . urlencode($values[$i]) . "&";
		  ++$i;
		}
		
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL,$location);		
		curl_setopt($this->curl, CURLOPT_REFERER, "");
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params ."Email=".$this->login."&Passwd=".$this->password."&PersistentCookie=");
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);	
		$html = curl_exec($this->curl);	
		
		
		if (eregi("Username and password do not match", $html))
		{
			curl_close ($this->curl);
			unlink($this->cookie_file);
			return 1;
		}
		$this->curl = curl_init();
		$location = "https://mail.google.com/mail/?ui=html&zy=f";
		curl_setopt($this->curl, CURLOPT_URL, $location);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);		
		$html = curl_exec($this->curl);
		curl_close ($this->curl);
		$location = "https://mail.google.com/mail/contacts/data/export?groupToExport=&exportType=ALL&out=HTML&exportEmail=true";
		$this->curl = curl_init();		
		curl_setopt($this->curl, CURLOPT_URL, $location);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);		
		$result = curl_exec($this->curl);
		curl_close ($this->curl);
		unlink($this->cookie_file);
		
		$result = str_replace("\n", "", $result);
		$result = str_replace("\r", "", $result);

		if (!empty($result))
		{
				$csv = array();
				preg_match_all('|<tr><td>(.*?)</td><td>(.*?)</td></tr>|', $result, $csv);
				//preg_match_all('|(.*?),(.*?),|', $result, $csv);
				$users = $csv[1];
				$emails = $csv[2];
				$returnNames = array();
				$returnEmails = array();
				
				foreach ( $users as $id => $user ) {
					$user = (empty($user) || ($user == '-')) ? $emails[$id] : $user;
					$returnNames[] = $user;
					$returnEmails[] = $emails[$id];
					
				}
				return array($returnNames, $returnEmails);
		} 
		else
		{
				return array();
		}
	}
}
?>