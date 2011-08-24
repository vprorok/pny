<div class="box clearfix">
	<div class="f-top-w clearfix"><h2><?php __('Affiliate Earnings');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				
				<?php if($affiliateBalance > 0) : ?>
				<h2><?php __('Withdraw Your Balance');?></h2>
				
				<p><?php __('Please enter in the amount you wish to withdraw and your paypal email address below and we will withdraw your balance within 3 working days.');?></p>
				
				<fieldset>
					<legend></legend>
					<?php echo $form->create('Affiliate', array('action' => 'index'));?>
					<?php echo $form->input('balance', array('label' => __('Balance:', true), 'size' => 5));?>
					<?php echo $form->input('email', array('label' => __('Paypal Email Address:', true)));?>
					<?php echo $form->end(__('Withdraw Balance', true));?>
				</fieldset>
				<?php endif; ?>
				
				<?php if(!empty($affiliates)): ?>
			
					<table class="results" cellpadding="0" cellspacing="0">
						<tr>
							<th><?php echo $paginator->sort('Date', 'created');?></th>
							<th><?php echo $paginator->sort('description');?></th>
							<th><?php echo $paginator->sort('debit');?></th>
							<th><?php echo $paginator->sort('credit');?></th>
						</tr>
						
						<tr>
							<td colspan="3"><strong><?php __('Current Balance');?></strong></td>
							<td><strong><?php echo $affiliateBalance; ?></strong></td>
						</tr>
					<?php
					$i = 0;
					foreach ($affiliates as $affiliate):
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
					?>
						<tr<?php echo $class;?>>
							<td><?php echo $time->niceShort($affiliate['Affiliate']['created']); ?></td>
							<td><?php echo $affiliate['Affiliate']['description']; ?>
							<?php if(!empty($affiliate['User']['username'])) : ?>
							 - Signed up <?php echo $affiliate['User']['username']; ?>
							<?php endif; ?>
							</td>
							<td><?php echo $number->currency($affiliate['Affiliate']['debit'], $appConfigurations['currency']); ?></td>
							<td><?php echo $number->currency($affiliate['Affiliate']['credit'], $appConfigurations['currency']); ?></td>
						</tr>
					<?php endforeach; ?>
					</table>
			
					<?php echo $this->element('pagination'); ?>
			
				<?php else:?>
					<div class="align-center off_message"><p><?php __('You have no affiliate earnings at the moment.');?></p></div>
				<?php endif;?>
			</div>
			<div id="rightcol">
			<div class="content">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			</div>
		</div>
		<br class="clear_l">
		<div class="crumb_bar">
				<?php
				$html->addCrumb('Affiliate Earnings', '/affiliates');
				echo $this->element('crumb_user');
				?>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>