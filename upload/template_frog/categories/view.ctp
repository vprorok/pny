<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php echo $category['Category']['name']; ?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content" style="padding:20px 20px 20px 30px !important;">
			<?php if(!empty($categories)) : ?>
				<?php if(!empty($auctions)) : ?>
				<h2><?php __('Subcategories'); ?></h2>
				<?php endif; ?>
				<?php echo $this->element('categories'); ?>
			<?php endif; ?>
			
			<?php if(!empty($auctions)) : ?>
				<?php echo $this->element('auctions'); ?>
				<?php echo $this->element('pagination'); ?>
			<?php elseif(empty($categories)) : ?>
				<div class="align-center off_message"><p><?php __('There are no auctions in this category at the moment.');?></p></div>
			<?php endif; ?>
		</div>
		<br class="clear_l">
		<div class="crumb_bar">
			<?php
			$html->addCrumb(__('Categories', true), '/categories');
			if(!empty($parents)) :
				foreach($parents as $parent) :
					$html->addCrumb($parent['Category']['name'], '/categories/view/'.$parent['Category']['id']);
				endforeach;
			endif;
			echo $this->element('crumb_auction');
			?>
		</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>