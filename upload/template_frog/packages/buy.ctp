<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('My Account');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
				$html->addCrumb(__('Purchase Bids', true), '/packages');
				$html->addCrumb(__('Purchase', true), '/packages/buy/'.$package['Package']['id']);
				echo $this->element('crumb_user');
				?>
				
				<h1><?php __('Purchase Bids');?></h1>
				
				<p>
					<?php echo sprintf(__('You are about to purchase the bid package called %s for a price of %s', true),
					'<strong>'.$package['Package']['name'].'</strong>',
					'<strong>'.$number->currency($package['Package']['price'], $appConfigurations['currency']).'</strong>');?>
				</p>
				<?php if(!empty($paypalData)):?>
					<?php echo $paypal->submit(__('Purchase this package', true), $paypalData);?>
				<?php else:?>
					<?php echo $form->create('Package', array('url' => '/packages/buy/'.$package['Package']['id']));?>
					<?php echo $form->hidden('id', array('value' => $package['Package']['id'])); ?>
					<?php echo $form->end(__('Purchase this package', true)); ?>
				<?php endif;?>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>