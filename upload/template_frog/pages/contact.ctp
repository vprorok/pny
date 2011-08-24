<div class="box clearfix">
	<div class="f-top-w clearfix"><h2><?php __('Contact Us');?></h2>
	<p><?php __('Please fill out the form below to get in touch with us');?> </p></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<p class="txt" style="padding-bottom:0; margin:0 0 20px;"><?php echo $j['contact_txt1']; ?></p>
			<p class="txt bold" style="paddin-top:0;"><?php echo $j['contact_txt2']; ?></p>

			<?php echo $form->create(null, array('url' => '/contact')); ?>

			<fieldset class="contact">
				<legend></legend>
				<?php
				echo $form->input('name', array('label' => $j['contact_name']));
				echo $form->input('email', array('class' => 'disabled', 'label' => $j['contact_mail']));
				if(!empty($departments)) :
					echo $form->input('department_id', array('label' => $j['contact_department'], 'empty' => 'Default', ));
				endif;
				echo $form->input('message', array('label' => $j['contact_desc'], 'type' => 'textarea'));
				?>
				<?php if(Configure::read('Recaptcha.enabled')):?>
					<label><?php __('Verification');?></label>
					<?php echo $recaptcha->getHtml(!empty($recaptchaError) ? $recaptchaError : null);?>
				<?php endif;?>
				
				<?php echo $form->end(__('Submit', true));?>
			</fieldset>
		</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>