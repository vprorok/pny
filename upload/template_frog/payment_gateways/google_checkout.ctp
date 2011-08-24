<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the Google Checkout payment gateway.');?></h1>
    <p><?php __('If this page appears for more than 5 seconds, click the button below to continue.');?></p>
    <?php echo $googleCheckout->submit($data);?>
    <script type="text/javascript">
        $(document).ready(function(){
            document.getElementById('BB_BuyButtonForm').submit();
        });
    </script>
</div>