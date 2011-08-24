<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Categories'); ?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<?php if(!empty($categories)) : ?>
				<?php echo $this->element('categories'); ?>
			<?php else: ?>
				<p><?php echo $j['no_categories']; ?></p>
			<?php endif; ?>
		</div>
		<br class="clear_l">
		<div class="crumb_bar">
			<?php
			$html->addCrumb(__('Categories', true), '/categories');
			echo $this->element('crumb_auction');
			?>
		</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>