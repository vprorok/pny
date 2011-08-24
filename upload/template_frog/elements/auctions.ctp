<ul class="horizontal-bid-list">
	<?php foreach($auctions as $auction):?>
	<li class="auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>"><?php if(!empty($auction['Product']['fixed'])):?><a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="fixed"></span></a><?php endif; ?><?php if(!empty($auction['Auction']['free'])) : ?><a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="free"></span></a><?php endif; ?>
		<div class="content">
			<h3><?php echo $html->link($text->truncate($auction['Product']['title'],25), AppController::AuctionLink($auction['Auction']['id'], $auction['Product']['title']));?></h3>
			<div class="thumb clearfix">
				<a href="<?php echo AppController::AuctionLinkFlat($auction['Auction']['id'], $auction['Product']['title']); ?>"><span class="<?php if(!empty($auction['Auction']['penny'])):?> penny<?php endif;?><?php if(!empty($auction['Auction']['peak_only'])):?> peak_only<?php endif;?><?php if(!empty($auction['Auction']['beginner'])):?> beginner<?php endif;?> <?php if(!empty($auction['Auction']['nail_bitter'])):?> nail<?php endif;?><?php if(!empty($auction['Auction']['featured'])):?> featured<?php endif;?>"></span>
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
			<div class="username bid-bidder clearfix">Highest bidder</div>
			<div class="bid-now clearfix">
				<?php if(!empty($auction['Auction']['isFuture'])) : ?>
					<div><img src="<?php echo $this->webroot; ?>img/button/b-soon.gif" width="94" height="32" alt="Please wait until the auction begins" title="Please wait until the auction begins"></div>
				 <?php elseif(!empty($auction['Auction']['isClosed'])) : ?>
					<div><img src="<?php echo $this->webroot; ?>img/button/b-sold.gif" width="94" height="32" alt="" title=""></div>
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
	<?php endforeach;?>
</ul>
