<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add/');
echo $this->element('admin/crumb');
?>

<div class="rewards form">
<?php echo $form->create('Reward', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('Add a Reward');?></legend>
	<?php
		echo $form->input('title', array('label' => 'Title *'));
		echo $form->input('description', array('label' => 'Description *'));
		echo $form->input('rrp', array('label' => 'RRP'));
		echo $form->input('points', array('label' => 'Points *'));
		echo $form->input('image',  array('type' => 'file'));
	?>
	</fieldset>
<?php echo $form->end('Add Reward');?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to Rewards', true), array('action'=>'index'));?></li>
	</ul>
</div>
