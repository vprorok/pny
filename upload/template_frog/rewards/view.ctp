<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Rewards'); ?> - <?php echo $reward['Reward']['title'];?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div>
				<?php
					if($session->check('Auth.User')){
						$points = $this->requestAction('/users/points');
						echo sprintf(__('You currently have <strong>%d</strong> points.', true), $points);
					}
				?>
			
				<p><?php echo $html->image('rewards/max/'.$reward['Reward']['image']); ?></p>
				<?php echo $reward['Reward']['description']; ?>
			
			
				<h3><?php __('RRP');?></h3>
				<?php echo $number->currency($reward['Reward']['rrp'], $appConfigurations['currency']); ?>
			
				<h3><?php __('Points Required');?></h3>
				<?php echo number_format($reward['Reward']['points']); ?>
			
				<div class="actions">
					<ul>
						<li><?php echo $html->link(__('< Back to Rewards', true), array('action'=>'index')); ?> </li>
					</ul>
				</div>
			</div>
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
