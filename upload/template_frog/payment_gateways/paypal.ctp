<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
    <?php echo $paypal->submit(__('Click here if this page appears for more than 5 seconds', true), $paypalData);?>
    <script type="text/javascript">
        document.getElementById('frmPaypal').submit();
    </script>
</div>