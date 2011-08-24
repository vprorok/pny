<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
$html->addCrumb($user['User']['username'], '/admin/users/view/'.$user['User']['id']);
$html->addCrumb(__('Referrals', true), '/admin/referrals/user/'.$user['User']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php __('User Referrals');?></h2>

<?php if(!empty($referrals)) : ?>

	<?php echo $this->element('pagination'); ?>

	<table class="results" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $paginator->sort('User.username');?></th>
			<th><?php echo $paginator->sort('User.first_name');?></th>
			<th><?php echo $paginator->sort('User.last_name');?></th>
			<th><?php echo $paginator->sort('Date Joined', 'Referral.created');?></th>
			<th><?php echo $paginator->sort('Bids Given', 'User.confirmed');?></th>
			<th><?php echo $paginator->sort('Date Bids Given', 'Referral.modified');?></th>
		</tr>
		<?php
		$i = 0;
		foreach ($referrals as $referral):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td>
				<?php echo $referral['User']['username']; ?>
			</td>
			<td>
				<?php echo $referral['User']['first_name']; ?>
			</td>
			<td>
				<?php echo $referral['User']['last_name']; ?>
			</td>
			<td>
				<?php echo $time->niceShort($referral['Referral']['created']); ?>
			</td>
			<td>
				<?php if($referral['Referral']['confirmed'] == 1) : ?>Yes<?php else: ?>No<?php endif; ?>
			</td>
			<td>
				<?php if($referral['Referral']['confirmed'] == 1) : ?><?php echo $time->niceShort($referral['Referral']['modified']); ?><?php else: ?>n/a<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>

	<?php echo $this->element('pagination'); ?>

<?php else:?>
	<p><?php __('This user has not referred any members yet.');?></p>
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
