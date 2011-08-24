<?php
class DotpayHelper extends Helper{
    function submit($data = array(), $submit_name = 'Pay Using Dotpay'){
        $output  = '<form name="frmDotpay" id="frmDotpay" action="https://ssl.dotpay.eu" method="post">';
        foreach($data as $name => $value){
            $output .= '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
        }
        $output .= '<input type="submit" value="'.$submit_name.'" />';
        $output .= '</form>';

        return $this->output($output);
    }
}
?>
