<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Departments', true), '/admin/departments');
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="news form">
<?php echo $form->create('Department');?>
	<fieldset>
 		<legend><?php __('Add a Department');?></legend>
	<?php
		echo $form->input('name', array('label' => __('Name *', true)));
		echo $form->input('email');
	?>
	</fieldset>
<?php echo $form->end(__('Add Department', true));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to departments', true), array('action' => 'index'));?></li>
	</ul>
</div>
