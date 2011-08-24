<div id="ending-soon" class="box">
	<div class="f-top clearfix"><h2><?php __('Your Search for:'); ?> <?php echo $search; ?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">

			<?php if(!empty($auctions)) : ?>
				<?php echo $this->element('auctions'); ?>
				<?php echo $this->element('pagination'); ?>
			<?php else: ?>
				<p><?php __('Your search returned no results, please try again.');?></p>
			<?php endif; ?>

		</div>
	<br class="clear_l">
	<div class="crumb_bar">
<?php
$html->addCrumb(__('Your Search', true), '/auctions/search/'.$search);
echo $this->element('crumb_auction');
?>
	</div>
	</div>
	<div class="f-bottom clearfix">&nbsp;</div>
</div>
