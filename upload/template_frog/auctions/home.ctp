<?php
if(!empty($auctions_end_soon)) : ?>
<div id="ending-soon" class="box" style="margin-top:0;">
	<div class="f-top-w clearfix"style="height:60px;">
	
	<div class="top_h2_l"><h2 class="top"><label><?=__('Get Bidding! Auctions ending soon')?></label></h2></div>
	<div class="top_h2_r">
	<!-- Free guide start -->
<a href="#" id="trigger_topguide"><?= $html->image('parts/help_icon.png', array('width'=>"19",'height'=>"19",'border'=>"0"))?></a>
<div class="tooltip_t">
<p class="bold orange2" style="margin-bottom:3px;"><?=__('View list of auctions');?></p>
<?= __('Every time a bid is placed, the auction extends its time by a set amount. When it hits zero, you win!') ?> 
<?= $html->image('/img/guide/topguide.jpg', array('width'=>"320",'height'=>"290",'alt'=>"", 'title'=>"")) ?>
</div>
<script>
$(function() {
$("#trigger_topguide").tooltip();
});
</script>
<!-- Free guide end -->
<label>	
<?= __('Bid until the countdown hits zero. The most recent bidder wins!') ?></label></div>
	<br class="clear_br">
	
	</div>
	<div class="f-repeat clearfix">
		<div class="content" style="padding:20px 20px 20px 30px !important;">
			<ul class="horizontal-bid-list">
				<?php foreach($auctions_end_soon as $auction):?>
				<li class="auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>"><?php if(!empty($auction['Product']['fixed'])):?><a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="fixed"></span></a><?php endif; ?><?php if(!empty($auction['Auction']['free'])) : ?><a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="free"></span></a><?php endif; ?>
					<div class="content">
						<h3><?php echo $html->link($text->truncate($auction['Product']['title'],25), AppController::AuctionLink($auction['Auction']['id'], $auction['Product']['title']));?></h3>
						<div class="thumb clearfix">
							<a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="<?php if(!empty($auction['Auction']['penny'])):?> penny<?php endif;?><?php if(!empty($auction['Auction']['peak_only'])):?> peak_only<?php endif;?><?php if(!empty($auction['Auction']['nail_bitter'])):?> nail<?php endif;?><?php if(!empty($auction['Auction']['beginner'])):?> beginner<?php endif;?> <?php if(!empty($auction['Auction']['featured'])):?> featured<?php endif;?>"></span>
							<?php 
							if(!empty($auction['Product']['Image']) && !empty($auction['Product']['Image'][0]['image'])) {
								echo $html->image('product_images/thumbs/'.$auction['Product']['Image'][0]['image']);
							} else {
								echo $html->image('product_images/thumbs/no-image.gif');
							} 
							?>
							</a>
						</div>
						<div id="timer_<?php echo $auction['Auction']['id'];?>" class="timer countdown clearfix" title="<?php echo $auction['Auction']['end_time'];?>">--:--:--</div>
						<div class="price clearfix">
								<?php if(!empty($auction['Product']['fixed'])):?>
									<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']);?>
									<span class="bid-price-fixed" style="display: none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
								<?php else: ?>
									<span class="bid-price"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
								<?php endif; ?>
						</div>
						<div class="username bid-bidder clearfix"><?=__('The highest bidder')?></div>
						<div class="bid-now clearfix">
							<?php if(!empty($auction['Auction']['isFuture'])) : ?>
								<div><?php echo $html->image('button/b-soon.gif', array('width'=>"94",'height'=>"32",'alt'=>"",'title'=>"")) ?></div>
							 <?php elseif(!empty($auction['Auction']['isClosed'])) : ?>
								<div><?php echo $html->image('button/b-sold.gif', array('width'=>"94",'height'=>"32",'alt'=>"",'title'=>"")) ?></div>
							 <?php else:?>
								 <?php if($session->check('Auth.User')):?>
									<div class="bid-loading" style="display: none"><?php echo $html->image('ajax-arrows.gif');?></div>
									 <div class="bid-button"><a class="bid-button-link button-small" title="<?php echo $auction['Auction']['id'];?>" href="/bid.php?id=<?php echo $auction['Auction']['id'];?>">Bid</a></div>
								<?php else:?>
									<div class="bid-button"><a href="/users/login" class="b-login"><?php __(@$j['menu_my_login']);?></a></div>
								<?php endif;?>
							<?php endif; ?>
						</div>
						<div class="bid-msg">
						<div class="bid-message"></div>
						</div>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
			<br class="clear_br">
			<div class="see-more float-right">
				<?php echo $html->link(__('More &raquo;', true), array('action' => 'index'), null, null, false);?>
			</div>
			<br class="clear_br">
		</div>
		
		
		
		
		
		
			<?php if (isset($future_auctions) && !empty($future_auctions)): ?>
			<h2 class="top" style="margin-left:30px"><legend>Upcoming Auctions:</legend></h2>
			<div class="content" style="padding:20px 20px 20px 30px !important;">
			<ul class="horizontal-bid-list">
				<?php foreach($future_auctions as $auction):?>
				<li class="auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>"><?php if(!empty($auction['Product']['fixed'])):?><a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="fixed"></span></a><?php endif; ?><?php if(!empty($auction['Auction']['free'])) : ?><a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="free"></span></a><?php endif; ?>
					<div class="content">
						<h3><?php echo $html->link($text->truncate($auction['Product']['title'],25), AppController::AuctionLink($auction['Auction']['id'], $auction['Product']['title']));?></h3>
						<div class="thumb clearfix">
							<a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="<?php if(!empty($auction['Auction']['penny'])):?> penny<?php endif;?><?php if(!empty($auction['Auction']['peak_only'])):?> peak_only<?php endif;?><?php if(!empty($auction['Auction']['nail_bitter'])):?> nail<?php endif;?><?php if(!empty($auction['Auction']['featured'])):?> featured<?php endif;?>"></span>
							<?php 
							if(!empty($auction['Product']['Image']) && !empty($auction['Product']['Image'][0]['image'])) {
								echo $html->image('product_images/thumbs/'.$auction['Product']['Image'][0]['image']);
							} else {
								echo $html->image('product_images/thumbs/no-image.gif');
							} 
							?>
							</a>
						</div>
						<div id="timer_<?php echo $auction['Auction']['id'];?>" class="timer countdown clearfix" title="<?php echo $auction['Auction']['end_time'];?>">--:--:--</div>
						<div class="price clearfix">
								<?php if(!empty($auction['Product']['fixed'])):?>
									<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']);?>
									<span class="bid-price-fixed" style="display: none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
								<?php else: ?>
									<span class="bid-price"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
								<?php endif; ?>
						</div>
						<div class="username bid-bidder clearfix"><?=__('The highest bidder')?></div>
						<div class="bid-now clearfix">
							<?php if(!empty($auction['Auction']['isFuture'])) : ?>
								<div><?php echo $html->image('button/b-soon.gif', array('width'=>"94",'height'=>"32",'alt'=>"",'title'=>"")) ?></div>
							 <?php elseif(!empty($auction['Auction']['isClosed'])) : ?>
								<div><?php echo $html->image('button/b-sold.gif', array('width'=>"94",'height'=>"32",'alt'=>"",'title'=>"")) ?></div>
							 <?php else:?>
								 <?php if($session->check('Auth.User')):?>
									<div class="bid-loading" style="display: none"><?php echo $html->image('ajax-arrows.gif');?></div>
								<div><?php echo $html->image('button/b-soon.gif', array('width'=>"94",'height'=>"32",'alt'=>"",'title'=>"")) ?></div>
								<?php else:?>
									<div class="bid-button"><a href="/users/login" class="b-login"><?php __(@$j['menu_my_login']);?></a></div>
								<?php endif;?>
							<?php endif; ?>
						</div>
						<div class="bid-msg">
						<div class="bid-message"></div>
						</div>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
			<br class="clear_br">
			<div class="see-more float-right">
				<?php echo $html->link(__('More &raquo;', true), array('action' => 'future'), null, null, false);?>
			</div>
			<br class="clear_br">
		</div>
		<?php endif; ?>
		
		
		
		
		
		
	</div>
	<div id="news-info" class="f-repeat clearfix">
		<div class="content" style="padding:10px 25px; margin-bottom:10px;">
			<div class="col1">
			<?php echo $this->element('latest_news'); ?>
			</div>
			<div class="col2">
			<?php echo $this->element('latest_winner'); ?>
			</div>
			<div class="col3">
			<h3 class="orange2 bold font-14" style="margin:0;"><label><? __('Auction News')?></label></h3>
				<div id="twitter_div" style="width: 240px;height: 128px;overflow: auto;overflow-x: hidden;overflow-y: hidden; margin-bottom:13px;">
				<ul id="twitter_update_list" class="tw_list"></ul>
				</div>

