<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
    <form name="frmAuthorizeNet" method="post" action="<?php echo Configure::read('PaymentGateways.AuthorizeNet.url');?>">
        <input type="hidden" name="x_invoice_num"     value="<?php echo date('YmdHis'); ?>" />
        <input type="hidden" name="x_description"     value="<?php echo $description; ?>" />
        <input type="hidden" name="x_amount"          value="<?php echo $amount; ?>" />
        <input type="hidden" name="x_login"           value="<?php echo $login; ?>" />

        <input type="hidden" name="x_relay_response"  value="TRUE" />
        <input type="hidden" name="x_relay_url"       value="<?php echo $x_relay_url; ?>" />

        <input type="hidden" name="x_fp_sequence"     value="<?php echo $sequence; ?>" />
        <input type="hidden" name="x_fp_timestamp"    value="<?php echo $timestamp; ?>" />
        <input type="hidden" name="x_fp_hash"         value="<?php echo $fingerprint; ?>" />

        <input type="hidden" name="control"           value="<?php echo $control; ?>" />
        <input type="hidden" name="timestamp"         value="<?php echo $timestamp; ?>" />
        <input type="hidden" name="fingerprint"       value="<?php echo $fingerprint; ?>" />
        <input type="hidden" name="sequence"          value="<?php echo $sequence; ?>" />

        <input type="hidden" name="x_first_name"         value="<?php echo $user['User']['first_name']; ?>" />
        <input type="hidden" name="x_last_name"         value="<?php echo $user['User']['last_name']; ?>" />
        <input type="hidden" name="x_cust_id"         value="<?php echo $user['User']['username']; ?>" />
        
        <input type="hidden" name="x_show_form" value='PAYMENT_FORM' />
        <?php if(!empty($test)):?>
            <input type="hidden" name="x_test_request" value="TRUE" />
        <?php endif;?>
        <input type="submit" value="<?php __('Click here if this page appears for more than 5 seconds');?>"/>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            document.frmAuthorizeNet.submit();
        });
    </script>
</div>