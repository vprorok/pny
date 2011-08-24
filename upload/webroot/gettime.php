<?php
    // Include the config
    require_once '../config/config.php';

    // Do not change any line below
    if(!empty($config['App']['timezone'])){
        putenv("TZ=".$config['App']['timezone']);
    }

    $format = "F d, Y H:i:s";
    if(!empty($_GET['format'])){
        $format = strip_tags($_GET['format']);
    }
    echo date($format);
?>