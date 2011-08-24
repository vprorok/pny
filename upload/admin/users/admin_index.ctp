<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
echo $this->element('admin/crumb');
?>

<h2><?php __('Users');?></h2>
<blockquote><p>Below is a list of your website's users. From here you can reward users with points as well as view their signup information and bid history.</p></blockquote>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new user', true), array('action' => 'add')); ?></li>
		<li><?php echo $html->link(__('Search user', true), array('action' => 'search'), array('class' => 'searchUser')); ?></li>
	</ul>
</div>
<div class="searchBox" style="display: none">
	<?php echo $form->create('User', array('action' => 'search'));?>
	<fieldset>
		<?php echo $form->input('name');?>
		<?php echo $form->input('email');?>
		<?php echo $form->input('username');?>
	</fieldset>
	<?php echo $form->end('Search');?>
</div>
<?php if(!empty($users)): ?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('username');?> <img src="<?php echo $this->webroot?>/admin/img/sortup.gif" /> <img src="<?php echo $this->webroot?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort('first_name');?></th>
	<th><?php echo $paginator->sort('last_name');?></th>
	<th><?php echo $paginator->sort('email');?></th>
	<th><?php echo $paginator->sort('ip');?></th>
	<th><?php echo $paginator->sort('newsletter');?></th>
	<th><?php echo $paginator->sort(__('DOB', true), 'date_of_birth');?></th>
	<th><?php echo $paginator->sort(__('Source', true), 'source.name');?></th>
	<th><?php echo $paginator->sort('Active');?> <img src="<?php echo $this->webroot?>/admin/img/sortup.gif" /> <img src="<?php echo $this->webroot?>/admin/img/sortdown.gif" /> </th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td><?php echo $user['User']['username']; ?></td>
		<td><?php echo $user['User']['first_name']; ?></td>
		<td><?php echo $user['User']['last_name']; ?></td>
		<td><a href="mailto:<?php echo $user['User']['email']; ?>"><?php echo $user['User']['email']; ?></a></td>
		<td>
			<?php if(!empty($user['User']['ip'])):?>
				<?php echo $html->link($user['User']['ip'], 'http://centralops.net/co/DomainDossier.aspx?addr='.$user['User']['ip'].'&dom_whois=true&net_whois=true', array('target' => '_blank')); ?>
			<?php else:?>
				<?php __('Not Available');?>
			<?php endif;?>
		</td>
		<td><?php if($user['User']['newsletter'] == 1) : ?>Yes<?php else: ?>No<?php endif; ?></td>
		<td><?php echo $time->format('d-m-Y', $user['User']['date_of_birth']); ?></td>
		<td>
			<?php if(!empty($user['Source']['name'])): ?>
				<?php echo $user['Source']['name']; ?>
				<?php if(!empty($user['User']['source_extra'])): ?>
					(<?php echo $user['User']['source_extra']; ?>)
				<?php endif; ?>
			<?php endif; ?>
		</td>
		<td><?php if($user['User']['active'] == 1) : ?>Yes<?php else: ?>No<?php endif; ?></td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action' => 'view', $user['User']['id'])); ?>
			<?php if(empty($user['User']['active'])):?>
				/ <?php echo $html->link(__('Resend Activation Email', true), array('action' => 'resend', $user['User']['id'])); ?>
			<?php endif;?>
			/ <?php echo $html->link(__('Edit', true), array('action' => 'edit', $user['User']['id'])); ?>
			/ <?php echo $html->link(__('Password', true), array('action' => 'changepassword', $user['User']['id'])); ?>
			<?php if(empty($appConfigurations['simpleBids'])) : ?>
				/ <?php echo $html->link(__('Bids', true), array('controller' => 'bids', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($user['Bidbutler'])) : ?>
				/ <?php echo $html->link(__('Bid Butlers', true), array('controller' => 'bidbutlers', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($user['Auction'])) : ?>
				/ <?php echo $html->link(__('Won Auctions', true), array('controller' => 'auctions', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($user['Account'])) : ?>
				/ <?php echo $html->link(__('Account', true), array('controller' => 'accounts', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($appConfigurations['limits']['active'])) : ?>
				/ <?php echo $html->link(__('Limits', true), array('controller' => 'limits', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($user['Referred'])) : ?>
				/ <?php echo $html->link(__('Referred Users', true), array('controller' => 'referrals', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($user['AffiliateCode'])) : ?>
				/ <?php echo $html->link(__('Affiliate Account', true), array('controller' => 'affiliates', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			
			<?php if(!empty($appConfigurations['credits']['active'])) : ?>
				<?php if(!empty($user['Credit'])) : ?>
					/ <?php echo $html->link(__('Credits', true), array('controller' => 'credits', 'action' => 'user', $user['User']['id'])); ?>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php if(!empty($appConfigurations['rewardsPoint'])) : ?>
					/ <?php echo $html->link(__('Reward Points', true), array('controller' => 'points', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			
			<?php if(empty($user['User']['admin'])) : ?>
				<?php if(!empty($user['User']['active'])):?>
					/ <?php echo $html->link(__('Suspend', true), array('action' => 'suspend', $user['User']['id']), null, sprintf(__('Are you sure you want to suspend the user: %s?', true), $user['User']['username'])); ?>
					/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete the user: %s?  Users should only be deleted if they have requested their account to be deleted or as a last minute resort, as it can have consequences on other areas of the website.  We recommend using the suspend option instead.', true), $user['User']['username'])); ?>	
				<?php else : ?>
					/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete the user: %s?', true), $user['User']['username'])); ?>
				<?php endif; ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
<p><?php __('There are no users at the moment.');?></p>
<?php endif;?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new user', true), array('action' => 'add')); ?></li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('a.searchUser').click(function(){
			$('.searchBox').toggle();
			return false;
		});
	});
</script>