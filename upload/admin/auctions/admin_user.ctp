<?php
$html->addCrumb('Manage Users', '/admin/users');
if($user['Winner']['autobidder'] == 0) :
	$html->addCrumb('Users', '/admin/users');
	$html->addCrumb($user['Winner']['username'], '/admin/users/view/'.$user['Winner']['id']);
else:
	$html->addCrumb('Auto Bidders', '/admin/users/autobidders');
	$html->addCrumb($user['Winner']['username'], '/admin/users/autobidder_edit/'.$user['Winner']['id']); 
endif;

$html->addCrumb('Won Auctions', '/admin/auctions/user/'.$user['Winner']['id']); 
echo $this->element('admin/crumb');
?>

<div class="auctions index">

<h2><?php __('Won Auctions');?></h2>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('category_id');?></th>
	<th><?php echo $paginator->sort('featured');?></th>
	<th><?php echo $paginator->sort('peak_only');?></th>
	<th><?php echo $paginator->sort('end_time');?></th>
	<th class="actions"><?php __('Options');?></th>
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
			<?php echo $auction['Product']['title']; ?>
		</td>
		<td>
			<?php echo $html->link($auction['Product']['Category']['name'], array('admin' => false, 'controller'=> 'categories', 'action'=>'view', $auction['Product']['Category']['id']), array('target' => '_blank')); ?>
		</td>
		<td>
			<?php echo !empty($auction['Auction']['featured']) ? __('Yes') : __('No'); ?>
		</td>
		<td>
			<?php echo !empty($auction['Auction']['peak_only']) ? __('Yes') : __('No'); ?>
		</td>
		<td>
			<?php echo $time->nice($auction['Auction']['end_time']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'action' => 'view', $auction['Auction']['id']), array('target' => '_blank')); ?>
			<?php if((!empty($auction['Winner']['id'])) && ($auction['Winner']['autobidder'] == 0)) : ?>
				/ <?php echo $html->link(__('More Information', true), array('action' => 'winner', $auction['Auction']['id'])); ?>
			<?php endif; ?>
			
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('This user has not won any auctions yet.');?></p>
<?php endif; ?>
</div>

<div class="actions">
	<ul>
		<?php if($user['Winner']['autobidder'] == 0) : ?>
			<li><?php echo $html->link(__('Edit User', true), array('controller' => 'users', 'action' => 'edit', $user['Winner']['id'])); ?></li>
			<?php if(!empty($user['Bid'])) : ?>
				<?php $delete = 0; ?>
				<li><?php echo $html->link(__('Bids', true), array('controller' => 'bids', 'action' => 'user', $user['Winner']['id'])); ?></li>
			<?php endif; ?>
			<?php if(!empty($user['Bidbutler'])) : ?>
				<?php $delete = 0; ?>
				<li><?php echo $html->link(__('Bid Butlers', true), array('controller' => 'bidbutlers', 'action' => 'user', $user['Winner']['id'])); ?></li>
			<?php endif; ?>
			<?php if(!empty($user['Auction'])) : ?>
				<?php $delete = 0; ?>
				<li><?php echo $html->link(__('Won Auctions', true), array('controller' => 'auctions', 'action' => 'user', $user['Winner']['id'])); ?></li>
			<?php endif; ?>
			<?php if(!empty($user['Account'])) : ?>
				<?php $delete = 0; ?>
				<li><?php echo $html->link(__('Account', true), array('controller' => 'accounts', 'action' => 'user', $user['Winner']['id'])); ?></li>
			<?php endif; ?>
			<?php if(!empty($user['Referred'])) : ?>
				<?php $delete = 0; ?>
				<li><?php echo $html->link(__('Referred Users', true), array('controller' => 'referrals', 'action' => 'user', $user['Winner']['id'])); ?></li>
			<?php endif; ?>
			<?php if(!empty($delete)) : ?>
				<li><?php echo $html->link(__('Delete User', true), array('action' => 'delete', $user['Winner']['id']), null, sprintf(__('Are you sure you want to delete this user?', true))); ?> </li>
			<?php endif; ?>
		<?php endif; ?>
		<li><?php echo $html->link(__('<< Back to users', true), array('action' => 'index')); ?> </li>
	</ul>
</div>