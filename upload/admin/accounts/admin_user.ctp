<?php
$html->addCrumb('Manage Users', '/admin/users');
$html->addCrumb('Users', '/admin/users');
$html->addCrumb($user['User']['username'], '/admin/users/view/'.$user['User']['id']);
$html->addCrumb('Account', '/admin/accounts/user/'.$user['User']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php __('Users Account');?></h2>
<?php if(!empty($accounts)): ?>
	<?php echo $this->element('pagination'); ?>

	<table class="results" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $paginator->sort('Date', 'created');?></th>
			<th><?php echo $paginator->sort('Description', 'Account.name');?></th>
			<th><?php echo $paginator->sort('Amount', 'Auction.price');?></th>
		</tr>
	<?php
	$i = 0;
	foreach ($accounts as $account):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
		<tr<?php echo $class;?>>
			<td>
				<?php echo $time->niceShort($account['Account']['created']); ?>
			</td>
			<td>
				<?php echo $account['Account']['name']; ?>
				<?php if($account['Account']['auction_id']) : ?>
					<a target="_blank" href="/auctions/view/<?php echo $account['Account']['auction_id']; ?>"><?php __('View this Auction');?></a>
				<?php elseif($account['Account']['bids']) : ?>
					- <?php echo sprintf(__('%d Bids Purchased', true), $account['Account']['bids']); ?>
				<?php endif; ?>
				
				<?php if(!empty($account['Account']['ip'])):?>
					(IP Information: <?php echo $html->link($account['Account']['ip'], 'http://centralops.net/co/DomainDossier.aspx?addr='.$account['Account']['ip'].'&dom_whois=true&net_whois=true', array('target' => '_blank')); ?>)
				<?php endif;?>
			</td>
			<td>
				<?php echo $number->currency($account['Account']['price'], $appConfigurations['currency']); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>

	<?php echo $this->element('pagination'); ?>

<?php else:?>
	<p><?php __('This user has no account transactions at the moment.');?></p>
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
