<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
$html->addCrumb(__('Referrals', true), '/admin/referrals/user/'.$user['User']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php __('Referrals');?></h2>
<blockquote><p>Users who have referred new users to your website are displayed here.</p></blockquote>
<?php if(!empty($referrals)) : ?>

	<?php echo $this->element('pagination'); ?>

	<table class="results" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $paginator->sort(__('Username', true), 'User.username');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
			<th><?php echo $paginator->sort(__('First Name', true), 'User.first_name');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /></th>
			<th><?php echo $paginator->sort(__('Last Name', true), 'User.last_name');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /></th>
			<th><?php echo $paginator->sort(__('Date Joined', true), 'Referral.created');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /></th>
			<th><?php echo $paginator->sort(__('Bids Given', true), 'User.confirmed');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /></th>
			<th><?php echo $paginator->sort(__('Date Bids Given', true), 'Referral.modified');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /></th>
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
	<p><?php __('There are no referrals yet.');?></p>
<?php endif;?>
