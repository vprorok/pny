<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Bidding Increments', true), '/admin/setting_increments');
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['SettingIncrement']['id']);
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
		echo $form->input('id');
		echo $form->input('bid_debit', array('label' => 'Bid Debit *'));
		echo $form->input('price_increment', array('label' => 'Price Increment *'));
		echo $form->input('time_increment', array('label' => 'Time Increment *'));
		if($appConfigurations['bidIncrements'] == 'dynamic') :
			echo $form->input('lower_price', array('value'=>number_format($this->data['SettingIncrement']['lower_price'],2, '.', '')));
			echo $form->input('upper_price', array('value'=>number_format($this->data['SettingIncrement']['upper_price'],2, '.', '')));
		endif;
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>
</div>

<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to bid increments', true), array('action'=>'index'));?></li>
	</ul>
</div>
