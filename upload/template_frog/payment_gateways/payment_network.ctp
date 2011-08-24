<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
    <form name="frmPaymentNetwork" method="post" action="https://www.directebanking.com/payment/start">
        <input type="hidden" name="user_id"         value="<?php echo $gateway['user_id']; ?>" />
        <input type="hidden" name="project_id"      value="<?php echo $gateway['project_id']; ?>" />
        <input type="hidden" name="currency_id"     value="<?php echo $gateway['currency_id']; ?>" />
        <input type="hidden" name="language_id"     value="<?php echo $gateway['language_id']; ?>" />

        <input type="hidden" name="amount"          value="<?php echo $amount; ?>" />
        <input type="hidden" name="reason_1"        value="<?php echo $description; ?>" />
        <input type="hidden" name="hash"            value="<?php echo $hash; ?>" />

        <input type="hidden" name="user_variable_0" value="<?php echo $control; ?>" />

        <input type="submit" value="<?php __('Click here if this page appears for more than 5 seconds');?>"/>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            document.frmPaymentNetwork.submit();
        });
    </script>
</div>