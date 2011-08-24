<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Buy This Auction Now:');?> <?php echo $auction['Product']['title']; ?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<p><?php __('You are about to purchase this auction now for:');?> <?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></p>
			
			<p>
				<?php echo $form->create('Auction', array('url' => '/auctions/buy/'.$auction['Auction']['id']));?>
				<?php echo $form->hidden('id', array('value' => $auction['Auction']['id'])); ?>
				<?php echo $form->end(__('Purchase this Auction Now >>', true)); ?>
			</p>
			
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>