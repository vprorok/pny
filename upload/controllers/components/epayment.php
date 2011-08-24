<?php
class EpaymentComponent extends Object{
    var $ipn_data;

    /**
     * RFC 2104 HMAC implementation for php.
     * Creates an md5 HMAC.
     * Eliminates the need to install mhash to compute a HMAC
     * Hacked by Lance Rushing
     */
    function hmac($key, $data){
        $b = 64; // byte length for md5
        if (strlen($key) > $b) {
            $key = pack("H*",md5($key));
        }

        $key    = str_pad($key, $b, chr(0x00));
        $ipad   = str_pad('', $b, chr(0x36));
        $opad   = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack("H*",md5($k_ipad . $data)));
    }

    function ipn(){
        // Get the parameter which server send
        $data = $_POST;

        $this->ipn_data = $data;

        $response  = '<EPAYMENT>';
        $response .= date('YmdHis');
        $response .= '|';

        $hash  = strlen($data['IPN_PID'][0]) . $data['IPN_PID'][0];
        $hash .= strlen($data['IPN_PNAME'][0]) . $data['IPN_PNAME'][0];
        $hash .= strlen($data['IPN_DATE']) . $data['IPN_DATE'];
        $hash .= strlen($data['DATE']) . $data['DATE'];

        $response .= $this->hmac(Configure::read('Security.salt'), $hash);
        $response .= '</EPAYMENT>';

        return $response;
    }
}
?>
