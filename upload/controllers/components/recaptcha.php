<?php
App::import('Vendor', 'recaptcha'.DS.'recaptchalib');

class RecaptchaComponent extends Object{
    var $public_key;
    var $private_key;
    var $error;

    function __construct(){
        parent::__construct();

        // get the key
        $config = Configure::read('Recaptcha');
        if(!empty($config['enabled'])) {
	        if(!empty($config['public_key'])){
	            $this->public_key = $config['public_key'];
	        }else{
	            die('Recaptcha need the public key to work. Please check configuraiton.');
	        }
	
	        if(!empty($config['private_key'])){
	            $this->private_key = $config['private_key'];
	        }else{
	            die('Recaptcha need the private key to work. Please check configuraiton.');
	        }
        }
    }

    function isValid(){
        if(empty($_POST["recaptcha_challenge_field"]) ||
           empty($_POST["recaptcha_response_field"])){

            return false;
        }
        
        $response = recaptcha_check_answer($this->private_key,
                                           $_SERVER["REMOTE_ADDR"],
                                           $_POST["recaptcha_challenge_field"],
                                           $_POST["recaptcha_response_field"]);
        if($response->is_valid){
            return true;
        }else{
            $this->error = $response->error;
            return false;
        }
    }

}
?>