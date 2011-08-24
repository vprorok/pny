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
 *  aol.com importer class
 */
class aol extends Importer {
	
	function aol () {
		$this->setEmailAddress('@aol.com');
		$this->setName('AOL');
		$this->init();
		
		$this->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MEGAUPLOAD 2.0)";		
	}
	
	function run($login, $password) {
		$this->generateCookie ('aol');
		$this->login = $login;
		$this->password = $password;
		
		$this->login=(strpos($this->login,'@aol')!==false?str_replace('@aol.com','',$this->login):$this->login);
		
		curl_setopt($this->curl, CURLOPT_URL,"https://my.screenname.aol.com/_cqr/login/login.psp?sitedomain=sns.webmail.aol.com&lang=en&locale=us&authLev=0");
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_REFERER, "");
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		$html = curl_exec($this->curl);	
	
		preg_match('/<form name="AOLLoginForm".*?action="([^"]*).*?<\/form>/si', $html, $matches);
		$opturl = $matches[1];
		$hiddens = array();
		preg_match_all('/<input type="hidden" name="([^"]*)" value="([^"]*)".*?>/si', $matches[0], $hiddens);
		$hiddennames = $hiddens[1];
		$hiddenvalues = $hiddens[2];
		$hcount = count($hiddennames);
		$params = "";
		for($i=0; $i<$hcount; $i++)
		{
			$params .= $hiddennames[$i]."=".urlencode($hiddenvalues[$i])."&";
		}		
		curl_setopt($this->curl, CURLOPT_URL, "https://my.screenname.aol.com/_cqr/login/login.psp");
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params . "loginId=".($this->login)."&password=".($this->password) );
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);		
		$html = curl_exec($this->curl);	
		if(!preg_match("/'loginForm', 'false', '([^']*)'/si", $html, $matches))
		{
		    curl_close ($this->curl);
		    unlink($this->cookie_file);	
		    return 1;
		};
		curl_close ($this->curl);
		$this->curl = curl_init();	
		curl_setopt($this->curl, CURLOPT_URL,$matches[1]);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		//curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1");
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		$html = curl_exec($this->curl);
		if(!preg_match("/var gSuccessPath = \"(.*?)\";/si", $html, $matches))
		{
			curl_close ($this->curl);
		    	unlink($this->cookie_file);	
		    	return 1;
		};
		if(preg_match("/var gPreferredHost = \"(.*?)\";/si", $html, $matches1))
		{
			$hosting ="http://".$matches1[1];
		}else{
			$hosting ="http://webmail.aol.com";	
		}
		$url_redirect=$hosting.$matches[1];
		$url_redirect=str_replace("Suite.aspx","Lite/Today.aspx",$url_redirect);
		curl_close ($this->curl);
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL,$url_redirect);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_REFERER, "");
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		$html = curl_exec($this->curl);
		
		//curl_close ($this->curl);
		/*
		$uid = "";
		$data = file($this->cookie_file);
		for ($x=0; $x<count($data); $x++) {
	            $line = trim($data[$x]);
	            
	            $pos = strpos($line, "&uid:");
	            if($pos){
	            	$line = substr($line, $pos+1,strlen($line)-$pos); 
	            	$pos2 = strpos($line, "&"); 
	            	$uid = substr($line, 4,$pos2-4); 
	            	break;            	
	            }
		}
		$this->curl = curl_init();
		*/
		curl_close ($this->curl);
		$this->curl = curl_init();
		$home = str_replace("Today.aspx","",$url_redirect);
		curl_setopt($this->curl, CURLOPT_URL,$home."ContactList.aspx?folder=Inbox&showUserFolders=False");
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_REFERER, "");
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1");
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		$html = curl_exec($this->curl);		
		preg_match('/<input type="hidden" name="user" value="(.*?)" \/>/', $html, $matches);
		if(isset($matches[1])){
			$uid = $matches[1];
		}
			
		curl_setopt($this->curl, CURLOPT_URL,$home."addresslist-print.aspx?command=all&sort=FirstLastNick&sortDir=Ascending&nameFormat=FirstLastNick&user=".$uid);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_REFERER, "");
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1");
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		$html = curl_exec($this->curl);	
		curl_close ($this->curl);
		unlink($this->cookie_file);
				
		preg_match_all('/<span class="fullName">(.*?)<\/span>/', $html, $matches);
		$names = $matches[1];		
		preg_match_all('/<span>Email 1:<\/span> <span>(.*?)<\/span>/', $html, $matches);
		$emails = $matches[1];	
	  	return array($names, $emails);
		
	}
		
}


?>