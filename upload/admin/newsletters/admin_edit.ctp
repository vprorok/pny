<?php
$html->addCrumb(__('Newsletter', true), '/admin/newsletter');
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Newsletter']['id']);
echo $this->element('admin/crumb');
?>

<div class="newsletters form">
<?php echo $form->create('Newsletter');?>
	<fieldset>
 		<legend><?php __('Edit Newsletter');?></legend>

 		<h3><?php __('Pre Defined Variables', true);?></h3>

 		<p><?php __('By entering in the following variables in either the subject or body of the newsletter, the variables will be replaced by the users details:');?><br />
 		<?php __('First Name');?>: {first_name}<br />
 		<?php __('Last Name');?>: {last_name}<br />
 		<?php __('Email Address');?>: {email}<br />
 		<?php __('Username');?>: {username}
 		</p>
	<?php
		echo $form->input('id');
		echo $form->input('subject');

		echo $form->label('Body');
		echo $fck->input('Newsletter.body');
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action'=>'delete', $form->value('Newsletter.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Newsletter.id'))); ?></li>
		<li><?php echo $html->link(__('List Newsletters', true), array('action'=>'index'));?></li>
	</ul>
</div>
