<?php
class Reminder extends AppModel{
    var $name = 'Reminder';

    var $belongsTo = array('Auction', 'User');
}
?>