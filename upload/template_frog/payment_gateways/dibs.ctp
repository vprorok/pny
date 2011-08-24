<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
    <form name="payform" method="post" action="https://payment.architrade.com/paymentweb/start.action">
		<input type="hidden" name="merchant" value="<?php echo $data['merchant'];?>" />
		<input type="hidden" name="orderid" value="<?php echo $data['orderid'];?>" />
		<input type="hidden" name="lang" value="<?php echo $data['lang'];?>" />
		<input type="hidden" name="amount" value="<?php echo $data['amount'];?>" />
		<input type="hidden" name="currency" value="<?php echo $data['currency'];?>" />
		<input type="hidden" name="accepturl" value="<?php echo $data['accepturl'];?>" />
		<input type="hidden" name="callbackurl" value="<?php echo $data['callbackurl'];?>" />
		<input type="hidden" name="cancelurl" value="<?php echo $data['cancelurl'];?>" />
		<input type="hidden" name="delivery1.Navn" value="<?php echo $data['name'];?>" />

		<?php if(!empty($data['address'])):?>
			<input type="hidden" name="delivery2.Adresse" value="<?php echo $data['address'];?>" />
		<?php endif;?>

		<input type="hidden" name="ordline0-1" value="id" />
		<input type="hidden" name="ordline0-2" value="title" />
		<input type="hidden" name="ordline0-3" value="control" />

		<input type="hidden" name="ordline1-1" value="<?php echo $data['item_id'];?>" />
		<input type="hidden" name="ordline1-2" value="<?php echo $data['item_title'];?>" />
		<input type="hidden" name="ordline1-3" value="<?php echo $data['item_control'];?>" />

        <input type="submit" value="<?php __('Click here if this page appears for more than 5 seconds');?>"/>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            document.frmAuthorizeNet.submit();
        });
    </script>
</div>