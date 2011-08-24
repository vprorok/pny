<div id="ending-soon" class="box">
	<div class="f-top clearfix"><h2><?php __('Live Auctions'); ?></h2>
	<p>List of live auctions</p></div>
	<div class="f-repeat clearfix">
		<div class="content" style="padding:20px 20px 20px 30px !important;">
			<?php if(!empty($auctions)) : ?>
				<?php echo $this->element('auctions'); ?>
				<?php echo $this->element('pagination'); ?>
			<?php else: ?>
				<div class="align-center off_message"><p><?php __('There are no live auctions at the moment.');?></p></div>
			<?php endif; ?>
		</div>
	<br class="clear_l">
	<div class="crumb_bar">
			<?php
			$html->addCrumb(__('Live Auctions', true), '/auctions');
			echo $this->element('crumb_auction');
			?>
	</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>
