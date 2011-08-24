<?php echo sprintf(__('Dear %s', true), $appConfigurations['name']);?>,

<?php echo sprintf(__('The following auction titled %s has sold', true), $data['Product']['title']);?>

<?php __('To view the details for this auction please visit the following link:');?>

<?php echo $appConfigurations['url'];?>/auctions/view/<?php echo $data['Auction']['id'];?> 