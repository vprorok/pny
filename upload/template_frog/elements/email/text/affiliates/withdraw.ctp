<?php echo sprintf(__('Dear %s', true), $appConfigurations['name']);?>,

<?php echo sprintf(__('The user %s has put in a request to withdraw affiliate funds.', true), $data['User']['username']);?>

<?php __('The details are as follows:');?>

<?php __('Paypal Email Address:');?> <?php echo $data['Affiliate']['email']; ?>
<?php __('Amount to Pay:');?> <?php echo $number->currency($data['Affiliate']['debit'], $appConfigurations['currency']); ?>