<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
echo $this->element('admin/crumb');
?>

<h2><?php __('Affiliate Codes');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new affiliate code', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if(!empty($codes)): ?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('username');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort('first_name');?></th>
	<th><?php echo $paginator->sort('last_name');?></th>
	<th><?php echo $paginator->sort('email');?></th>
	<th><?php echo $paginator->sort('code');?></th>
	<th><?php echo $paginator->sort('credit');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($codes as $code):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	$delete = 1;
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $html->link($code['User']['username'], array('controller' => 'users', 'action' => 'view', $code['User']['id'])); ?>
		</td>
		<td><?php echo $code['User']['first_name']; ?></td>
		<td><?php echo $code['User']['last_name']; ?></td>
		<td><a href="mailto:<?php echo $code['User']['email']; ?>"><?php echo $code['User']['email']; ?></a></td>
		<td><?php echo $code['AffiliateCode']['code']; ?></td>
		<td><?php echo $code['AffiliateCode']['credit']; ?></td>
		<td class="actions">
			<?php echo $html->link(__('Account', true), array('controller' => 'affiliates', 'action' => 'user', $code['User']['id'])); ?>
			/ <?php echo $html->link(__('Edit', true), array('action' => 'edit', $code['AffiliateCode']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $code['AffiliateCode']['id']), null, sprintf(__('Are you sure you want to delete the affiliate code: %s?', true), $code['AffiliateCode']['code'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
<p><?php __('There are no affiliate codes at the moment.');?></p>
<?php endif;?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new affiliate code', true), array('action' => 'add')); ?></li>
	</ul>
</div>