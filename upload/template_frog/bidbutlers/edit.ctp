<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Edit BidBuddies');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<fieldset>
				<?php echo $form->create('Bidbutler', array('action' => 'edit/'.$bidbutler['Auction']['id']));?>
				<legend><?php __('Edit My Bid Butler for Auction:');?> <a href="/auctions/view/<?php echo $bidbutler['Auction']['id']; ?>"><?php echo $bidbutler['Auction']['Product']['title']; ?></a></legend>

				<?php if(Configure::read('App.bidButlerType') == 'simple') : ?>
					<p><?php __('Please Note: Your bids will be used when the counter reaches 30 seconds.');?></p>
				<?php endif; ?>

				<?php
					echo $form->input('id');
					if(Configure::read('App.bidButlerType') == 'simple') :
					echo $form->input('bids', array('label' => __('Number of Bids to use *', true)));;
					endif;
					if(Configure::read('App.bidButlerType') == 'advanced') :
					echo $form->input('minimum_price', array('label' => 'Minimum Price *'));
					echo $form->input('maximum_price', array('label' => 'Maximum Price *'));
					echo $form->input('bids', array('label' => 'Number of Bids to use *'));
					endif;
				?>
				<?php echo $form->end(__('Save Changes', true));?>
			</fieldset>

			<div class="actions">
				<ul>
					<li><?php echo $html->link(__('<< Back to my bid butlers', true), array('action' => 'index'));?></li>
				</ul>
			</div>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>
