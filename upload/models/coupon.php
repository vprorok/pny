<?php
class Coupon extends AppModel{
    var $name = 'Coupon';
    var $belongsTo = array('CouponType');
    
    function beforeSave(){
        if(!empty($this->data['Coupon']['code'])){
            $this->data['Coupon']['code'] = strtoupper($this->data['Coupon']['code']);
        }
        
        return true;
    }
}
?>