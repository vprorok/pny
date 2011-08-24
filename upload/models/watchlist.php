<?php
class Watchlist extends AppModel{
    var $name = 'Watchlist';
    
    var $belongsTo = array('Auction', 'User');
    
    var $actsAs = array('Containable');
}
?>