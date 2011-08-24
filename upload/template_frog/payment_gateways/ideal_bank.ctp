<div class="payment-redirect">
    <h1><?php __('iDeal - Continue to Your Bank');?></h1>
    <?php if(!empty($result)):?>
        <a href="<?php echo $ideal_url;?>"><?php __('Click here if this page appears for more than 5 seconds.');?></a>
        <script type="text/javascript">
            $(document).ready(function(){
                document.location = '<?php echo $ideal_url;?>';
            });
        </script>
    <?php else:?>
        <div class="message"><?php __('Cannot establish a connection to your bank. Please try again');?></div>
        <div class="notice"><?php echo sprintf(__('Reason: %s', true), $ideal_error);?></div>
        <a href="javascript: history.go(-1);"><?php __('Back');?></a>
    <?php endif;?>
</div>