
<div class="box clearfix">
	<div class="f-top clearfix">
		<h1><?php __('USA ePay - Continue to paying');?></h1>
	   <? if(isset($message) && !empty($message)): ?>
			<h1><?php echo $message;?></h1>
		<?endif;?>
	</div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div>
			<div id="rightcol">
				<?php
				//$html->addCrumb(__('Edit Profile', true), '/users/edit');
				//echo $this->element('crumb_user');
				?>

				<?php echo $form->create('Usaepay', array('url' => $formurl));?>
				<fieldset>
					<legend><?php __('Enter payment information');?></legend>

					<?php
						echo $form->input('card', array('label' => __('Credit card no *', true)));
						?>
					<? echo $form->input('cardholder', array('label' => __('Firstname lastname *', true))); ?>
					<? echo $form->input('street', array('label' => __('Street *', true))); ?>
					<? echo $form->input('zip', array('label' => __('Zip *', true))); ?>
					

					<div class="input text">
						<label for="Usaepayexp">Expiration date *</label>

						<?=$form->datetime('exp', 'MY', 'NONE', null, array('minYear' => date('Y'),
										'maxYear' => date('Y')+15, 'label' => 'Expiration date *'));
						?>
					</div>
					<?
						echo $form->input('cvv2', array('label' => __('CVV2 *', true)));
						echo $form->end(__('Save Changes', true));
					?>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>
