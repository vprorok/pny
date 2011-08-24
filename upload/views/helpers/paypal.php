<?php
    class PaypalHelper extends Helper{
        function submit($buttonName = 'Submit', $data = array()){
            $result = '<form id="frmPaypal" name="frmPaypal" action="'. $data['url'] . '" method="post">';
            foreach($data as $name => $value){
                if($data != 'url'){
                    $result .= '<input type="hidden" name="'. $name . '" value="'. $value .'" />';
                }
            }
            $result .= '<div class="submit"><input type="submit" value="'. $buttonName . '" /></div>';
            $result .= '</form>';

            return $this->output($result);
        }
    }
?>
