<?php echo $html->css('admin/style'); ?>

<div id="container">
	<?php
	if($session->check('Message.flash')) {
		$session->flash();
	}
	?>
	
	<?php echo $form->create('Product', array('url' => '/admin/products/help'));?>
	
	<fieldset>
		<legend><?php __('Calculate the Minimum Price');?></legend>
	
		<?php
			echo $form->input('start_price', array('label' => 'Start Price (usually set to zero)'));
			echo $form->input('cost_price', array('label' => 'Cost Price (The cost the product cost you)'));
			echo $form->input('profit', array('label' => 'Profit (the money you want to make on this auction.)'));
			echo $form->input('bid_cost', array('label' => 'Cost per Bid (the minimum cost per bid for a user)'));
			echo $form->input('bid_increment', array('label' => 'Bid Increment (the amount the price will increase per bid)'));
		?>
	
		<?php echo $form->end(__('Calculate >>', true));?>
	</fieldset>
	
	<?php if(!empty($min_price)) : ?>
		<div id="flashMessage" class="message">
			The minimum price should be set to <?php echo $number->currency($min_price, $appConfigurations['currency']); ?>
		</div>
	<?php endif; ?>
</div>