<p><?php echo sprintf(__('Dear %s', true), $appConfigurations['name']);?></p>

<p><?php __('The contact form has been submitted at your website.  Here are the details');?>:</p>

<p><?php __('Name');?>: <?php echo $data['Page']['name']; ?></p>

<p><?php __('Email Address');?>: <?php echo $data['Page']['email']; ?></p>

<?php if(!empty($data['Department']['name'])) : ?>
	<p><?php __('Department');?>: <?php echo $data['Department']['name']; ?></p>
<?php endif; ?>

<?php if(!empty($data['Page']['phone'])) : ?>
	<p><?php __('Phone Number');?>: <?php echo $data['Page']['phone']; ?></p>
<?php endif; ?>

<p><?php __('Enquiry');?>:<br />
<?php echo nl2br($data['Page']['message']); ?></p>