<p class="more" style="border-top:1px dotted #DEDEDE;"><a href="http://twitter.com/<?php echo Configure::read('Twitter.username'); ?>" target="_blank">Twitter<img src="<?php echo $this->webroot; ?>img/parts/external.png" width="10" height="10" border="0" align="middle" style="vertical-align: baseline; margin-left:3px;"></a></p>
			</div>
		</div>
	</div>
<?php if(!empty($auctions_live)) : ?>
	<div class="f-bottom clearfix">&nbsp;</div>
<?php else:?>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top"><?= __('PAGE TOP') ?></a></p></div>
<?php endif; ?>
</div>
<?php endif; ?>


<?php if(!empty($auctions_live)) : ?>
<div id="live-bids" class="box">
	<div class="f-top-w clearfix" style="height:60px;"><h2 class="top"><label><?=__('Other recommended auctions')?></label></h2></div>
	<div class="f-repeat clearfix">
		<div class="content" style="padding-top:5px;">
			<div class="bid-heading clearfix">
				<div class="content">
					<div class="col1">&nbsp;</div>
					<div class="col2">&nbsp;</div>
					<div class="col3"><?= __('Price')?></div>
					<div class="col4"><?= __('Bidder') ?></div>
					<div class="col5"><?= __('Time left')?></div>
				</div>
			</div>
			<ul class="vertical-bid-list">
				<?php foreach($auctions_live as $auction):?>
				<li class="auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>">
					<div class="content">
						<div class="col1 thumb">
							<a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="<?php if(!empty($auction['Auction']['penny'])):?> penny<?php endif;?><?php if(!empty($auction['Auction']['peak_only'])):?> peak_only<?php endif;?><?php if(!empty($auction['Auction']['beginner'])):?> beginner<?php endif;?> <?php if(!empty($auction['Auction']['nail_bitter'])):?> nail<?php endif;?><?php if(!empty($auction['Auction']['featured'])):?> featured<?php endif;?><?php if(empty($auction['Auction']['nail_bitter']) && empty($auction['Auction']['penny']) && empty($auction['Auction']['featured']) && empty($auction['Auction']['peak_only'])):?> glossy<?php endif;?>"></span>
							<?php
							if(!empty($auction['Product']['Image']) && !empty($auction['Product']['Image'][0]['image'])) {
								echo $html->image('product_images/thumbs/'.$auction['Product']['Image'][0]['image']);
							} else {
								echo $html->image('product_images/thumbs/no-image.gif');
							}
							?>
							</a>
						</div>
						<div class="col2">
							<h3 class="heading"><?php echo $html->link($auction['Product']['title'], AppController::AuctionLink($auction['Auction']['id'], $auction['Product']['title']));?></h3>
							<?php echo strip_tags($text->truncate($auction['Product']['brief'], 120, '...', false, true));?>
							
							<div><?php if(!empty($auction['Auction']['free'])) : ?><a href="/page/auction_type" class="side_2px"><img src="<?php echo $this->webroot; ?>badge/free_label_vertical.png" width="101" height="16" border="0" alt="Free auctions" title="Free auctions"></a><?php endif; ?>
							<?php if(!empty($auction['Product']['fixed'])) : ?><a href="/page/auction_type" class="side_2px"><img src="<?php echo $this->webroot; ?>/badge/fixed_label_vertical.png" width="101" height="16" border="0" alt="Fixed price auction" title="Fixed price auction"></a><?php endif; ?></div>
						</div>
						<div class="col3">
							<div class="price">
								<?php if(!empty($auction['Product']['fixed'])) : ?>
									<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']); ?>
									<span class="bid-price-fixed" style="display:none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
								<?php else: ?>
									<div class="bid-price"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></div>
								<?php endif; ?>
							</div>
							<?php if(!empty($auction['Product']['rrp'])): ?>
								<div class="rrp"><?= __('Retail Price')?> : <?php echo $number->currency($auction['Product']['rrp'], $appConfigurations['currency']); ?></div>
							<?php endif; ?>

						</div>
						<div class="col4 bid-bidder"><?=__('The highest bidder')?></div>
						<div class="col5">
							 <div id="auctionLive_<?php echo $auction['Auction']['id'];?>" class="timer countdown" title="<?php echo $auction['Auction']['end_time'];?>">--:--:--</div>
							<div class="bid-now">
								<?php if(!empty($auction['Auction']['isFuture'])) : ?>
									<div><?= $html->image('/button/b-soon-w.gif', array('width'=>"94", 'height'=>"32", 'alt'=>__("Please wait until the auction starts"), 'title'=>__("Please wait until the auction starts"))) ?></div>
								 <?php elseif(!empty($auction['Auction']['isClosed'])) : ?>
									<div><?= $html->image('/button/b-sold-w.gif', array('width'=>"94", 'height'=>"32", 'alt'=>"", 'title'=>"")) ?>></div>
								 <?php else:?>
									 <?php if($session->check('Auth.User')):?>
										<div class="bid-loading" style="display: none"><?php echo $html->image('ajax-arrows.gif');?></div>
										<div class="bid-button"><a class="bid-button-link button-small-vertical" title="<?php echo $auction['Auction']['id'];?>" href="/bid.php?id=<?php echo $auction['Auction']['id'];?>"><?= __('Bid') ?></a></div>
									<?php else:?>
										<div class="bid-button"><a href="/users/login" class="b-login-vertical"><?php __(@$j['menu_my_login']);?></a></div>
									<?php endif;?>
								<?php endif; ?>
							<div class="bid-message"></div>
							</div>
						</div>
					</div>
				</li>
				<?php endforeach;?>
			</ul>
	        <br class="clear_br">
	        <div class="see-more float-right">
	        	<?php echo $html->link(__('More &raquo;', true), array('action' => 'index'), null, null, false);?>
	        </div>
	        <br class="clear_br">
		</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top"><?=__('PAGE TOP')?></a></p></div>
</div>
<?php endif; ?>