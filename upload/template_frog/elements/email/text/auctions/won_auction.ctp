<?php echo sprintf(__('Hi %s', true), $data['User']['first_name']);?>,

<?php echo sprintf(__('Congratulations!  You have successfully won an auction listed at %s titled: %s', true), $appConfigurations['name'], $data['Product']['title']);?>

<?php __('To view the details and pay for this auction please visit the following link:');?>

<?php echo $appConfigurations['url'];?>/auctions/pay/<?php echo $data['Auction']['id'];?>

<?php __('Thank You');?>
<?php echo $appConfigurations['name'];?>

<?php __('If you never registered at our website, please contact the administrator by submitting the form on our contact page.');?>
