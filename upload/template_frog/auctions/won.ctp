<div id="ending-soon" class="box">
	<div class="f-top clearfix"><h2><?php __('Won Auctions');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
				$html->addCrumb(__('Won Auctions', true), '/auctions/won');
				echo $this->element('crumb_user');
				?>
			
				<?php if(!empty($auctions)): ?>
					<?php echo $this->element('pagination'); ?>
			
					<table class="results" cellpadding="0" cellspacing="0">
						<tr>
							<th><?php echo $paginator->sort('Image', 'title');?></th>
							<th><?php echo $paginator->sort('title');?></th>
							<th><?php __('Price');?></th>
							<th><?php echo $paginator->sort('Date Won', 'end_time');?></th>
							<th><?php echo $paginator->sort('Status', 'Status.name');?></th>
							<th><?php __('Options');?></th>
						</tr>
					<?php
					$i = 0;
					foreach ($auctions as $auction):
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
					?>
						<tr<?php echo $class;?>>
							<td>
								<?php if(!empty($auction['Product']['Image'][0]['image'])):?>
									<?php if(!empty($auction['Product']['Image'][0]['ImageDefault']['image'])) : ?>
										<?php echo $html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$auction['Product']['Image'][0]['ImageDefault']['image']); ?>
									<?php else: ?>
										<?php echo $html->image('product_images/thumbs/'.$auction['Product']['Image'][0]['image']); ?>
									<?php endif; ?>
								<?php else:?>
									<?php echo $html->image('product_images/thumbs/no-image.gif');?>
								<?php endif;?>
							</td>
							<td>
								<?php echo $html->link($auction['Product']['title'], array('controller' => 'auctions', 'action' => 'view', $auction['Auction']['id'])); ?>
							</td>
							<td>
								<?php if(!empty($auction['Product']['fixed'])) : ?>
									<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']); ?>
								<?php else: ?>
									<?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $time->niceShort($auction['Auction']['end_time']); ?>
							</td>
							<td>
								<?php echo $auction['Status']['name']; ?>
							</td>
							<td>
								<?php echo $html->link(__('View', true), array('action' => 'view', $auction['Auction']['id'])); ?>
								<?php if($auction['Status']['id'] == 1) : ?>
									<?php if(!empty($auction['Product']['fixed'])) : ?>
										<?php if($auction['Product']['fixed_price'] > 0) : ?>
											| <?php echo $html->link(__('Pay', true), array('action' => 'pay', $auction['Auction']['id'])); ?>
										<?php else : ?>
											| <?php echo $html->link(__('Confirm Details', true), array('action' => 'pay', $auction['Auction']['id'])); ?>
										<?php endif; ?>
									<?php else: ?>
										<?php if($auction['Auction']['price'] > 0) : ?>
											| <?php echo $html->link(__('Pay', true), array('action' => 'pay', $auction['Auction']['id'])); ?>
										<?php else : ?>
											| <?php echo $html->link(__('Confirm Details', true), array('action' => 'pay', $auction['Auction']['id'])); ?>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</table>
			
					<?php echo $this->element('pagination'); ?>
			
				<?php else:?>
					<p><?php __('You have not won any auctions yet.');?></p>
				<?php endif;?>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix">&nbsp;</div>
</div>
