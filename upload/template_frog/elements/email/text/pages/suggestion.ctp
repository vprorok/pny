<?php echo sprintf(__('Dear %s', true), $appConfigurations['name']);?>

<?php __('The suggestion form has been submitted at your website.  Here are the details');?>:

<?php __('Name');?>: <?php echo $data['Page']['name']; ?>

<?php __('Email Address');?>: <?php echo $data['Page']['email']; ?>

<?php if(!empty($data['Department']['name'])) : ?>
	<?php __('Department');?>: <?php echo $data['Department']['name']; ?>
<?php endif; ?>

<?php if(!empty($data['Page']['phone'])) : ?>
	<?php __('Phone Number');?>: <?php echo $data['Page']['phone']; ?>
<?php endif; ?>

<?php __('Suggestion');?>:
<?php echo $data['Page']['message']; ?>
