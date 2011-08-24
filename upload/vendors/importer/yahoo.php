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
 *  mail.yahoo.com importer class
 */
class yahoo extends Importer {
	
	function yahoo () {
		$this->setEmailAddress('@yahoo.com');
		$this->setName('Yahoo Mail');
		$this->init();		
	}
	
	function run($login, $password) {
		$this->generateCookie ('yahoo');
		$this->login = urlencode($login);
		$this->password = urlencode($password);
		
		curl_setopt($this->curl, CURLOPT_URL,"http://address.yahoo.com");
		curl_setopt($this->curl, CURLOPT_REFERER, "");
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);	
		$html = curl_exec($this->curl);
				
		$matches = array();		
		preg_match_all('/<input type\="hidden" name\="([^"]+)" value\="([^"]*)">/', $html, $matches);
		$values = $matches[2];
		$params = "";
		
		$i=0;
		foreach ($matches[1] as $name)
		{
		  $params .= "$name=$values[$i]&";
		  ++$i;
		}		
		
		curl_setopt($this->curl, CURLOPT_URL,"https://login.yahoo.com/config/login?");
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_REFERER, "");
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params . "login=".$this->login."&passwd=".$this->password);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		
		curl_exec($this->curl);
		
		curl_setopt($this->curl, CURLOPT_URL, "http://address.mail.yahoo.com/?_src=&VPC=tools_print");
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, ".src=&VPC=print&field[allc]=1&field[catid]=0&field[style]=quick&submit[action_display]=Display for Printing");
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		$html = curl_exec($this->curl);
		
		preg_match_all('/<tr class="phead">[^>]*<td>(.*?)<\/td>[^>]*<\/tr>/is', $html, $matches);
		$names = $matches[1];
		preg_match_all('/<div class="first">([^"]+)<\/div>([^"]+)<div class="last">([^"]+)<\/div>/', $html, $matches);
		$emails = $matches[1];
		
		$returnnames = array();
		$returnemails = array();
		
		for($i=0; $i<count($emails); ++$i)
		{				
			$email = trim(str_replace('<div>','',str_replace('</div>','',$emails[$i])));
			if(!empty($email)){
				$returnemails[] =$email;
				if(preg_match("/<b>(.*?)<\/b>/si", $names[$i], $matches1))
				{
					$returnnames[] =$matches1[1];
				}else{
					$returnnames[] ="";
				}
			}
		}
		return array($returnnames, $returnemails);
	}
	
	
}

?>