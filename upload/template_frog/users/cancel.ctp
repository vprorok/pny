<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Cancel Account');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
				$html->addCrumb(__('Cancel Account', true), '/users/cancel');
				echo $this->element('crumb_user');
				?>

                <h1>Cancel Account</h1>
                <p>Are you sure you want to cancel your account?</p>

				<?php echo $form->create('User', array('action' => 'cancel'));?>
				<fieldset>
					<?php
						echo $form->hidden('security', array('value' => $security));
						echo $form->end(__('Yes, Cancel My Account', true));
					?>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>