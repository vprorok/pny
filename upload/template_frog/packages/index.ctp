<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Packages');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
				$html->addCrumb(__('Packages', true), '/packages');
				echo $this->element('crumb_user');
				?>


				<?php if(!empty($packages)) : ?>

				<?php if(Configure::read('App.coupons')):?>
					<?php if($coupon = Cache::read('coupon_user_'.$session->read('Auth.User.id'))):?>
						<?php echo sprintf(__('Coupon code applied : %s', true), $coupon['Coupon']['code']);?>
						(<?php echo $html->link(__('Remove Coupon', true), array('action' => 'removecoupon'));?>)
					<?php else:?>
						<p><?php __('If you have a coupon or discount code enter it in below to receive a discount.');?></p>
						<fieldset>
							<legend></legend>
							<?php echo $form->create('Package', array('action' => 'applycoupon'));?>
							<?php echo $form->input('Coupon.code', array('label' => __('Coupon Code:', true)));?>
							<?php echo $form->end(__('Apply Coupon', true));?>
						</fieldset>
					<?php endif;?>
				<?php endif;?>

				<?php echo $this->element('pagination'); ?>

				<table class="results" cellpadding="0" cellspacing="0">
				<tr>
					<th><?php echo $paginator->sort('name');?></th>
					<th><?php echo $paginator->sort('Number of Bids', 'bids');?></th>
					<th><?php echo $paginator->sort('price');?></th>
					<th class="actions"><?php __('Options');?></th>
				</tr>
				<?php
				$i = 0;
				foreach ($packages as $package):
					$class = null;
					if ($i++ % 2 == 0) {
						$class = ' class="altrow"';
					}
				?>
					<tr<?php echo $class;?>>
						<td>
							<?php echo $package['Package']['name']; ?>
						</td>
						<td>
							<?php echo $package['Package']['bids']; ?>
						</td>
						<td>
							<?php echo $number->currency($package['Package']['price'], $appConfigurations['currency']); ?>
						</td>
						<td class="actions">
						
							<?php if(Configure::read('Paypal.email')<>'') : ?>
								<?php echo $html->link(__('Purchase Using Paypal', true), array('controller' => 'payment_gateways', 'action' => 'paypal', 'package', $package['Package']['id'])); ?><br>
							<?php endif; ?>
							<?php if(Configure::read('PaymentGateways.GoogleCheckout.merchant_id')) : ?>
								<?php echo $html->link(__('Purchase Using Google Checkout', true), array('controller' => 'payment_gateways', 'action' => 'google_checkout', 'package', $package['Package']['id'])); ?><br>	
							<?php endif; ?>
							<?php if(Configure::read('PaypalProUk.username')) : ?>
								<?php if(Configure::read('debug') == 0) : ?>
									<?php echo $html->link(__('Purchase Using Credit Card', true), str_replace('http://', 'https://', $appConfigurations['url']).'/packages/creditcard/'.$package['Package']['id']); ?><br>
								<?php else: ?>
									<?php echo $html->link(__('Purchase Using Credit Card', true), array('action' => 'creditcard', $package['Package']['id'])); ?>
								<?php endif; ?>
							<?php endif; ?>
							<?php if(Configure::read('PaymentGateways.Paybox.active')) : ?>
								<?php echo $html->link(__('Purchase Using PayBox', true),'/payment_gateways/paybox/package/'.$package['Package']['id']); ?><br>
							<?php endif; ?>
							<?php if(Configure::read('PaymentGateways.Usaepay.active')) : ?>
								<?php echo $html->link(__('Purchase Using USA ePay', true),'/payment_gateways/usaepay/package/'.$package['Package']['id']); ?><br>
							<?php endif; ?>
							<?php if(Configure::read('PaymentGateways.Nbepay.active')) : ?>
								<?php echo $html->link(__('Purchase Using NBePay', true),'/payment_gateways/nbepay/package/'.$package['Package']['id']); ?><br>
							<?php endif; ?>

							<?php if(Configure::read('PaymentGateways.iPay88.active')) : ?>
								<?php echo $html->link(__('Purchase Using iPay88', true),'/payment_gateways/ipay88/package/'.$package['Package']['id']); ?><br>
							<?php endif; ?>

							<?php if(Configure::read('PaymentGateways.Pagseguro.active')) : ?>
								<?php echo $html->link(__('Purchase Using Pagseguro', true),'/payment_gateways/pagseguro/package/'.$package['Package']['id']); ?><br>
							<?php endif; ?>

							<?php if(Configure::read('PaymentGateways.Firstdata.active')) : ?>
								<?php echo $html->link(__('Purchase Using Firstdata', true),'/payment_gateways/firstdata/package/'.$package['Package']['id']); ?><br>
							<?php endif; ?>

							<?php if(Configure::read('PaymentGateways.AuthorizeNet.login')) : ?>
								<?php echo $html->link(__('Purchase Using Credit Card', true),'/payment_gateways/authorizenet/package/'.$package['Package']['id']); ?><br>
							<?php endif; ?>

							<?php if(Configure::read('PaymentGateways.Dotpay.id')) : ?>
								<?php echo $html->link(__('Purchase Using Dotpay', true), array('controller' => 'payment_gateways', 'action' => 'dotpay', 'package', $package['Package']['id'])); ?><br>
							<?php endif;?>
							
							<?php if(Configure::read('PaymentGateways.iDeal.layout')) : ?>
								<?php echo $html->link(__('Purchase Using iDeal', true), array('controller' => 'payment_gateways', 'action' => 'ideal', 'package', $package['Package']['id'])); ?><br>
							<?php endif;?>

							<?php if(Configure::read('PaymentGateways.DIBS.merchant')) : ?>
								<?php echo $html->link(__('Purchase Using DIBS', true), array('controller' => 'payment_gateways', 'action' => 'dibs', 'package', $package['Package']['id'])); ?><br>
							<?php endif;?>
						</td>
					</tr>
				<?php endforeach; ?>
				</table>

				<?php echo $this->element('pagination'); ?>

				<?php else:?>
					<p><?php __('There are no packages at the moment.');?></p>
				<?php endif;?>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>
