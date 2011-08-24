<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Rewards'); ?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			
			<?php
				if($session->check('Auth.User')){
					$points = $this->requestAction('/users/points');
					echo sprintf(__('You currently have <strong>%d</strong> points.', true), $points);
				}
			?>
			
			<?php if($paginator->counter() > 0):?>
			
				<ul class="horizontal-bid-list category-list">
					<?php foreach($rewards as $reward):?>
					<li>
						<div class="align-center">
							<h3><?php echo $html->link($reward['Reward']['title'], array('action' => 'view', $reward['Reward']['id'])); ?></h3>
							<div class="thumb"><?php echo $html->image('rewards/thumbs/'.$reward['Reward']['image']); ?></div>
							<?php __('Points');?>
							<div class="big-price"><?php echo number_format($reward['Reward']['points']); ?></div>
			
							<?php __('Retail Price');?>
							<div class="bold"><?php echo $number->currency($reward['Reward']['rrp'], $appConfigurations['currency']); ?></div>
							<div>
								<?php echo $html->link(__('Purchase', true), array('action' => 'purchase', $reward['Reward']['id']));?>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
				</ul>
				<?php echo $this->element('pagination');?>
			<?php else:?>
				<div class="align-center off_message"><p><?php __('There are no rewards available at the moment');?></p></div>
			<?php endif;?>
		</div>
		<br class="clear_l">
		<div class="crumb_bar">
			<?php
			$html->addCrumb(__('Rewards', true), '/rewards');
			echo $this->element('crumb_auction');
			?>
		</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>