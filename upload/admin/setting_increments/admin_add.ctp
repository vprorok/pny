<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Bidding Increments', true), '/admin/setting_increments');
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="settings form">
<?php echo $form->create('SettingIncrement');?>
	<fieldset>
 		<legend><?php __('Bid Increments'); ?></legend>
 		
 		<?php if($appConfigurations['bidIncrements'] == 'dynamic') : ?>
 			<p><?php __('For the price increment, set the lower price and upper price for the pricing range.  This will be taken off from the current price.');?></p>
 			<p><?php __('If you do not wish to set the lower or upper price, set the value for the lower or upper range as 0.');?></p>
 		<?php endif; ?>
 		
	<?php
		echo $form->input('bid_debit',  array('label' => 'Bid Debit <span class="required">*</span> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Bid Debit</span><span class="HelpToolTip_Contents" style="display:none;">The number of bids in the customer account that you wanted to be used ("debited") with each bid. Recommended to set to 1.</span></span>'));
		echo $form->input('price_increment', array('label' => 'Price Increment <span class="required">*</span> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Price Increment</span><span class="HelpToolTip_Contents" style="display:none;">The amount that you want the bid price to increase by, with each new bid that is placed.</span></span>'));
		echo $form->input('time_increment', array('label' => 'Time Increment <span class="required">*</span> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Time Increment</span><span class="HelpToolTip_Contents" style="display:none;">The amount of time that you want to be added to the auction timer (countdown) with each bid, in seconds.</span></span>'));
		if($appConfigurations['bidIncrements'] == 'dynamic') :
			echo $form->input('lower_price');
			echo $form->input('upper_price');
		endif;
	?>
	</fieldset>
<?php echo $form->end(__('Add Bid Increment', true));?>
</div>

<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to bid increments', true), array('action'=>'index'));?></li>
	</ul>
</div>
