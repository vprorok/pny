<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
	 <?php echo $form->create('firstdata', array('url' => $formurl));?>
		<fieldset>
			<legend><?php __('Enter payment information');?></legend>

			<?php echo $form->hidden('ordertype', array('value' => 'SALE')); ?>
			<?php echo $form->hidden('chargetotal', array('value' => $data['amount'])); ?>
			<?php echo $form->hidden('cvmindicator', array('value' => 'provided')); ?>
			<?php echo $form->hidden('debugging', array('value' => $data['debugging'])); ?>
			<?php echo $form->hidden('verbose', array('value' => $data['verbose'])); ?>

			<?php echo $form->input('cardnumber', array('label' => __('Credit card no *', true))); ?>

			<div class="input text">
				<label for="cardexp">Expiration date *</label>
				<?php echo $form->datetime('cardexp', 'MY', 'NONE', null, array('minYear' => date('Y'),
										  'maxYear' => date('Y')+15, 'label' => 'Expiration date *')); ?>
			</div>
			
			<?php	echo $form->input('cvmvalue', array('label' => __('CVV *', true)));
					echo $form->end(__('Save Changes', true));
			?>
		</fieldset>
	
   <script type="text/javascript">
        $(document).ready(function(){
            //document.frmPagseguro.submit();
        });
	</script>
</div>