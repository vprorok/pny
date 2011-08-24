<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Reward']['id']);
echo $this->element('admin/crumb');
?>

<div class="rewards form">
<?php echo $form->create('Reward');?>
	<fieldset>
 		<legend><?php __('Edit a Reward');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('title', array('label' => 'Title *'));
		echo $form->input('description', array('label' => 'Description *'));
		echo $form->input('rrp', array('label' => 'RRP'));
		echo $form->input('points');
	?>
	</fieldset>
<?php echo $form->end('Save Changes');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to Rewards', true), array('action'=>'index'));?></li>
	</ul>
</div>