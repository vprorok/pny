<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="limits form">
<?php echo $form->create('Limit');?>
	<fieldset>
 		<legend><?php __('Add Limit');?></legend>
	<?php
		echo $form->input('name', array('label' => __('Name *', true)));
		echo $form->input('limit', array('label' => __('Limit *', true)));
	?>
	</fieldset>
<?php echo $form->end(__('Add Limit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to the limits', true), array('action'=>'index'));?></li>
	</ul>
</div>
