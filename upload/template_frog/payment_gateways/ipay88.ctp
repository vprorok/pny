<div class="payment-redirect">
	<h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
	<form name="frmIpay88" method="post" action="<?php echo Configure::read('PaymentGateways.iPay88.url');?>">


		<?php foreach($data as $key=>$val): ?>

			<input type="hidden" name="<?php echo $key;?>" value="<?php echo $val;?>"/>

		<?php endforeach; ?>

		<?php echo $form->end(__('Proceed payment', true)); ?>

		<!--
		<input type="hidden"name="MerchantCode" value="<?php echo $data['MerchantCode'];?>">
		<input type="hidden" name="PaymentId" value="echo $data">
		<input type="hidden" name="RefNo" value="A00000001">
		<input type="hidden" name="Amount" value="1.00">
		<input type="hidden" name="Currency" value="MYR">
		<input type="hidden" name="ProdDesc" value="Photo Print">
		<input type="hidden" name="UserName" value="John Doe">
		<input type="hidden" name="UserEmail" value="john@gmail.com">
		<input type="hidden" name="UserContact" value="0126500100">
		<input type="hidden" name="Remark" value="">
		<input type="hidden" name="Lang"   value="UTF-8">
		<input type="hidden" name="Signature" value="84dNMbfgjLMS42IqSTPqQ99cUGA=">
		<input type="hidden" name="ResponseURL" value="http://www.YourResponseURL.com/payment/response.asp">
		
		optional <input type="hidden" name="PaymentId" value="">
		-->

		
   </form>
   <script type="text/javascript">
       $(document).ready(function(){
           //document.frmIpay88.submit();
       });
   </script>
</div>