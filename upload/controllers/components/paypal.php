<?php
    /**
     * Paypal IPN CakePHP component based on Micah Carrick <email@micahcarrick.com>
     * http://www.micahcarrick.com paypal_class
     *
     * @author Maulana Kurniawan <maulana.kurniawan@gmail.com>
     * @license Copyleft Maulana Kurniawan
     */

    class PaypalComponent extends Object{
        // Variable for payment request send
        var $options = array(
            'url'           => 'https://www.paypal.com/cgi-bin/webscr',
            'cmd'           => '_xclick',
            'lc'            => '',
            'currency_code' => 'USD',
            'business'      => '',
            'item_name'     => '',
            'item_number'   => '',
            'amount'        => '',
            'currency_code' => '',
            'return'        => '',
            'notify_url'    => '',
            'no_shipping'   => '',
            'no_note'       => '',
            'first_name'    => '',
            'last_name'     => '',
            'address1'      => '',
            'address2'      => '',
            'city'          => '',
            'zip'           => '',
            'night_phone_a' => '',
            'email'         => '',
            'custom'        => ''
        );

        // Variable that need to be filled before payment request send
        var $options_required = array(
            'url', 'cmd', 'business', 'item_name',
            'item_number', 'amount', 'return'
        );

        // holds the last error encountered
        var $last_error;

        // holds the IPN response from paypal
        var $ipn_response;

        // array contains the POST values for IPN
        var $ipn_data = array();

        function startup(&$controller){

        }

        function __construct() {
            parent::__construct();
        }

        function getFormData(){
            // Variable to hold data which we want to send
            $data = array();

            // Run through options and put it in data
            // if it has value(not empty)
            foreach($this->options as $name => $value){
                if(!empty($value)){
                    $data[$name] = $value;
                }
            }

            // Check required parameter before sending data
            foreach($this->options_required as $name){
                if(array_key_exists($name, $data) == false){
                    $this->log('PaypalComponent *WARNING* '. $name .' data is required but not yet configured/filled', 'payment');
                    //return false;
                }
            }

            // Return data
            return $data;
        }

        // Configuring options for global use
        function configure($options = array()){
            foreach($options as $name => $value){
                $this->options[$name] = $value;
            }

            // Set last error if not configured by user
            if(empty($this->last_error)){
                $this->last_error = '';
            }

            // Set ipn response to empty string to prevent notice/warning
            // from php
            if(empty($this->ipn_response)){
                $this->ipn_response = '';
            }
        }

        // Function to validate the ipn
        function validate_ipn() {
            // parse the paypal URL
            $url_parsed = parse_url($this->options['url']);

            // generate the post string from the _POST vars aswell as load the
            // _POST vars into an arry so we can play with them from the calling
            // script.
            $this->ipn_data = $_POST;

            // Read the post from PayPal and add 'cmd'
            $post_string = 'cmd=_notify-validate';

            foreach ($_POST as $key => $value){
                // Handle escape characters
                if (get_magic_quotes_gpc()) {
			$value = urlencode(stripslashes($value));
		} else {
			$value = urlencode($value);
		}
                $post_string .= "&$key=$value";
            }

            if ($url_parsed['scheme'] == 'https'){
                $url_parsed['port'] = 443;
                $ssl = 'ssl://';
            } else {
                $url_parsed['port'] = 80;
                $ssl = '';
            }

            // open the connection to paypal
            $fp = fsockopen($ssl.$url_parsed['host'], $url_parsed['port'], $err_num, $err_str, 30);
            if(!$fp) {
                // could not open the connection.  If loggin is on, the error message
                // will be in the log.
                $this->last_error = "fsockopen error no. $err_num: $err_str";
                $this->log('Cannot open socket: '.$err_str, 'payment');

		if ($url_parsed['scheme'] == 'https'){
			$this->log('Trying non-ssl fsockopen() ovveride', 'payment');
				
			$url_parsed['port'] = 443;
			$ssl = 'ssl://';
			$fp = fsockopen($ssl.$url_parsed['host'], $url_parsed['port'], $err_num, $err_str, 30);
			if (!$fp) {
				$this->log('Still couldn\'t open the socket :(  Aborting.', 'payment');
				$this->log_ipn_results(false);
				return false;
			}
			
			
                } else {
                	$this->log_ipn_results(false);
                	return false;
                }
                
                
            }
                
            if ($fp) {

                // Post the data back to paypal
                fputs($fp, "POST ".$url_parsed['path']." HTTP/1.1\r\n");
                fputs($fp, "Host: ".$url_parsed['host']."\r\n");
                fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
                fputs($fp, "Content-length: ".strlen($post_string)."\r\n");
                fputs($fp, "Connection: close\r\n\r\n");
                fputs($fp, $post_string . "\r\n\r\n");

                // loop through the response from the server and append to variable
                while(!feof($fp)) {
                    $this->ipn_response .= fgets($fp, 1024);
                }

                fclose($fp); // close connection

                if(eregi("VERIFIED",$this->ipn_response)) {
                    $success = true;
                } else {
                    $success = false;
                }
            }

            if($success == true){
                // Valid IPN transaction.
                $this->log_ipn_results(true);
                return true;
            } else {
               // Invalid IPN transaction.  Check the log for details.
               $this->last_error = 'IPN Validation Failed.';
               $this->log_ipn_results(false);
               return false;
            }
        }

        function log_ipn_results($success) {

            // Success or failure being logged?
            if ($success){
                $text .= "SUCCESS!\n";
            }else{
                $text .= 'FAIL: '.$this->last_error."\n";
            }

            // Log the POST variables
            $text .= "IPN POST Vars from Paypal:\n";
            foreach ($this->ipn_data as $key=>$value) {
                $text .= "$key=$value, ";
            }

            // Log the response from the paypal server
            $text .= "IPN Response from Paypal Server: ".$this->ipn_response;

            // Write to log
            $this->log($text, 'payment');
        }
    }
?>
