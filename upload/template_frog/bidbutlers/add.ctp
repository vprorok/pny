<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Add BidBuddies');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php echo $form->create(null, array('url' => '/bidbutlers/add/'.$auction['Auction']['id']));?>
				<fieldset>
					<legend><?php __('Add a Bid Butler for Auction');?> <a href="/auctions/view/<?php echo $auction['Auction']['id']; ?>"><?php echo $auction['Product']['title']; ?></a></legend>
					<p><?php __('Your bids will be used when the counter reaches');?> <?php echo Configure::read('Settings.bid_butler_time'); ?> <?php __('seconds.');?></p>
			
					<?php if($appConfigurations['bidButlerType'] == 'advanced') : ?>
						<p><?php __('The minimum price must be at least');?> <?php echo $number->currency(0.01, $appConfigurations['currency']);?> <?php __('higher than the current bid.');?></p>
					<?php endif; ?>
				<?php
					if($appConfigurations['bidButlerType'] == 'advanced') :
						echo $form->input('minimum_price', array('label' => __('Minimum Price *', true)));
						echo $form->input('maximum_price', array('label' => __('Maximum Price *', true)));
					endif;
					echo $form->input('bids', array('label' => __('Number of Bids to use *', true)));
				?>
				</fieldset>
			<?php echo $form->end(__('Add a Bid Buddy >>', true));?>
			<div class="actions">
				<ul>
					<li><?php echo $html->link(__('<< Back to the auction', true), array('controller' => 'auctions', 'action' => 'view', $auction['Auction']['id']));?></li>
					<li><?php echo $html->link(__('<< Back to my bid butlers', true), array('action' => 'index'));?></li>
				</ul>
			</div>
						</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>
