<div class="box clearfix">
	<div class="f-top-w clearfix"><h2><?php __('Suggestion box');?></h2>
	<p><center><?php __('Send us your suggestion using the suggestion form below');?></center></p></div>
	<div class="f-repeat clearfix">
		<div class="content">

			<?php echo $form->create(null, array('url' => '/suggestion')); ?>

			<fieldset class="contact">
				<legend></legend>
				<?php
				echo $form->input('name');
				echo $form->input('email', array('class' => 'disabled'));
				echo $form->input('message', array('type' => 'textarea'));
				echo $form->end(__('Send', true));
				?>
			</fieldset>
		</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>