<p><?php echo sprintf(__('Hi %s', true), $data['User']['first_name']);?>,</p>

<p><?php __('In order to access your account and more features of our website, please follow the link below to activate your account:');?></p>

<p>
    <a href="<?php echo $data['User']['activate_link'];?>" title="Activate">
        <?php echo $data['User']['activate_link'];?>
    </a>
</p>

<p><?php __('Enjoy the services!');?></p>

<p><?php __('Thank You');?><br/>
<?php echo $appConfigurations['name'];?></p>

<p><?php __('If you never registered at our website, please contact the administrator by submitting the form on our contact page.');?></p>
