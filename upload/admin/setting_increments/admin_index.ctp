<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Bidding Increments', true), '/admin/setting_increments');
echo $this->element('admin/crumb');
?>

<div class="settings index">

<h2><?php __('Bidding Increments');?></h2>
<blockquote><p>Bidding Increments allow you to set multiple price points depending on the current amount that the bidding is up to. For example, if you want your users to start paying $0.25 per bid when the bidding reaches $100.00 on that product, but want the regular bid price to be $0.10 before that price is reached, you can use increments to achieve the desired result. <span class="helplink">[ <a href="https://members.phppennyauction.com/link.php?id=24" target="_blank">Find out more &raquo;</a> ]</span></p></blockquote>
<?php if($appConfigurations['bidIncrements'] == 'dynamic') : ?>
	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('Add a bid increment', true), array('action' => 'add')); ?></li>
		</ul>
	</div>
<?php endif; ?>

<?php if(!empty($settings)) : ?>
	<?php echo $this->element('admin/pagination'); ?>

	<table cellpadding="0" cellspacing="10">
	<tr>
		<?php if($appConfigurations['bidIncrements'] == 'dynamic') : ?>
			<th><?php echo $paginator->sort('lower_price');?></th>
			<th><?php echo $paginator->sort('upper_price');?></th>
		<?php endif; ?>
		<th><?php echo $paginator->sort(__('Bid Debit', true), 'bid_debit');?></th>
		<th><?php echo $paginator->sort(__('Price Increment', true), 'price_increment');?></th>
		<th><?php echo $paginator->sort(__('Time Increment', true), 'time_increment');?></th>
		<th class="actions"><?php __('Options');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($settings as $setting):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
		<tr<?php echo $class;?>>
			<?php if($appConfigurations['bidIncrements'] == 'dynamic') : ?>
				<td>
					<?php echo number_format($setting['SettingIncrement']['lower_price'], 2); ?>
				</td>
				<td>
					<?php echo number_format($setting['SettingIncrement']['upper_price'], 2); ?>
				</td>
			<?php endif; ?>
			<td>
				<?php echo $setting['SettingIncrement']['bid_debit']; ?>
			</td>
			<td>
				<?php echo number_format($setting['SettingIncrement']['price_increment'], 2); ?>
			</td>
			<td>
				<?php echo $setting['SettingIncrement']['time_increment']; ?>
			</td>
			<td class="actions">
				<?php echo $html->link(__('Edit', true), array('action'=>'edit', $setting['SettingIncrement']['id'])); ?>
				<?php if($appConfigurations['bidIncrements'] == 'dynamic') : ?>
					/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $setting['SettingIncrement']['id']), null, sprintf(__('Are you sure you want to delete this setting?', true))); ?>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	</div>
	<?php echo $this->element('admin/pagination'); ?>
<?php else : ?>
	<p>There are no bid increments at the moment.</p>
<?php endif; ?>

<?php if($appConfigurations['bidIncrements'] == 'dynamic') : ?>
	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('Add a bid increment', true), array('action' => 'add')); ?></li>
		</ul>
	</div>
<?php endif; ?>