<div id="ending-soon" class="box">
	<div class="f-top clearfix"><h2><?php __('Won Auctions'); ?></h2>
	<p>See who's won what in the list below. All ended auctions here.</p></div>
	<div class="f-repeat clearfix">
		<div class="content" style="padding:20px 20px 20px 30px !important;">
			
			<?php if(!empty($auctions)) : ?>
				<?php echo $this->element('auctions'); ?>
				<?php echo $this->element('pagination'); ?>
			<?php else: ?>
				<div class="align-center off_message"><p><?php __('There are no won auctions at the moment.', true);?></p></div>
			<?php endif; ?>
		</div>
	<br class="clear_l">
	<div class="crumb_bar">
			<?php
			$html->addCrumb(__('Winners', true), '/auctions/winners');
			echo $this->element('crumb_auction');
			?>
	</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>
