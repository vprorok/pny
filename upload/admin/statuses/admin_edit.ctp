<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Auction Statuses', true), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Status']['id']);
echo $this->element('admin/crumb');
?>

<?php echo $form->create();?>
	<fieldset>
 		<legend><?php __('Edit a Status');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('message');
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to statuses', true), array('action' => 'index'));?></li>
	</ul>
</div>
