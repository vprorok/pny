<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('My Bids');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
				$html->addCrumb(__('My Bids', true), '/bids');
				echo $this->element('crumb_user');
				?>
			
				<h1><?php __('My Bids');?></h1>
			
				<?php if($appConfigurations['simpleBids'] == false) : ?>
					<?php if(!empty($bids)): ?>
						<?php echo $this->element('pagination'); ?>
				
						<table class="results" cellpadding="0" cellspacing="0">
							<tr>
								<th><?php echo $paginator->sort('Date', 'Bid.created');?></th>
								<th><?php echo $paginator->sort('description');?></th>
								<th><?php echo $paginator->sort('debit');?></th>
								<th><?php echo $paginator->sort('credit');?></th>
							</tr>
							<tr>
								<td colspan="3"><strong><?php __('Current Balance');?></strong></td>
								<td><strong><?php echo $bidBalance; ?></strong></td>
							</tr>
						<?php
						$i = 0;
						foreach ($bids as $bid):
							$class = null;
							if ($i++ % 2 == 0) {
								$class = ' class="altrow"';
							}
						?>
							<tr<?php echo $class;?>>
								<td>
									<?php echo $time->niceShort($bid['Bid']['created']); ?>
								</td>
								<td>
									<?php echo $bid['Bid']['description']; ?>
									<?php if(!empty($bid['Bid']['auction_id'])) : ?>
										placed on auction titled: <?php echo $html->link($bid['Auction']['Product']['title'], array('controller' => 'auctions', 'action' => 'view', $bid['Bid']['auction_id']));?>
									<?php endif; ?>
								</td>
								<td>
									<?php if($bid['Bid']['debit'] > 0) : ?><?php echo $bid['Bid']['debit']; ?><?php else: ?>&nbsp;<?php endif; ?>
								</td>
								<td>
									<?php if($bid['Bid']['credit'] > 0) : ?><?php echo $bid['Bid']['credit']; ?><?php else: ?>&nbsp;<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</table>
				
						<?php echo $this->element('pagination'); ?>
				
					<?php else:?>
						<p><?php __('You have no bids in your account.');?></p>
					<?php endif;?>
				<?php else:?>
					<?php __('You have'); ?> <strong><?php echo $bidBalance; ?></strong> <?php __('bids in your account'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>