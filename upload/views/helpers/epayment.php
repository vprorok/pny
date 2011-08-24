<?php
class EpaymentHelper extends Helper{

    var $excludedFromHash = array('language', 'key');

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

    function submit($submit_name = 'Pay Online', $data = array(), $merchant_key = null){
        $hash   = '';
        $output = '<form action="https://secure.epayment.ro/order/lu.php" method="post" name="frmOrder">';

        /**
         * Merchant identifier
         * Required
         */
        if(!empty($data['merchant'])){
            $output .= '<input name="MERCHANT" type="hidden" id="MERCHANT" value="'.$data['merchant'].'" />';
        }else{
            return $this->output('ePayment helper: Merchant is required');
        }

        /**
         * Order reference
         * Optional
         */
        if(!empty($data['order_ref'])){
            $output .= '<input name="ORDER_REF" type="hidden" value="'.$data['order_ref'].'" />';
        }

        /**
         * Order date
         * Format: yyyy-mm-dd hh:ii:ss
         * Required
         */
        if(!empty($data['order_date'])){
            $output .= '<input name="ORDER_DATE" type="hidden" value="'.$data['order_date'].'" />';
        }else{
            return $this->output('ePayment helper: order date is required');
        }

        /**
         * Order list
         * Required, at least one item
         */
        if(!empty($data['orders'])){
            foreach($data['orders'] as $key => $order){

                /**
                 * Order name
                 * Required
                 */
                if(!empty($order['name'])){
                    $output .= '<input name="ORDER_PNAME[]" type="hidden" value="'.$order['name'].'" />';
                }else{
                    return $this->output('ePayment helper: order name for item #'.$key.' is required.');
                }

                /**
                 * Order code
                 * Max 20 character
                 * Required
                 */
                if(!empty($order['code'])){
                    $output .= '<input name="ORDER_PCODE[]" type="hidden" value="'.$order['code'].'" />';
                }else{
                    return $this->output('ePayment helper: order code for item #'.$key.' is required.');
                }

                /**
                 * Additional order info
                 * Optional
                 */
                if(!empty($order['info'])){
                    $output .= '<input name="ORDER_PINFO[]" type="hidden" value="'.$order['info'].'" />';
                }

                /**
                 * Order price
                 * Required
                 */
                if(!empty($order['price'])){
                    $output .= '<input name="ORDER_PRICE[]" type="hidden" value="'.$order['price'].'" />';
                }else{
                    return $this->output('ePayment helper: order price for item #'.$key.' is required.');
                }

                /**
                 * Order quantity
                 * Required
                 */
                if(!empty($order['qty'])){
                    $output .= '<input name="ORDER_QTY[]" type="hidden" value="'.$order['qty'].'" />';
                }else{
                    return $this->output('ePayment helper: order qty for item #'.$key.' is required.');
                }

                /**
                 * Order value added tax
                 * Required
                 */
                if(!empty($order['vat']) || $order['vat'] == 0){
                    $output .= '<input name="ORDER_QTY[]" type="hidden" value="'.$order['vat'].'" />';
                }else{
                    return $this->output('ePayment helper: order vat for item #'.$key.' is required.');
                }
            }
        }

        /**
         * Order shipping cost
         * Required
         */
        if(!empty($data['order_shipping'])){
            $output .= '<input name="ORDER_SHIPPING" type="hidden" value="'.$data['order_shipping'].'" />';
        }

        /**
         * The currency can be RON, EUR, or USD
         * Optional, default is RON
         */
        if(!empty($data['prices_currency'])){
            $output .= '<input name="PRICES_CURRENCY" type="hidden" value="'.$data['prices_currency'].'" />';
        }

        /**
         * Order discount
         * Optional
         */
        if(!empty($data['discount'])){
            $output .= '<input name="DISCOUNT" type="hidden" value="'.$data['discount'].'" />';
        }

        /**
         * Destination city
         * Optional
         */
        if(!empty($data['destination_city'])){
            $output .= '<input name="DESTINATION_CITY" type="hidden" value="'.$data['destination_city'].'" />';
        }

        /**
         * Destination state
         * Optional
         */
        if(!empty($data['destination_state'])){
            $output .= '<input name="DESTINATION_STATE" type="hidden" value="'.$data['destination_state'].'" />';
        }

        /**
         * Destination country
         * Optional
         */
        if(!empty($data['destination_country'])){
            $output .= '<input name="DESTINATION_COUNTRY" type="hidden" value="'.$data['destination_country'].'" />';
        }

        /**
         * Language
         * Optional
         */
        if(!empty($data['language'])){
            $output .= '<input name="LANGUAGE" type="hidden" value="'.$data['language'].'" />';
        }

        /**
         * Test order
         * Optional
         */
        if(!empty($data['testorder'])){
            $output .= '<input name="TESTORDER" type="hidden" value="'.$data['testorder'].'" />';
        }

        /**
         * Processing order hash
         */
        foreach($data as $name => $value){
            if(!in_array($name, $this->excludedFromHash)){
                if($name == 'orders'){
                    foreach($value as $subkey => $subvalue){
                        foreach($subvalue as $subsubkey => $subsubvalue){
                            $hash_length = strlen($subsubvalue);
                            $hash .= $hash_length . $subsubvalue;
                        }
                    }
                }else{
                    $hash_length = strlen($value);
                    $hash .= $hash_length . $value;
                }
            }
        }

        if(empty($merchant_key)){
            return $this->output('ePayment helper: merchant key is required');
        }

        $hmac_md5 = $this->hmac($merchant_key, $hash);

        $output .= '<input name="ORDER_HASH" type="hidden" value="'.$hmac_md5.'" />';
        $output .= '<div class="submit"><input type="submit" name="Submit" value="'.$submit_name.'" /></div>';
        $output .= '</form>';

        return $this->output($output);
    }
}
?>
