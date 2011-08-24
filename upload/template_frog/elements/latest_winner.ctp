<?php if(!empty($latest_winner)) : ?>
<h3 class="orange2 bold font-14" style="margin:0;"><label>Latest winner</label></h3>
<div class="latest-winner">
<div class="title">
	<?php echo $html->link($text->truncate($latest_winner['Product']['title'], 28), array('action' => 'view', $latest_winner['Auction']['id']));?></div>
<div class="thumb">
	<a href="/auctions/view/<?php echo $latest_winner['Auction']['id']; ?>"><span class="<?php if(!empty($auction['Auction']['penny'])):?> penny<?php endif;?><?php if(!empty($auction['Auction']['peak_only'])):?> peak_only<?php endif;?><?php if(!empty($auction['Auction']['nail_bitter'])):?> nail<?php endif;?><?php if(!empty($auction['Auction']['featured'])):?> featured<?php endif;?><?php if(empty($auction['Auction']['nail_bitter']) && empty($auction['Auction']['penny']) && empty($auction['Auction']['featured']) && empty($auction['Auction']['peak_only'])):?> glossy<?php endif;?>"></span>
	<?php if(!empty($latest_winner['Product']['Image'][0]['image'])):?>
		<?php if(!empty($latest_winner['Product']['Image'][0]['ImageDefault']['image'])) : ?>
			<?php echo $html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$latest_winner['Product']['Image'][0]['ImageDefault']['image']); ?>
		<?php else: ?>
			<?php echo $html->image('product_images/thumbs/'.$latest_winner['Product']['Image'][0]['image']); ?>
		<?php endif; ?>
	<?php else:?>
		<?php echo $html->image('product_images/thumbs/no-image.gif');?>
	<?php endif;?>
	</a>
</div>

<div class="bold align-center orange2 congres"><label>Congratulations </label></div>
<div class="bold align-center orange2 name"><label><?php echo $text->truncate($latest_winner['Winner']['username'], 15); ?>!</label></div>
    <div class="align-center">
    <label><?php __('End price');?> :</label><span class="price">
    <?php if(!empty($latest_winner['Product']['fixed'])) : ?>
		<?php echo $number->currency($latest_winner['Product']['fixed_price'], $appConfigurations['currency']); ?>
	<?php else: ?>
		<?php echo $number->currency($latest_winner['Auction']['price'], $appConfigurations['currency']); ?>
	<?php endif; ?>
    </span></div>
    <div class="align-center">
    <label>End time :  </label><span><?php echo $time->format('g:ia', $latest_winner['Auction']['end_time']); ?></span>
    </div><br class="clear_br">
<div class="more">
	<p class="more"><?php echo $html->link(__('View More', true), array('action' => 'winners'));?></p>
</div>
</div>
<?php else : ?>
	<p><?php __('There are no winners yet!');?></p>
<?php endif; ?>