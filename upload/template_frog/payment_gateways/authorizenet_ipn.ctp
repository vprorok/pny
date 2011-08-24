<div style="text-align: center">
    <h1><?php echo $message;?></h1>
    <?php if(!empty($next_url)):?>
        <a href="<?php echo $next_url;?>" title="Continue"><?php __('Continue to your account');?></a>
    <?php endif;?>
</div>