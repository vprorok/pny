<?php echo sprintf(__('Hi %s', true), $data['User']['first_name']);?>,

<?php echo sprintf(__('You or someone pretending to be you have requested that your account details be reset at %s.', true), $appConfigurations['name']);?>.

<?php __('Your login details are');?>:
<?php __('Username');?>: <?php echo $data['User']['username'];?>

<?php __('Password');?>: <?php echo $data['User']['before_password'];?>

<?php __('Please change your password immediately after entering the application.');?>

<?php __('Enjoy the services!');?>

<?php __('Thank You');?>
<?php echo $appConfigurations['name'];?>

<?php __('If you never registered at our website, please contact the administrator by submitting the form on our contact page.');?>
