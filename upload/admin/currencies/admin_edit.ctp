<?php
$html->addCrumb(__('General Settings', true), '/admin/settings');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Currency']['id']);
echo $this->element('admin/crumb');
?>

<div class="currencies form">
<?php echo $form->create('Currency');?>
	<fieldset>
 		<legend><?php __('Edit a Currency:');?> <?php echo $this->data['Currency']['currency']; ?></legend>
	<?php
		echo $form->input('id');
		echo $form->hidden('currency');
		echo $form->input('rate');
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to currencies', true), array('action' => 'index'));?></li>
	</ul>
</div>
