<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('My BidBuddies');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
					$html->addCrumb(__('My Bid Butlers', true), '/bidbutlers');
					echo $this->element('crumb_user');
				?>
				
				<?php if(!empty($bidbutlers)): ?>
					<?php echo $this->element('pagination'); ?>
				
					<table class="results" cellpadding="0" cellspacing="0">
						<tr>
							<th><?php echo $paginator->sort('Image', 'Product.title');?></th>
							<th><?php echo $paginator->sort('Auction', 'Product.title');?></th>
							<?php if($appConfigurations['bidButlerType'] !== 'simple') : ?>
								<th><?php echo $paginator->sort('minimum_price');?></th>
								<th><?php echo $paginator->sort('maximum_price');?></th>
							<?php endif; ?>
							<th><?php echo $paginator->sort('Bids Left', 'bids');?></th>
							<th><?php echo $paginator->sort('Date', 'created');?></th>
							<th class="actions"><?php __('Options');?></th>
						</tr>
					<?php
					$i = 0;
					foreach ($bidbutlers as $bidbutler):
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
					?>
						<tr<?php echo $class;?>>
							<td>
							<a href="/auctions/view/<?php echo $bidbutler['Auction']['id']; ?>">
							<?php if(!empty($bidbutler['Auction']['Product']['Image'])):?>
								<?php if(!empty($bidbutler['Auction']['Product']['Image'][0]['ImageDefault']['image'])) : ?>
									<?php echo $html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$bidbutler['Auction']['Product']['Image'][0]['ImageDefault']['image']); ?>
								<?php else: ?>
									<?php echo $html->image('product_images/thumbs/'.$bidbutler['Auction']['Product']['Image'][0]['image']); ?>
								<?php endif; ?>
							<?php else:?>
								<?php echo $html->image('product_images/thumbs/no-image.gif');?>
							<?php endif;?>
							</a>
							</td>
							<td>
								<?php echo $html->link($bidbutler['Auction']['Product']['title'], array('controller'=> 'auctions', 'action'=>'view', $bidbutler['Auction']['id'])); ?>
							</td>
							<?php if($appConfigurations['bidButlerType'] !== 'simple') : ?>
								<td>
									<?php echo $number->currency($bidbutler['Bidbutler']['minimum_price'], $appConfigurations['currency']); ?>
								</td>
								<td>
									<?php echo $number->currency($bidbutler['Bidbutler']['maximum_price'], $appConfigurations['currency']); ?>
								</td>
							<?php endif; ?>
							<td>
								<?php echo $bidbutler['Bidbutler']['bids']; ?>
							</td>
							<td>
								<?php echo $time->niceShort($bidbutler['Bidbutler']['created']); ?>
							</td>
							<td class="actions">
								<?php echo $html->link(__('Edit', true), array('action'=>'edit', $bidbutler['Bidbutler']['id'])); ?>
								| <?php echo $html->link(__('Delete', true), array('action'=>'delete', $bidbutler['Bidbutler']['id']), null, sprintf(__('Are you sure you want to delete this bid butler?', true))); ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</table>
				
					<?php echo $this->element('pagination'); ?>
				
				<?php else:?>
					<p><?php __('You have no bid butlers at the moment.');?></p>
				<?php endif;?>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>