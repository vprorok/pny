<?php
class CouponType extends AppModel{
    var $name = 'CouponType';
    var $hasMany = array('Coupon');
}
?>