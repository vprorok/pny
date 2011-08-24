<div class="users login-page">
	<h1 class="page-title"><?php __('Login');?></h1>

	<div class="form-block">
		<div class="content">
			<div class="col1">
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
							echo '<div>';
							echo $form->end('Login');
							echo $form->end();
						?>
					</fieldset>
				</div>
			</div>

			<div class="col2">
				<div class="content">
					<h3 class="heading"><?php __('Don\'t have an account?');?></h3>
					<p><?php echo sprintf(__('If so you may want to %s now.', true), $html->link(__('sign up', true), array('action'=>'register')));?></p>

					<h2><?php __('Forgotten Your Password?');?></h2>
					<p><?php echo sprintf(__('Click here to %s.', true), $html->link(__('reset your password', true), array('action'=>'reset')));?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if(Configure::read('GoogleTracking.registration.active')) : ?>
	<!-- Google Code for Registration Conversion Page -->
	<script language="JavaScript" type="text/javascript">
	<!--
	var google_conversion_id = <?php echo Configure::read('GoogleTracking.registration.id'); ?>;
	var google_conversion_language = "<?php echo Configure::read('GoogleTracking.registration.language'); ?>";
	var google_conversion_format = "<?php echo Configure::read('GoogleTracking.registration.format'); ?>";
	var google_conversion_color = "<?php echo Configure::read('GoogleTracking.registration.color'); ?>";
	var google_conversion_label = "<?php echo Configure::read('GoogleTracking.registration.label'); ?>";
	//-->
	</script>
	<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
	<img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/<?php echo Configure::read('GoogleTracking.registration.id'); ?>/?label=<?php echo Configure::read('GoogleTracking.registration.label'); ?>&amp;guid=ON&amp;script=0"/>
	</noscript>
<?php endif; ?>