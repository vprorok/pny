	<?php
	$html->addCrumb(__('Manage Users', true), '/admin/users');
	$html->addCrumb(__('Users', true), '/admin/users');
	$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['User']['id']);
	$html->addCrumb(__('Change Password', true), '/admin/users/changepassword');
	echo $this->element('admin/crumb');
	?>

	<?php echo $form->create('User', array('url' => '/admin/users/changepassword/'.$user['User']['id']));?>
		<fieldset>
			<legend><?php __('Change Password');?> &mdash; <?= $user['User']['username'] ?></legend>
			<?php
				echo $form->input('before_password', array('value' => '', 'type' => 'password', 'label' => __('New Password', true)));
				echo $form->input('retype_password', array('value' => '', 'type' => 'password'));
			?>
		</fieldset>
	<?php echo $form->end(__('Change Password', true));?>