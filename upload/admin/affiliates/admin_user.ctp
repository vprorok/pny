<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
$html->addCrumb(__('Affiates Account', true), '/admin/referrals/user/'.$user['User']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php __('Affiliates Account');?></h2>

<?php if(!empty($affiliates)) : ?>

	<?php echo $this->element('pagination'); ?>

	<table class="results" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $paginator->sort('Date', 'created');?></th>
			<th><?php echo $paginator->sort('description');?></th>
			<th><?php echo $paginator->sort('debit');?></th>
			<th><?php echo $paginator->sort('credit');?></th>
		</tr>
		
		<tr>
			<td colspan="3"><strong><?php __('Current Balance');?></strong></td>
			<td><strong><?php echo $affiliateBalance; ?></strong></td>
		</tr>
		
		<?php
		$i = 0;
		foreach ($affiliates as $affiliate):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $time->niceShort($affiliate['Affiliate']['created']); ?></td>
			<td><?php echo $affiliate['Affiliate']['description']; ?>
			<?php if(!empty($affiliate['User']['username'])) : ?>
			 - Signed up <?php echo $html->link($affiliate['User']['username'], array('controller' => 'users', 'action' => 'view', $affiliate['User']['id'])); ?>
			<?php endif; ?>
			</td>
			<td><?php echo $number->currency($affiliate['Affiliate']['debit'], $appConfigurations['currency']); ?></td>
			<td><?php echo $number->currency($affiliate['Affiliate']['credit'], $appConfigurations['currency']); ?></td>
		</tr>
	<?php endforeach; ?>
	</table>

	<?php echo $this->element('pagination'); ?>

<?php else:?>
	<p><?php __('This user has referred no users.');?></p>
<?php endif;?>