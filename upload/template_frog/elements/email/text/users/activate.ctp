<?php echo sprintf(__('Hi %s', true), $data['User']['first_name']);?>,

<?php __('In order to access your account and more features of our website, please follow the link below to activate your account:');?>

<?php echo $data['User']['activate_link'];?>

<?php __('Enjoy the services!');?>

<?php __('Thank You');?> 
<?php echo $appConfigurations['name'];?>

<?php __('If you never registered at our website, please contact the administrator by submitting the form on our contact page.');?>
