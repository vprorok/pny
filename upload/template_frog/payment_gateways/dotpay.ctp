<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
    <?php echo $dotpay->submit($data, __('Click here if this page appears for more than 5 seconds', true));?>
    <script type="text/javascript">
        document.getElementById('frmDotpay').submit();
    </script>
</div>