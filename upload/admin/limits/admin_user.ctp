<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
$html->addCrumb($user['User']['username'], '/admin/users/view/'.$user['User']['id']);
$html->addCrumb(__('Limits', true), '/admin/limits/user/'.$user['User']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php __('Users Limits');?></h2>

<table class="results" cellpadding="0" cellspacing="0">
	<tr>
		<th>&nbsp;</th>
		<th><?php __('Status');?></th>
		<th><?php __('Auction Title');?></th>
		<th><?php __('Information');?></th>
		<th><?php __('Group');?></th>
	</tr>

	<?php
	$i = 0;
		if(!empty($auctions)): ?>
		<?php
		foreach ($auctions as $auction):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
			<tr<?php echo $class;?>>
				<td><?php echo $i; ?></td>
				<td><?php if($auction['Auction']['winner_id'] > 0) : ?><?php __('You Won!');?><?php else : ?>Leading Bid<?php endif; ?></td>
				<td><?php echo $html->link($auction['Product']['title'], array('admin' => false, 'controller' => 'auctions', 'action' => 'view', $auction['Auction']['id']));?></td>
				<td>
				<?php if($auction['Auction']['winner_id'] > 0) : ?>
					<strong><?php __('You have won this auction!');?></strong>
					<br />
					<?php __('This auction slot will become available after the auction has been closed for');?> <?php echo $appConfigurations['limits']['expiry']; ?> <?php __('days.');?>
				<?php else : ?>
					<strong><?php __('This user is leading the bidding!');?></strong>
					<br />
					<?php __('This auction slot will become available when the users last bid has been outbid by another bidder.');?>
				<?php endif; ?>
				</td>
				<td><?php echo $auction['Product']['Limit']['name']; ?></td>
			</tr>
		<?php endforeach; ?>
	<?php endif;?>

	<?php
	$n = $total + 1;
		while ($n <= $appConfigurations['limits']['limit']) { ?>
		    <?php
		    $class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		    ?>

		    <tr<?php echo $class;?>>
			    <td><?php echo $i; ?></td>
				<td>&nbsp;</td>
				<td><?php __('free');?></td>
				<td>-</td>
				<td>&nbsp;</td>
			</tr>
			<?php $n ++; ?>
		<?php } ?>

	</table>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit User', true), array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?></li>
		<li><?php echo $html->link(__('<< Back to users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
	</ul>
</div>