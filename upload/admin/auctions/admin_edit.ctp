<?php
$html->addCrumb('Manage Auctions', '/admin/auctions');
$html->addCrumb($product['Product']['title'], '/admin/products/edit/'.$product['Product']['id']);
$html->addCrumb('Edit Auction', '/admin/'.$this->params['controller'].'/edit/'.$this->data['Auction']['id']);
echo $this->element('admin/crumb');
?>

<div class="auctions form">
<?php echo $form->create('Auction', array('url' => '/admin/auctions/edit/'.$this->data['Auction']['id']));?>
	<fieldset>
 		<legend><?php echo sprintf(__('Edit an Auction for: %s', true), $product['Product']['title']);?></legend>
		<?php
			echo $form->input('id');
			echo $form->input('start_time', array('timeFormat' => '24+second', 'label' => __('Start Time *', true)));
			echo $form->input('end_time', array('timeFormat' => '24+second', 'label' => __('End Time *', true)));
			if(!empty($appConfigurations['autobids'])) {
				echo $form->input('max_end', array('id' => 'maxEnd', 'label' => __('** Max End Time - force this auction to close at a certain time.', true)));
			} else {
				echo $form->input('max_end', array('id' => 'maxEnd', 'label' => __('Max End Time - force this auction to close at a certain time.', true)));
			}
		?>
		<div id="maxEndTimeBlock" style="display: none">
			<?php echo $form->input('max_end_time', array('label' => __('** Max End Time', true), 'timeFormat' => '24+second'));?>
		</div>
		<?php
			echo $form->input('autolist', array('label' => __('Autolist this auction - it will be automatically listed once it closes.', true)));
			echo $form->input('featured', array('label' => __('Featured Auction - Show this auction on the home page.', true)));
			echo $form->input('peak_only', array('label' => __('Peak Auction - Only allow bidding on the auction during a set time.', true)));
			echo $form->input('nail_bitter', array('label' => __('Nail Biter - The Bid Butler cannot be used on this auction.', true)));
			echo $form->input('penny', array('label' => __('Penny Auction - This will make the bid increment increase by 0.01 each bid.', true)));
			echo $form->input('free', array('label' => __('Free Auction - Bids on this auction won\'t debit your customers\' accounts.', true)));
			echo $form->input('beginner', array('label' => __('Beginner Auction - Only new members can bid.', true)));
			echo $form->input('reverse', array('label' => __('Reverse Auction - Price starts at RRP and decreases with each bid.', true)));
			echo $form->input('active', array('label' => __('Active - show this auction on the website.', true)));
			if(!empty($appConfigurations['hiddenReserve'])) {
				echo $form->input('hidden_reserve');
			}
		?>

		<?php if(!empty($appConfigurations['autobids'])) : ?>
		<p><?php __('** If using the max end time, when the time is met, it will close the auction regardless if the minumum price has been met.');?></p>
		<?php endif; ?>

	</fieldset>
<?php echo $form->end(__('Save Changes >>', true));?>
</div>
<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to products', true), array('controller' => 'products', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('<< Back to auctions', true), array('action' => 'index'));?></li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#maxEnd').click(function(){
			if($('#maxEnd').attr('checked')){
				$('#maxEndTimeBlock').show(0);
			}else{
				$('#maxEndTimeBlock').hide(0);
			}
		});

		if($('#maxEnd').attr('checked')){
			$('#maxEndTimeBlock').show(0);
		}else{
			$('#maxEndTimeBlock').hide(0);
		}
	});
</script>
