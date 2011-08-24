<p><?php echo sprintf(__('Dear %s', true), $appConfigurations['name']);?>,</p>

<p><?php echo sprintf(__('The following auction titled %s has sold', true), $data['Product']['title']);?></p>

<p><?php __('To view the details for this auction please visit the following link:');?></p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/auctions/view/<?php echo $data['Auction']['id'];?>">
        <?php echo $appConfigurations['url'];?>/auctions/view/<?php echo $data['Auction']['id'];?>
    </a>
</p>