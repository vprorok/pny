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
 *  msn.com importer class
 */
class msn_mail extends Importer {
	var $curl_get;
	
	function msn_mail () {
		$this->setEmailAddress('@msn.com');
		$this->setName('msn');
		$this->init();		
	}
	
	function run($login, $password) {
		$this->generateCookie ('msn');
		$this->login = $login."@msn.com";
		$this->password = $password;		
		$this->curl_get = curl_init();
		
		curl_setopt($this->curl_get, CURLOPT_URL,"http://login.live.com/login.srf?id=2&vv=400&lc=1033");		
		curl_setopt($this->curl_get, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl_get, CURLOPT_REFERER, "");
		curl_setopt($this->curl_get, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl_get, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl_get, CURLOPT_USERAGENT, $this->agent);
		$html = curl_exec($this->curl_get);
		
		
		$matches = array();
		
		preg_match('/<form [^>]+action\="([^"]+)"[^>]*>/', $html, $matches);
		$opturl = $matches[1];
		preg_match_all('/<input type\="hidden"[^>]*name\="([^"]+)"[^>]*value\="([^"]*)">/', $html, $matches);
		$values = $matches[2];
		$params = "";		
		$i=0;
		foreach ($matches[1] as $name)
		{
		  $params .= "$name=" . urlencode($values[$i]);
		  ++$i;
		  if(isset($matches[$i]))
		  {
			$params .= "&";
		  }
		}		
		$params = trim ($params, "&");
		
		curl_setopt($this->curl, CURLOPT_URL, $opturl);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
	 	curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		$html = curl_exec($this->curl);
	
		$matches = array();
		preg_match('/<form [^>]+action\="([^"]+)"[^>]*>/', $html, $matches);
		$opturl = $matches[1];
		
		preg_match_all('/<input type="hidden"[^>]*name\="([^"]+)"[^>]*value\="([^"]*)"[^>]*>/', $html, $matches);
		$values = $matches[2];
		$params = "";
			
		$i=0;
		foreach ($matches[1] as $name)
		{
		  $paramsin[$name]=$values[$i];
		  ++$i;
		}
	
		$sPad="IfYouAreReadingThisYouHaveTooMuchFreeTime";
		$lPad=strlen($sPad)-strlen($this->password);
		$PwPad=substr($sPad, 0,($lPad<0)?0:$lPad);
		
		$paramsin['PwdPad']=urlencode($PwPad);
	
		foreach ($paramsin as $key=>$value)
		{
		  $params .= "$key=" . urlencode($value) . "&";
		}		
		
		curl_setopt($this->curl, CURLOPT_URL,$opturl);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params . "login=" . urlencode($this->login) . "&passwd=" . urlencode($this->password) . "&LoginOptions=3");
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		$html = curl_exec($this->curl);
		
			
		
		if((preg_match('/replace[^"]*"([^"]*)"/', $html, $matches)==0) && (preg_match("/url=([^\"]*)\"/si", $html, $matches)==0 || eregi("password is incorrect", $html)))
		{
	  		curl_close ($this->curl);
			unlink($this->cookie_file);
	  		return 1;
		}
		$this->curl = curl_init();		
		curl_setopt($this->curl, CURLOPT_URL,$matches[1]);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);	
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);	
		$html = curl_exec($this->curl);		
		$info = curl_getinfo($this->curl);
		
		
		if(eregi("MessageAtLogin.aspx", $info['url']))//alert
		{			
			preg_match('/<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="([^"]+)" \/>/', $html, $matches);			
			$event = $matches[1];			
			preg_match('/<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="([^"]+)" \/>/', $html, $matches);			
			$view = $matches[1];			
			$params = "__VIEWSTATE=".urlencode($view)."&TakeMeToInbox=Continue&__EVENTVALIDATION=".urlencode($event);			
			curl_setopt($this->curl, CURLOPT_URL,$info['url']);
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($this->curl, CURLOPT_POST, 1);
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
			curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
			curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
			$html = curl_exec($this->curl);
		}
		else if (eregi("hotmail.msn.com/", $info['url']))//old version
		{
			$base_url = "http://mail.live.com";
			curl_setopt($this->curl, CURLOPT_URL,$base_url);
		    	curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		    	curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		    	curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		    	curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);
		    	$html = curl_exec($this->curl);
		}
		preg_match('/self.location.href = \'(.*?)\';/si', $html, $matches);
		$url_redirect=urldecode(str_replace('\x', '%',$matches[1]));
		$bulkStringArray=explode("/mail",$url_redirect);
		$base_url = $bulkStringArray[0];
		$url = $base_url.'/mail/EditMessageLight.aspx?n=';
		curl_setopt($this->curl, CURLOPT_URL,$url);
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);		
		$html = curl_exec($this->curl);
		preg_match('/ContactList.aspx(.*?)\"/si', $html, $matches);
		
		if(isset($matches[1])){
			$url = $base_url.'/mail/ContactList.aspx'.$matches[1];
			curl_setopt($this->curl, CURLOPT_URL,$url);
			curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);		
			$html = curl_exec($this->curl);
			
			$url = $base_url.'/mail/logout.aspx';
			curl_setopt($this->curl, CURLOPT_URL,$url);
			curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent);
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookie_file);
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie_file);		
			curl_exec($this->curl);
			curl_close ($this->curl);
			unlink($this->cookie_file);
			
			$bulkStringArray=explode("['",$html);
			unset($bulkStringArray[0]);
			unset($bulkStringArray[count($bulkStringArray)]);
			$contacts=array();
			foreach($bulkStringArray as $stringValue)
			{
					$stringValue=str_replace(array("']],","'"),'',$stringValue);
					if (strpos($stringValue,'0,0,0,')!==false) 
					{
						$tempStringArray=explode(',',$stringValue);
						if (!empty($tempStringArray[2])) 
							$name=html_entity_decode(urldecode(str_replace('\x', '%', $tempStringArray[2])),ENT_QUOTES, "UTF-8");
					}
					else
					{
							$emailsArray=array();
							$emailsArray=explode('\x26\x2364\x3b',$stringValue);
							if (count($emailsArray)>0) 
							{
								//get all emails
								$bulkEmails=explode(',',$stringValue);
								if (!empty($bulkEmails)) 
								foreach($bulkEmails as $valueEmail)
								{ 
									$email=html_entity_decode(urldecode(str_replace('\x', '%', $valueEmail))); 
									if(!empty($email)) 
									{ 
										$contacts[$email]=array('first_name'=>(!empty($name)?$name:""),'email_1'=>$email);
										$email=false; 
									} 
								}
								$name=false;	
							}	
					}
			}
			foreach ($contacts as $email=>$name) if (!$this->isEmail($email)) unset($contacts[$email]);
			$newemails = array();
			$newnames = array();
			foreach($contacts as $key=>$value)
			{
				$newemails[] = $key;	
				$newnames[] = $value['first_name'];
			}
	  		return array($newnames, $newemails);		
		}else{
			return array();
		}
	}
	function isEmail($email)
	{
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email);
	}		
		
}
?>