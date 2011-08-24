<?php echo sprintf(__('Dear %s', true), $appConfigurations['name']);?>,

<?php __('A user has just redeemed their rewards. Below are the details:');?>

<?php __('User Details');?>
<?php __('Email Address');?> : <?php echo $data['User']['email']; ?>
<?php __('Username');?> : <?php echo $data['User']['username']; ?>
<?php __('Name');?> : <?php echo $data['Address']['name']; ?>
<?php __('Address');?> : <?php echo $data['Address']['address_1']; ?><?php if(!empty($data['Address']['address_2'])) : ?>, <?php echo $data['Address']['address_2']; ?><?php endif; ?>
<?php __('Suburb / Town');?> : <?php if(!empty($data['Address']['suburb'])) : ?><?php echo $data['Address']['suburb']; ?><?php else: ?>n/a<?php endif; ?>
<?php __('City / State / County');?> : <?php echo $data['Address']['city']; ?>
<?php __('Postcode');?> : <?php echo $data['Address']['postcode']; ?>
<?php __('Country');?> : <?php echo $data['Country']['name']; ?>
<?php __('Phone Number');?> : <?php if(!empty($data['Address']['phone'])) : ?><?php echo $data['Address']['phone']; ?><?php else: ?>n/a<?php endif; ?>

<?php __('Reward');?>
<?php __('Title');?> : <?php echo $data['Reward']['title']; ?>
<?php __('Description');?> : <?php echo $data['Reward']['description']; ?>
<?php __('Points');?> : <?php echo $data['Reward']['points']; ?>