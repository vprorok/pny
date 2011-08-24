<?php
$html->addCrumb(__('Settings'), '/admin/settings');
$html->addCrumb(__('Departments', true), '/admin/departments');
$html->addCrumb(__('Edit'), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Department']['id']);
echo $this->element('admin/crumb');
?>

<div class="news form">
<?php echo $form->create('Department');?>
	<fieldset>
 		<legend><?php __('Add a Department');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name', array('label' => 'Name *'));
		echo $form->input('email');
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $form->value('Department.id')), null, sprintf(__('Are you sure you want to delete this department?', true))); ?></li>
		<li><?php echo $html->link(__('<< Back to departments', true), array('action' => 'index'));?></li>
	</ul>
</div>
