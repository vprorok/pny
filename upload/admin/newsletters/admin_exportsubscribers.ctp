<?php
header("Content-type:application/vnd.ms-excel");
header("Content-disposition:attachment;filename=newsletter-users.csv");
?>
"Username","First Name","Last Name","Email"
<?php
    foreach($users as $user){
        echo '"'.$user['User']['username'].'",';
        echo '"'.$user['User']['first_name'].'",';
        echo '"'.$user['User']['last_name'].'",';
        echo '"'.$user['User']['email'].'"'."\n";
    }
?>