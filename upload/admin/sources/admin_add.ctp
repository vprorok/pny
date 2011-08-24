<div class="sources form">
<?php echo $form->create('Source');?>
	<fieldset>
 		<legend><?php __('Add Source');?></legend>
	<?php
		echo $form->input('name');
		echo $form->input('extra');
	?>
	</fieldset>
<?php echo $form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to Sources', true), array('action'=>'index'));?></li>
	</ul>
</div>
