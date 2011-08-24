<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Login');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<fieldset>
				<legend></legend>
				<?php echo $form->create('User', array('action' => 'login'));?>
				<?php
					echo $form->input('username', array('label' => __('Username', true)));
					echo $form->input('password', array('label' => __('Password', true)));
					echo '<div class="checkbox">';
					echo $form->checkbox('remember_me');
					echo $form->label('remember_me', __('Remember Me', true), array('class' => 'nofloat'));
					echo '</div>';
					echo $form->end('Login');
				?>
			</fieldset>
			
			<h3 class="heading"><?php __('Don\'t have an account?');?></h3>
			<p><?php echo sprintf(__('If so you may want to %s now.', true), $html->link(__('sign up', true), array('action'=>'register')));?></p>
			
			<h2><?php __('Forgotten Your Password?');?></h2>
			<p><?php echo sprintf(__('Click here to %s.', true), $html->link(__('reset your password', true), array('action'=>'reset')));?>
			</p>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>