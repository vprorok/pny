<?php
class PaypalProUkComponent extends Object
{
	// Configuration for this Component
	var $config;

	function process($data = array()) {
		if(empty($this->config)){
			$this->log('PaypalProUk Component : You need to set the configuration first before calling process');
			return false;
		}

		$paymentType 		= 'Sale';
		$firstName 			= urlencode($data['cc']['owner']['first']);
		$lastName 			= urlencode($data['cc']['owner']['last']);
		$email 				= urlencode($data['buyer']['email']);
		$creditCardType 	= urlencode($data['cc']['type']);
		$creditCardNumber 	= urlencode($data['cc']['number']);
		$expiration 		= explode('/', $data['cc']['expiration']);
		if(empty($expiration[1])) {
			$expiration[1] = null;
		}

		$expireDate			= $expiration[0].$expiration[1];
		$cvv2Number 		= urlencode($data['cc']['cvv2']);
		$issueNumber 		= urlencode($data['cc']['issue']);
		$address1 			= urlencode($data['buyer']['address1']);
		$address2 			= urlencode($data['buyer']['address2']);
		$city 				= urlencode($data['buyer']['city']);
		$state 				= urlencode($data['buyer']['state']);
		$zip 				= urlencode($data['buyer']['zip']);
		$countryCode		= urlencode($data['buyer']['country']);
		$currencyCode		= Configure::read('App.currency');

		// lets check that the required variables are there!
		if(empty($firstName)) {
			return 'FAILURE';
		}
		elseif(empty($lastName)) {
			return 'FAILURE';
		}
		elseif(empty($email)) {
			return 'FAILURE';
		}
		elseif(empty($address1)) {
			return 'FAILURE';
		}
		elseif(empty($city)) {
			return 'FAILURE';
		}
		elseif(empty($state)) {
			return 'FAILURE';
		}
		elseif(empty($creditCardNumber)) {
			return 'FAILURE';
		}
		elseif(empty($expiration)) {
			return 'FAILURE';
		}

		$amount = number_format($data['price'], 2);
		$nvpstr = "&EXPDATE=$expireDate&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&CVV2=$cvv2Number&IssueNumber=$issueNumber&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$countryCode&CURRENCYCODE=$currencyCode&EMAIL=$email";
		$resArray = $this->hash_call("doDirectPayment",$nvpstr);

		return strtoupper($resArray["ACK"]);
	}

	function hash_call($methodName, $nvpStr) {
		if(empty($this->config)){
			$this->log('PaypalProUk Component : You need to set the configuration first before calling process');
			return false;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->config['endpoint']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);

		if(!empty($this->config['use_proxy'])){
			curl_setopt ($ch, CURLOPT_PROXY, $this->config['proxy_host'].":".$this->config['proxy_port']);
		}

		$nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($this->config['version'])."&PWD=".urlencode($this->config['password'])."&USER=".urlencode($this->config['username'])."&SIGNATURE=".urlencode($this->config['signature']).$nvpStr;
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
		$response = curl_exec($ch);
		$nvpResArray=$this->deformatNVP($response);
		$nvpReqArray=$this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;

		if (curl_errno($ch)) {
			$_SESSION['curl_error_no']=curl_errno($ch);
			$_SESSION['curl_error_msg']=curl_error($ch);
		} else {
			curl_close($ch);
		}

		return $nvpResArray;
	}

	function deformatNVP($nvpstr) {
		$intial=0;
	 	$nvpArray = array();

		while(strlen($nvpstr)){
			$keypos= strpos($nvpstr,'=');
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	    }
		return $nvpArray;
	}
}
?>