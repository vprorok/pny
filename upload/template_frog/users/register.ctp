<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Register');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<?php echo $form->create('User', array('action' => 'register'));?>
			<fieldset>
				<?php
					echo $form->input('username', array('label' => __('Username *', true)));
					echo $form->input('before_password', array('value' => '', 'type' => 'password', 'label' => 'Password *'));
					echo $form->input('retype_password', array('value' => '', 'type' => 'password', 'label' => 'Retype Password *'));
					echo $form->input('first_name', array('label' => __('First Name *', true)));
					echo $form->input('last_name', array('label' => __('Last Name *', true)));
					echo $form->input('email', array('label' => __('Email *', true)));
					echo $form->input('date_of_birth', array('minYear' => $appConfigurations['Dob']['year_min'], 'maxYear' => $appConfigurations['Dob']['year_max'], 'label' => __('Date of Birth', true)));
					echo $form->input('gender_id', array('type' => 'select', 'label' => __('Gender', true)));
					echo $form->input('newsletter', array('label' => __('Sign up for the newsletter?', true)));
					echo $form->input('referrer', array('label' => __('Referred By', true)));
					?>
					<div class="hint"><?php __('Enter in the username or email address of someone that referred you.');?></div>
			</fieldset>

			<fieldset>
				<label><?php __('How did you find us? *');?></label>
				<div class="radio-group">
					<?php echo $form->hidden('source_id', array('value' => ''));?>
					<?php foreach($sources as $source):?>
						<input type="radio" <?php if(!empty($this->data['User']['source_id']) && $this->data['User']['source_id'] == $source['Source']['id']) echo 'checked="checked"';?> title="<?php echo $source['Source']['extra'];?>" id="source_<?php echo $source['Source']['id'];?>" name="data[User][source_id]" value="<?php echo $source['Source']['id'];?>"/>
						<label class="radio" for="source_<?php echo $source['Source']['id'];?>"><?php echo $source['Source']['name'];?></label>
					<?php endforeach;?>
					<?php echo $form->error('source_id');?>

					<div id="sourceExtraBlock" style="display: none">
						<?php echo $form->input('source_extra', array('id' => 'sourceExtra', 'label' => __('Source *', true)));?>
					</div>
				</div>
				<?php if(Configure::read('Recaptcha.enabled')):?>
					<label><?php __('Verification');?></label>
					<?php echo $recaptcha->getHtml(!empty($recaptchaError) ? $recaptchaError : null);?>
				<?php endif;?>

				<?php
					echo $form->input('terms', array('type' => 'checkbox', 'label' => 'You have read and accept the <a target="_blank" href="/page/terms">terms and conditions</a> and are over 18 years of age.'));
					echo $form->end('Register');
				?>
			</fieldset>


			<h3 class="heading"><?php __('Already a Member?');?></h3>
			<p><?php echo sprintf(__('If so you may want to %s now.', true), $html->link(__('login', true), array('action'=>'login')));?></p>

			<h3><?php __('Forgotten Your Password?');?></h3>
			<p><?php echo sprintf(__('Click here to %s.', true), $html->link(__('reset your password', true), array('action'=>'reset')));?>
			</p>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.radio-group input').click(function(){
			if($(this).attr('title')){
				if($(this).attr('title') == 1){
					$('#sourceExtraBlock').show(1);
				}else{
					$('#sourceExtraBlock').hide(1);
					$('#sourceExtra').val('');
				}
			}
		});

		if($('.radio-group input:checked').attr('title') == 1){
			$('#sourceExtraBlock').show(1);
		}
	});
</script>
