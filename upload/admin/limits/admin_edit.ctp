<?php
$html->addCrumb(__('Manage Content', true), '/admin/pages');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="limits form">
<?php echo $form->create('Limit');?>
	<fieldset>
 		<legend><?php __('Edit Limit');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('limit');
	?>
	</fieldset>
<?php echo $form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Limit.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Limit.id'))); ?></li>
		<li><?php echo $html->link(__('List Limits', true), array('action'=>'index'));?></li>
	</ul>
</div>
