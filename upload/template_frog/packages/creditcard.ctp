<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Purchase Bids');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<p>
					<?php echo sprintf(__('You are about to purchase the bid package called %s for a price of %s', true),
					'<strong>'.$package['Package']['name'].'</strong>',
					'<strong>'.$number->currency($package['Package']['price'], $appConfigurations['currency']).'</strong>');?>
				</p>
				<?php echo $form->create('Package', array('url' => '/packages/creditcard/'.$package['Package']['id']));?>
					<fieldset>
						<legend><?php __('Personal Information');?></legend>
						<?php echo $form->input('buyer.first', array('label' => __('First Name', true))); ?>
						<?php echo $form->input('buyer.last', array('label' => __('Last Name', true))); ?>
						<?php echo $form->input('buyer.address1', array('label' => __('Address', true))); ?>
						<?php echo $form->input('buyer.address2', array('label' => '')); ?>
						<?php echo $form->input('buyer.city'); ?>
						<?php echo $form->input('buyer.state', array('label' => __('State / County', true))); ?>
						<?php echo $form->input('buyer.zip', array('label' => __('Zip / Postcode', true))); ?>
						<?php echo $form->input('buyer.country', array('type' => 'select', 'options' => $countries)); ?>
					</fieldset>
					<fieldset>
						<legend><?php __('Credit Card');?></legend>
						<?php echo $form->input('cc.type', array('type' => 'select', 'options' => $ccTypes));?>
						<?php echo $form->input('cc.number');?>
						<?php echo $form->input('cc.expiration', array('label' => 'Exp. date (mm/yyyy)')); ?>
						<?php echo $form->input('cc.cvv2', array('label' => 'CVV2')); ?>
						<?php echo $form->input('cc.issue', array('label' => 'Issue Number (for Switch/Solo only)')); ?>
						<?php echo $form->input('cc.owner.first', array('label' => __('First Name', true))); ?>
						<?php echo $form->input('cc.owner.last', array('label' => __('Last Name', true))); ?>
					</fieldset>
				<?php echo $form->end(__('Purchase this package', true)); ?>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>