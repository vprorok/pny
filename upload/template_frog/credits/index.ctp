<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('My Credits');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="rightcol">
			
				<?php if(!empty($credits)): ?>
					<table class="results" cellpadding="0" cellspacing="0">
						<tr>
							<th width="106" style="border:1px solid #a7d5ed; border-right:none;"><?php echo $paginator->sort('Date', 'created');?></th>
							<th style="border:1px solid #a7d5ed; border-right:none; border-left:none;"><?php echo $paginator->sort('Description', 'Auction.title');?></th>
							<th class="right" width="70" style="border:1px solid #a7d5ed; border-right:none; border-left:none;"><?php echo $paginator->sort('debit');?></th>
							<th class="right" width="70" style="border:1px solid #a7d5ed; border-left:none;"><?php echo $paginator->sort('credit');?></th>
						</tr>
			
					<?php
					$i = 0;
					foreach ($credits as $credit):
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
					?>
						<tr<?php echo $class;?>>
							<td>
								<?php echo $time->niceShort($credit['Credit']['created']); ?>
							</td>
							<td>
								<?php if($credit['Credit']['debit'] > 0) : ?>
									<?php __('Credits used on:'); ?> <a href="<?php echo $appConfigurations['url'];?>/auctions/view/<?php echo $credit['Auction']['id']; ?>"><?php echo $credit['Auction']['Product']['title']; ?></a>
								<?php else : ?>
									<?php __('Credits earned on:'); ?> <a href="<?php echo $appConfigurations['url'];?>/auctions/view/<?php echo $credit['Auction']['id']; ?>"><?php echo $credit['Auction']['Product']['title']; ?></a>
								<?php endif; ?>
							</td>
							<td class="right"><?php echo $credit['Credit']['debit']; ?></td>
							<td class="right"><?php echo $credit['Credit']['credit']; ?></td>
						</tr>
					<?php endforeach; ?>
						<tr>
							<td colspan="3"><strong><?php __('Current Credits');?> :</strong></td>
							<td class="right"><strong><?php echo $creditBalance; ?></strong></td>
						</tr>
					</table>
			
					<?php echo $this->element('pagination'); ?>
			
				<?php else:?>
					<p class="align-center bold font-18" style="margin-top:3em; color:#AAAAAA;"><?php __('You have no credits at the moment.');?></p>
				<?php endif;?>
			</div>
			<div id="left">
			<div class="content">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			</div>
		</div>
		<br class="clear_l">
		<div class="crumb_bar">
				<?php
				$html->addCrumb(__('My Credits', true), '/credits');
				echo $this->element('crumb_user');
				?>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>