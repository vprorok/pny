<?php
$html->addCrumb('Manage Users', '/admin/users');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb('View', '/admin/'.$this->params['controller'].'/view/'.$user['User']['id']);
echo $this->element('admin/crumb');

?>

<h2><?php echo $user['User']['first_name']; ?> <?php echo $user['User']['last_name']; ?> (aka <?php echo $user['User']['username']; ?>)</h2>

<dl><?php $i = 0; $class = ' class="altrow"';?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Date of Birth'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $time->format('d F Y', $user['User']['date_of_birth']); ?>
	</dd>

	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Gender'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $genders[$user['User']['gender_id']]; ?>
	</dd>

	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php if($user['User']['active'] == 1) : ?>Yes<?php else: ?>No<?php endif; ?>
	</dd>

	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Newsletter'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php if($user['User']['newsletter'] == 1) : ?>Yes<?php else: ?>No<?php endif; ?>
	</dd>

	<?php if(!empty($referral)) : ?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Referred by'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $html->link($referral['Referrer']['username'], array('action' => 'view', $referral['Referrer']['id'])); ?>
		<?php if($referral['Referral']['confirmed'] == 0) : ?>(this referral is still pending.)<?php endif; ?>
	</dd>
	<?php endif; ?>

</dl>

<h2><?php __('User\'s Address Details'); ?></h2>

<?php if(!empty($address)) : ?>
	<?php foreach($address as $name => $address) : ?>
		<h2><?php echo $name; ?> Address</h2>
		<?php if(!empty($address)) : ?>
			<table class="results" cellpadding="0" cellspacing="0">
			<tr>
				<th><?php __('Name');?></th>
				<th><?php __('Address');?></th>
				<th><?php __('Suburb / Town');?></th>
				<th><?php __('City / State / County');?></th>
				<th><?php __('Postcode');?></th>
				<th><?php __('Country');?></th>
				<th><?php __('Phone Number');?></th>
			</tr>

			<tr>
				<td><?php echo $address['Address']['name']; ?></td>
				<td><?php echo $address['Address']['address_1']; ?><?php if(!empty($address['Address']['address_2'])) : ?>, <?php echo $address['Address']['address_2']; ?><?php endif; ?></td>
				<td><?php if(!empty($address['Address']['suburb'])) : ?><?php echo $address['Address']['suburb']; ?><?php else: ?>n/a<?php endif; ?></td>
				<td><?php echo $address['Address']['city']; ?></td>
				<td><?php echo $address['Address']['postcode']; ?></td>
				<td><?php echo $address['Country']['name']; ?></td>
				<td><?php if(!empty($address['Address']['phone'])) : ?><?php echo $address['Address']['phone']; ?><?php else: ?>n/a<?php endif; ?></td>
			</tr>
			</table>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
<?php $delete = 1; ?>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit User', true), array('action' => 'edit', $user['User']['id'])); ?></li>
		<?php if(empty($appConfigurations['simpleBids'])) : ?>
			<li><?php echo $html->link(__('Bids', true), array('controller' => 'bids', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>	
		<?php if(!empty($user['Bid'])) : ?>
			<?php $delete = 0; ?>
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
		<?php if(!empty($user['AffiliateCode'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Affiliate Account', true), array('controller' => 'affiliates', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($delete)) : ?>
			<li><?php echo $html->link(__('Delete User', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete this user?', true))); ?> </li>
		<?php endif; ?>
		<li><?php echo $html->link(__('<< Back to users', true), array('action' => 'index')); ?> </li>
	</ul>
</div>
