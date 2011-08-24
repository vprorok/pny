<p><?php echo sprintf(__('Dear %s', true), $appConfigurations['name']);?>,</p>

<p><?php echo sprintf(__('The user %s has put in a request to withdraw affiliate funds.', true), $data['User']['username']);?></p>

<p><?php __('The details are as follows:');?></p>

<p>
	<?php __('Paypal Email Address:');?> <?php echo $data['Affiliate']['email']; ?><br />
	<?php __('Amount to Pay:');?> <?php echo $number->currency($data['Affiliate']['debit'], $appConfigurations['currency']); ?>
</p>