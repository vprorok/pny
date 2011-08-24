<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
$html->addCrumb($user['User']['username'], '/admin/users/view/'.$user['User']['id']);
$html->addCrumb(__('Bid Butlers', true), '/admin/bidbutlers/user/'.$user['User']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php __('Users Bid Butlers');?></h2>

<?php if(!empty($bidbutlers)): ?>
	<?php echo $this->element('admin/pagination'); ?>

	<table class="results" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $paginator->sort('Auction', 'Auction.title');?></th>
			<th><?php echo $paginator->sort('minimum_price');?></th>
			<th><?php echo $paginator->sort('maximum_price');?></th>
			<th><?php echo $paginator->sort('bids');?></th>
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
				<?php echo $html->link($bidbutler['Auction']['Product']['title'], array('admin' => false, 'controller' => 'auctions', 'action' => 'view', $bidbutler['Bidbutler']['auction_id']), array('target' => '_blank'));?>
			</td>
			<td>
				<?php echo $number->currency($bidbutler['Bidbutler']['minimum_price'], $appConfigurations['currency']); ?>
			</td>
			<td>
				<?php echo $number->currency($bidbutler['Bidbutler']['maximum_price'], $appConfigurations['currency']); ?>
			</td>
			<td>
				<?php echo $bidbutler['Bidbutler']['bids']; ?>
			</td>
			<td>
				<?php echo $time->niceShort($bidbutler['Bidbutler']['created']); ?>
			</td>
			<td>
			<?php echo $html->link(__('Delete', true), array('action' => 'delete', $bidbutler['Bidbutler']['id']), null, sprintf(__('Are you sure you want to delete this bid butler?', true))); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>

	<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
	<p><?php __('This user has no bid butlers at the moment.');?></p>
<?php endif;?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit User', true), array('action' => 'edit', $user['User']['id'])); ?></li>
		<?php if(!empty($user['Bid'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Bids', true), array('controller' => 'bids', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($user['Bidbutler'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Bid Butlers', true), array('controller' => 'bidbutlers', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($user['Auction'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Won Auctions', true), array('controller' => 'auctions', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($user['Account'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Account', true), array('controller' => 'accounts', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($user['Referred'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Referred Users', true), array('controller' => 'referrals', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($delete)) : ?>
			<li><?php echo $html->link(__('Delete User', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete this user?', true))); ?> </li>
		<?php endif; ?>
		<li><?php echo $html->link(__('<< Back to users', true), array('action' => 'index')); ?> </li>
	</ul>
</div>
