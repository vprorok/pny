<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Change Password');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
					$html->addCrumb(__('Change Password', true), '/users/changepassword');
					echo $this->element('crumb_user');
				?>
				
				<fieldset>
					<?php echo $form->create('User', array('url' => '/users/changepassword'));?>
					<legend><?php __('Change Password');?></legend>
					<p><?php __('To change your password enter in your old password and your new password twice (Disabled in DEMO).');?></p>
					<?php
						echo $form->input('old_password', array('value' => '', 'type' => 'password'));
						echo $form->input('before_password', array('value' => '', 'type' => 'password', 'label' => __('New Password', true)));
						echo $form->input('retype_password', array('value' => '', 'type' => 'password'));
					?>
					<?php if (isset($is_scd) && $is_scd===true): ?>
						<?php echo $form->end(__('Change Password', true));?>
					<?php else: ?>
						<?php echo $form->end(__('Change Password', true));?>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>