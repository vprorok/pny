<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Edit Image', true), '/admin/'.$this->params['controller'].'/image/'.$this->data['Reward']['id']);
echo $this->element('admin/crumb');
?>

<div class="rewards form">

<?php if(!empty($this->data['Reward']['image'])) : ?>
	<h3>Current Image</h3>
	
	<p><?php echo $html->image('rewards/max/'.$this->data['Reward']['image']); ?></p>
	<p>&nbsp;</p>
<?php endif; ?>

<?php echo $form->create('Reward', array('type' => 'file', 'url' => '/admin/rewards/image/'.$this->data['Reward']['id']));?>
	<fieldset>
 		<legend><?php __('Upload a New Image');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('image', array('type' => 'file'));
	?>
	</fieldset>
<?php echo $form->end('Upload New Image >>');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to Rewards', true), array('action'=>'index'));?></li>
	</ul>
</div>