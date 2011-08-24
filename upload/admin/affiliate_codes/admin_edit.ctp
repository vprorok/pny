<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['AffiliateCode']['id']);
echo $this->element('admin/crumb');
?>

<?php echo $form->create('AffiliateCode');?>
	<fieldset>
 		<legend><?php __('Edit an Affiliate Code');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('user_id', array('label' => 'Username *', 'empty' => 'Select User'));
		echo $form->input('code', array('label' => 'Code *'));
		echo $form->input('credit', array('label' => 'Credit *'));
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to affiliate codes', true), array('action' => 'index')); ?> </li>
	</ul>
</div>