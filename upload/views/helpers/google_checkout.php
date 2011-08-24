<?php
class GoogleCheckoutHelper extends Helper{
    /**
     * Function to create a buy now button
     *
     * @param Array $data Data to passed to button (merchant_id, items(name, description, quantity, price, [currency=USD]))
     */
    function submit($data = array()){
        if(!empty($data)){
            if(empty($data['merchant_id'])){
                $this->log('Google Checkout Helper: merchant id is required.');
                return false;
            }

            if(empty($data['items'])){
                $this->log('Google Checkout Helper: you need to define at least 1 item in items array.');
                return false;
            }

            if(empty($data['local'])){
                $data['local'] = 'en_US';
            }

            if(empty($data['charset'])){
                $data['charset'] = 'utf-8';
            }

            if(!empty($data['sandbox'])){
                $result  = '<form action="https://sandbox.google.com/checkout/api/checkout/v2/checkoutForm/Merchant/'.$data['merchant_id'].'" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm">';
            }else{
                $result  = '<form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/'.$data['merchant_id'].'" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm">';
            }

            foreach($data['items'] as $index => $item){
                if(empty($item['name'])){
                    $this->log('Google Checkout Helper: item #'.($index + 1).' does not has name');
                    return false;
                }
                $result .= '<input name="item_name_'.($index + 1).'" type="hidden" value="'.$item['name'].'"/>';

                if(empty($item['description'])){
                    $this->log('Google Checkout Helper: item #'.($index + 1).' does not has description');
                    return false;
                }
                $result .= '<input name="item_description_'.($index + 1).'" type="hidden" value="'.$item['description'].'"/>';

                if(empty($item['quantity'])){
                    $item['quantity'] = 1;
                }
                $result .= '<input name="item_quantity_'.($index + 1).'" type="hidden" value="'.$item['quantity'].'"/>';

                if(empty($item['price'])){
                    $this->log('Google Checkout Helper: item #'.($index + 1).' does not has price');
                    return false;
                }
                $result .= '<input name="item_price_'.($index + 1).'" type="hidden" value="'.$item['price'].'"/>';

                if(empty($item['merchant_id'])){
                    $this->log('Google Checkout Helper: item #'.($index + 1).' does not has merchant id');
                    return false;
                }
                $result .= '<input name="item_merchant_id_'.($index + 1).'" type="hidden" value="'.$item['merchant_id'].'"/>';

                if(empty($item['currency'])){
                    if(empty($data['currency'])){
                        $data['currency'] = 'USD';
                    }
                }
                $result .= '<input name="item_currency_'.($index + 1).'" type="hidden" value="'.$item['currency'].'"/>';
            }

            $result .= '<input name="_charset_" type="hidden" value="'.$data['charset'].'"/>';
            $result .= '<input alt="" src="https://checkout.google.com/buttons/checkout.gif?merchant_id='.$data['merchant_id'].'&amp;w=180&amp;h=46&amp;style=white&amp;variant=text&amp;loc='.$data['local'].'" type="image"/>';

            $result .= '</form>';

            return $this->output($result);
        }

    }
}
?>