<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
echo $this->element('admin/crumb');
?>

<div class="rewards index">

<h2><?php __('Rewards');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a reward', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('rrp');?></th>
	<th><?php echo $paginator->sort('points');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($rewards as $reward):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $reward['Reward']['title']; ?>
		</td>
		<td>
			<?php echo $reward['Reward']['rrp']; ?>
		</td>
		<td>
			<?php echo $reward['Reward']['points']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'action' => 'view', $reward['Reward']['id']), array('target' => '_blank')); ?>
			/ <?php echo $html->link(__('Edit', true), array('action'=>'edit', $reward['Reward']['id'])); ?>
			/ <?php echo $html->link(__('Edit Image', true), array('action'=>'image', $reward['Reward']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action'=>'delete', $reward['Reward']['id']), null, sprintf(__('Are you sure you want to delete the reward titled: %s?', true), $reward['Reward']['title'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
	<p><?php __('There are no rewards at the moment');?></p>
<?php endif;?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a reward', true), array('action' => 'add')); ?></li>
	</ul>
</div>