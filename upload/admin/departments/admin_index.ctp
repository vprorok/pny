<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Departments', true), '/admin/departments');
echo $this->element('admin/crumb');
?>

<div class="news index">

<h2><?php __('Departments');?></h2>
<blockquote><p>
<p><?php __('If an email address is entered in on a department, then the contact form will be sent to that email address if that particular department is selected.');?> <span class="helplink">[ <a href="https://members.phppennyauction.com/link.php?id=22" target="_blank">Find out more &raquo;</a> ]</span></p>
</p></blockquote>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a department', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('email');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($departments as $department):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $department['Department']['name']; ?>
		</td>
		<td>
			<?php echo $department['Department']['email']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $department['Department']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $department['Department']['id']), null, sprintf(__('Are you sure you want to delete the department titled: %s?', true), $department['Department']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
	<p><?php __('There are no departments at the moment.');?></p>
<?php endif;?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a department', true), array('action' => 'add')); ?></li>
	</ul>
</div>
