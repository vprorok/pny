<div class="sources form">
<?php echo $form->create('Source');?>
	<fieldset>
 		<legend><?php __('Edit Source');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('extra');
	?>
	</fieldset>
<?php echo $form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to packages', true), array('action'=>'index'));?></li>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Source.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Source.id'))); ?></li>
	</ul>
</div>
