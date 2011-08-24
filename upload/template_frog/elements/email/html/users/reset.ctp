<p><?php echo sprintf(__('Hi %s', true), $data['User']['first_name']);?>,</p>

<p><?php echo sprintf(__('You or someone pretending to be you have requested that your account details be reset at %s.', true), $appConfigurations['name']);?>.</p>

<p><?php __('Your login details are');?>:<br />
<?php __('Username');?>: <?php echo $data['User']['username'];?><br />
<?php __('Password');?>: <?php echo $data['User']['before_password'];?>
</p>

<p><?php __('Please change your password immediately after entering the application.');?></p>

<p><?php __('Enjoy the services!');?></p>

<p><?php __('Thank You');?><br/>
<?php echo $appConfigurations['name'];?></p>

<p><?php __('If you never registered at our website, please contact the administrator by submitting the form on our contact page.');?></p>
