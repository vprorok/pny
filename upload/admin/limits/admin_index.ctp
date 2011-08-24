<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
echo $this->element('admin/crumb');
?>

<div class="limits index">

<h2><?php __('Limits');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a limit', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('limit');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($limits as $limit):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $limit['Limit']['name']; ?>
		</td>
		<td>
			<?php echo $limit['Limit']['limit']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $limit['Limit']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $limit['Limit']['id']), null, sprintf(__('Are you sure you want to delete the limit named: %s?', true), $limit['Limit']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
	<p><?php __('There are no limits at the moment.');?></p>
<?php endif;?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a limit', true), array('action' => 'add')); ?></li>
	</ul>
</div>
