<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Default Images', true), '/admin/image_defaults');
$html->addCrumb(__('Create', true), '/admin/image_defaults/create');
echo $this->element('admin/crumb');
?>

<div class="imagedefault form">

<?php echo $form->create('ImageDefault', array('url' => '/admin/image_defaults/create'));?>
	<fieldset>
 		<legend><?php __('Create Default Image');?></legend>

	<?php
		echo $form->input('name', array('label' => __('Name *', true)));
		echo $form->input('image', array('label' => __('Image (file name) *', true)));
	?>
	</fieldset>
<?php echo $form->end(__('Add Default Image >>', true));?>
</div>

<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to default images', true), array('action' => 'index'));?></li>
	</ul>
</div>
