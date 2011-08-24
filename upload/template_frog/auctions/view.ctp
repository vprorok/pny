<div id="auction-details" class="box">
	<div class="f-top-o clearfix" style="height:84px;">

<div class="detail_head">
<div class="detail_head_l">
<h2><?php echo $auction['Product']['title']; ?></h2>
</div>
<div class="detail_head_r">

<!-- Free guide start --><?php if(!empty($auction['Auction']['free'])):?>                       
<a href="#" id="trigger_free"><?= $html->image('badge/free_icon.png', array('width'=>"50", 'height'=>"50", 'border'=>"0", 'alt'=>"Free bid auction", 'title'=>"Free bid auction"))?></a>
	<div class="tooltip">
	<p><label>Free auctions <strong class="orange2">cost nothing to enter.</strong> Ever! <br />Worry free bidding!</label></p>
	<p><label>For explanations <a href="/page/auction_type#free">of this auction type, please click here</a></label></p>
	</div>
<script>
$(function() {
$("#trigger_free").tooltip();
});
</script>
<?php endif;?><!-- /Free guide end -->

<!-- Reverse guide start --><?php if(!empty($auction['Auction']['reverse'])):?>
<a href="#" id="trigger_reverse"><?= $html->image('badge/reverse_icon.png', array('width'=>"50", 'height'=>"50", 'border'=>"0", 'alt'=>"Reverse bid auction", 'title'=>"Reverse bid auction"))?></a>
	<div class="tooltip">
	<p><label>Reverse auctions <strong class="orange2">cost nothing to enter.</strong> Ever! Reverse auctions are worry free bidding!</label></p>
	<p><label>For explanations <a href="/page/auction_type#reverse">of this auction type, please click here</a></label></p>
	</div>
<script>
$(function() {
$("#trigger_reverse").tooltip();
});
</script>
<?php endif;?><!-- /Reverse guide end -->

<!-- Beginner guide start --><?php if(!empty($auction['Auction']['beginner'])):?>                       
<a href="#" id="trigger_beginner"><?= $html->image('badge/beginner_icon.png', array('width'=>"50", 'height'=>"50", 'border'=>"0", 'alt'=>"Beginner auction", 'title'=>"Beginner auction"))?></a>
	<div class="tooltip">
	<p><label>Beginner auctions <strong class="orange2">are for newbies only!</strong> You therefore have a greater chance of winning, but you can only win a Beginner auction once!</label></p>
	<p><label>For an explanation <a href="/page/auction_type#beginner">of this auction type, please click here</a></label></p>
	</div>
<script>
$(function() {
$("#trigger_beginner").tooltip();
});
</script>
<?php endif;?><!-- /Beginner guide end -->

<!-- Fixed Price guide start --><?php if(!empty($auction['Product']['fixed'])):?>                        
<a href="#" id="trigger_fixed"><?= $html->image('badge/fixed_icon.png', array('width'=>"50", 'height'=>"50", 'border'=>"0", 'alt'=>"Fixed price auction", 'title'=>"Fixed price auction"))?></a>
	<div class="tooltip">
	<p><label>Fixed price auctions <strong class="orange2">mean you pay a fixed fee only. </strong><br />The easiest way to win any auction at <strong class="orange2">a ridiculously low price!</strong></label></p>
	<p><label>For explanations <a href="/page/auction_type#fixed">of this auction type, please click here</a></label></p>
	</div>
<script>
$(function() {
$("#trigger_fixed").tooltip();
});
</script>
<?php endif;?><!-- /Fixed Price guide end -->

<!-- Penny (featured_ guide start --><?php if(!empty($auction['Auction']['penny'])):?>                   
<a href="#" id="trigger_penny"><?= $html->image('badge/penny_icon.png', array('width'=>"50", 'height'=>"50", 'border'=>"0", 'alt'=>"Penny auction", 'title'=>"Penny auction"))?></a>
	<div class="tooltip">
	<p><label>One bid will increase the auction price<strong class="orange2">by ONE penny (cent).</strong> These are sometimes called cent auctions. Get a bargain!</label></p>
	<p><label>For explanations <a href="/page/auction_type#penny">of this auction type, please click here</a></label></p>
	</div>
<script>
$(function() {
$("#trigger_penny").tooltip();
});
</script>
<?php endif;?><!-- /Penny guide end -->

<!-- Peak guide start --><?php if(!empty($auction['Auction']['peak_only'])):?>                
<a href="#" id="trigger_peak"><img src="<?php echo $this->webroot; ?>img/badge/peak_only_icon.png" width="50" height="50" border="0" alt="Peak only auction" title="Peak only auctions"></a>
	<div class="tooltip">
	<p><label>The auction time is limited <strong class="orange2">meaning a greater chance of winning! </strong>Bidding can only be placed、<strong class="orange2">during peak hours (10am to 6pm)</strong></label></p>
	<p><label>For explanations <a href="/page/auction_type#peak_only">of this auction type, please click here</a></label></p>
	</div>
<script>
$(function() {
$("#trigger_peak").tooltip();
});
</script>
<?php endif;?><!-- Peak guide end -->

<!-- Nail Biter guide start --><?php if(!empty($auction['Auction']['nail_bitter'])):?>
<a href="#" id="trigger_nail"><img src="<?php echo $this->webroot; ?>img/badge/nail_icon.png" width="50" height="50" border="0" alt="Nailbiter auctions" title="Nailbiter auctions"></a>
	<div class="tooltip">
	<p><label>Nailbiter auctions are <strong class="orange2">those where Bid Butlers are not allowed</strong> which gives you a greater chance of winning!</label></p>
	<p><label>For explanations <a href="/page/auction_type#nail">of this auction type please click here</a></label></p>
	</div>
<script>
$(function() {
$("#trigger_nail").tooltip();
});
</script>
<?php endif;?><!-- /Nail Biter guide end -->

<!-- Featured guide start --><?php if(!empty($auction['Auction']['featured'])):?>
<a href="#" id="trigger_featured"><img src="<?php echo $this->webroot; ?>img/badge/featured_icon.png" width="50" height="50" border="0" alt="Featured auctions" title="Featured auction"></a>
	<div class="tooltip">
	<p><label><strong class="orange2">Most popular auctions</strong> are displayed on the homepage, meaning that you should <strong class="orange2">start here if you're new!</strong></label></p>
	<p><label>Don't miss these auctions, they are HOT!</label></p>
	</div>
<script>
$(function() {
$("#trigger_featured").tooltip();
});
</script>
<?php endif;?><!-- /Featured guide end -->

<br class="clear_br"> </div>
</div><!-- /detail_head end -->
	</div>
	<div class="f-repeat clearfix" style="padding-bottom:20px;">
		<div class="content" style="padding:20px !important;">
			<div class="col1">
				<div class="content">
					<div class="auction-image">
						<?php if(!empty($auction['Auction']['image'])):?>
							<?php echo $html->image($auction['Auction']['image'], array('class'=>'productImageMax', 'alt' => $auction['Product']['title'], 'title' => $auction['Product']['title']));?>
						<?php else:?>
							<?php echo $html->image('product_images/max/no-image.gif', array('alt' => $auction['Product']['title'], 'title' => $auction['Product']['title']));?>
						<?php endif; ?>
					</div>
					<div class="thumbs">
						<?php if(!empty($auction['Product']['Image']) && count($auction['Product']['Image']) > 1):?>
								<?php foreach($auction['Product']['Image'] as $image):?>
									<?php if(!empty($image['ImageDefault'])) : ?>
									<span><?php echo $html->link($html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$image['ImageDefault']['image']), '/img/'.$appConfigurations['currency'].'/default_images/max/'.$image['ImageDefault']['image'], array('class' => 'productImageThumb'), null, false);?></span>
								<?php else: ?>
									<span><?php echo $html->link($html->image('product_images/thumbs/'.$image['image']), '/img/product_images/max/'.$image['image'], array('class' => 'productImageThumb'), null, false);?></span>
								<?php endif; ?>
							<?php endforeach;?>
						<?php endif;?>
					</div>
					<br class="clear_br">
					<div class="align-center" style="margin-bottom:0px;">
					<p style="margin:5px 0 13px; padding:0;"><label>Click to enlarge image</label></p>
				    <?php if(!$session->check('Auth.User')){ ?>
				    		<div style="margin:-3px 0 0 -11px;"><a href="<?php echo $appConfigurations['ref_url']; ?>/users/register" class="button_orage" style="font-size:14px;white-space:nowrap;">Hurry! Register for free bids.</a></div>
				    <?php } ?>

					<?php if($session->check('Auth.User') && empty($auction['Auction']['isClosed'])):?>
                                    <?php if(!empty($watchlist)):?>
                                        <div class="bid-addwatchlist" style="margin:-3px 0 0;"><?php echo $html->link(__('Remove from Watchlist', true), array('controller' => 'watchlists', 'action'=>'delete', $watchlist['Watchlist']['id']), null, sprintf(__('Are you sure you want to delete the auction from your watchlist??', true), $watchlist['Watchlist']['id'])); ?></div>
                                    <?php else:?>
                                        <div class="bid-addwatchlist" style="margin:-3px 0 0;"><?php echo $html->link(__('Add to Watchlist', true), array('controller' => 'watchlists', 'action' => 'add', $auction['Auction']['id']));?></div>
                                    <?php endif;?>
                              <?php endif;?>
						<div class="bold bkmk align-center"><img src="<?php echo $this->webroot; ?>img/parts/twitter_logo_small.png" width="79" height="21" alt="Twitter" title="Twitter"><a class="retweet self" href=""></a> <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.<?php echo Configure::read('Twitter.username') ?>.com&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe></div>
					</div>
				</div>
			</div>

			<div class="col2">
				<div class="content auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>" style="padding:0;">
					<div class="sub-col1"<?php if ($buy_it_now) { ?> style="height:365px"<?php } ?>>

                         		<?php if(!empty($auction['Auction']['isFuture'])):?>
							<div class="congrats">Auction items will</div>
						<?php endif;?>

						<?php if(!empty($auction['Auction']['isClosed'])):?>
						    <?php if(!empty($auction['Winner']['id'])):?>
							<div class="congrats orange2">Congratulations!<br /><?php echo $auction['Winner']['username'];?> </div>
						    <?php else:?>
							<div class="congrats"><?php __('There was no winner');?></div>
						    <?php endif;?>
						<?php endif;?>

						<?php if(!empty($auction['Auction']['peak_only'])):?>
							<div class="congrats orange2">Peak only auctions</div>
						<?php endif;?>

						<?php if(empty($auction['Auction']['isFuture']) && empty($auction['Auction']['isClosed']) && empty($auction['Auction']['peak_only'])):?>
							<div class="congrats orange2">
							This is a <?php if(!empty($auction['Product']['fixed'])):?><a href="/pages/auction_type#fixed">Fixed</a><?php endif;?><?php if(!empty($auction['Auction']['beginner'])):?><a href="/pages/auction_type#beginner">Beginner</a><?php endif;?><?php if(!empty($auction['Auction']['reverse'])):?><a href="/pages/auction_type#reverse">Reverse</a><?php endif;?><?php if(!empty($auction['Auction']['penny'])):?><a href="/pages/auction_type#penny">Penny</a><?php endif;?><?php if(!empty($auction['Auction']['nail_bitter'])):?><a href="/pages/auction_type#nailbiter">Nail Biter</a><?php endif;?><?php if(!empty($auction['Auction']['free'])):?><a href="/pages/auction_type#free">Free</a><?php endif;?> auction.
							</div>
						<?php endif;?>

						
						<dl class="clearfix" style="padding:10px 10px 0;">
						
						</dl>
						
						<dl class="clearfix" style="padding:10px 10px 0;">
							<dt><?php if(!empty($auction['Product']['fixed'])):?>
								<span class="font-18">Flat rate pricing :</span>
							<?php else: ?>
								<?php echo empty($auction['Auction']['isClosed']) ? "Current price :" : "Price :"; ?>
							<?php endif; ?></dt>
							<dd class="price">
								<?php if(!empty($auction['Product']['fixed'])):?>
								<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']); ?><span class="bid-price-fixed" style="display: none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span><?php else: ?><span class="bid-price"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span><?php endif; ?><br /><span class="vat">Including tax &amp; shipping</span>
							</dd>
							
							<?php if($buy_it_now):?>
								<dt>
								Buy it now :
								</dt>
								<dd class="price_bin">
								<?php echo $number->currency($auction['Product']['buy_now'], $appConfigurations['currency']); ?>
								<br /><span class="vat">Including tax &amp; shipping</span></dd>
							<?php endif; ?>
							
							<dt><?php echo empty($auction['Auction']['isClosed']) ? "Latest bidder :" : "Winners :"; ?></dt>
							<dd class="username"><span class="bid-bidder">Highest Bidder</span></dd>
						</dl>
						
						
						
						<div style="padding-bottom:0px;">
						<?php if(empty($auction['Auction']['isFuture']) && empty($auction['Auction']['isClosed'])):?>
						<span class="popup_time">
						<a href="#">
						<span class="tips">
						<pre>With each bid, the time remaining will increase by <strong class="orange2"><?php echo $timeIncrease; ?> seconds</strong>.</pre>
						</span>
						<img src="<?php echo $this->webroot; ?>img/increment/time<?php echo $timeIncrease; ?>.png" class="timeincrement"></a>
						</span>
						<?php endif; ?>
						<div id="timer_<?php echo $auction['Auction']['id'];?>" class="timer countdown" title="<?php echo $auction['Auction']['end_time'];?>">--:--:--</div>
						</div>
						
						<div class="bid-now"<?php if ($buy_it_now) { ?> style="height:105px"<?php } ?>>

							<?php if(!empty($auction['Auction']['isFuture'])) : ?>
								<div><img src="<?php echo $this->webroot; ?>img/button/b-soon-big.gif" width="199" height="59" alt="Please wait for the auction to begin" title="Please wait for the auction to begin"></div>
							 <?php elseif(!empty($auction['Auction']['isClosed'])) : ?>
								<div><img src="<?php echo $this->webroot; ?>img/button/b-sold-big.gif" width="199" height="59" alt="オークション終了" title="オークション終了"></div>
								
								<?php
								if ($buy_it_now) { ?>
									<div class="submit" style="padding-left:5px">
									<input type="submit" 
										onclick="window.location='/auctions/buy/<?php echo $auction['Auction']['id']; ?>'" 
										value="Buy it now!"
										style="width:194px; padding-top:2px; height:30px">
									</div>
									<?php 
								} ?>
							 <?php else:?>
								 <?php if($session->check('Auth.User')):?>
									<div class="bid-loading" style="display: none"><?php echo $html->image('ajax-arrows-white.gif');?></div>
									<div class="bid-button"><a class="bid-button-link button-big" title="<?php echo $auction['Auction']['id'];?>" href="/bid.php?id=<?php echo $auction['Auction']['id'];?>">Bid!</a></div>
									
									<?php
									if ($buy_it_now) { ?>
										<div class="submit" style="padding-left:5px">
										<input type="submit" 
											onclick="window.location='/auctions/buy/<?php echo $auction['Auction']['id']; ?>'" 
											value="Buy it now!"
											style="width:194px; padding-top:2px; height:30px">
										</div>
										<?php 
									} ?>
								<?php else:?>
									<div class="bid-button"><a href="/users/login" class="b-login-big"><?php __($j['menu_my_login']);?></a></div>
								<?php endif;?>
								
								
							<?php endif; ?>

						</div>
						
						<?php if(empty($auction['Auction']['isFuture']) && empty($auction['Auction']['isClosed'])):?>
						<div class="bid-msg">
						<div class="bid-message" style="display: none"></div>
						</div>
						<?php endif; ?>

						<?php if(!empty($auction['Product']['fixed']) && empty($auction['Auction']['isClosed'])):?>
							<div class="note"><?php if(!empty($auction['Auction']['free'])):?>Free listing! Costs nothing. <br /><?php endif; ?>To the highest bidder, flat rate pricing:<span class="side_2px bold blk"><?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']);?></span>You can bid.</div>
						<?php endif; ?>
						<?php if(empty($auction['Auction']['isClosed']) && empty($auction['Auction']['isFuture']) && empty($auction['Product']['fixed'])):?>
							<div class="note" style="<?php if ($buy_it_now) { ?>margin-top:3px <?php } ?>">
							<?php if(!empty($auction['Auction']['free'])):?>Free listing! Costs nothing. <br />
							<?php endif; ?>
							
							<?= __('With each bid, the auction price') ?> <?php if ($auction['Auction']['reverse']) echo __('decreases by'); else echo __('increases by'); ?> <span class="side_2px bold blk price-increment"><?php echo $number->currency($bidIncrease, $appConfigurations['currency']);?></span></div>
						<?php endif; ?>

					</div>

					<div class="count-saving">
							<dl class="saving">
						<?php if(!empty($auction['Product']['rrp'])) : ?>
							<dt class="saving"><?= __('Normal price') ?> :</dt>
							<dd class="saving"><?php echo $number->currency($auction['Product']['rrp'], $appConfigurations['currency']); ?></dd>
						<?php endif; ?>
						<dt class="saving" style="border:0px;">
						<?php if(!empty($auction['Product']['fixed'])):
							?> <?= __('Flat-rate pricing') ?> :
						<?php else: ?>
							<?php echo empty($auction['Auction']['isClosed']) ? "Current price :" : "Price :"; ?>
						<?php endif; ?></dt>
							<?php if(!empty($auction['Product']['fixed'])):?>
								<dd class="saving" style="border:0px;"><?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']);?></dd>
							<?php else: ?>
								<dd class="bid-price2" style="border:0px;"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></dd>
							<?php endif; ?>
							</dl><br class="clear_br">
						<div class="total-savings align-center"><?php if(empty($auction['Auction']['isFuture'])):?>Save over <span class="bid-savings-price side_5px"><?php echo $number->currency($auction['Auction']['savings']['price'], $appConfigurations['currency']);?></span> from the normal retail price!<?php endif; ?>
						</div>
					</div>

				</div>
			</div>

			<div class="col3">

<!-- the tabs -->
<ul class="tabs">
	<li><a href="#t1"><?=__('Bid History')?></a></li>
	<?php if(empty($auction['Auction']['isFuture']) && empty($auction['Auction']['isClosed']) && empty($auction['Auction']['nail_bitter']) && $session->check('Auth.User')):?>

	<li><a href="#t2"><?= __('Bid Butlers')?></a></li>
	<?php endif;?>
</ul>

<!-- tab "panes" -->
<div class="panes">
	<div class="bid-history bid-histories" id="bidHistoryTable<?php echo $auction['Auction']['id'];?>">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th><label><?php __('Time');?></label></th>
                                    <th><label><?php __('Bidder');?></label></th>
                                    <th><label><?php __('Type');?></label></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($bidHistories)):?>
                                    <?php foreach($bidHistories as $bid):?>
                                    <tr>
                                        <td><?php echo $time->niceShort($bid['Bid']['created']);?></td>
                                        <td><?php echo $bid['User']['username'];?></td>
                                        <td><?php echo $bid['Bid']['description'];?></td>
                                    </tr>
                                    <?php endforeach;?>
                                <?php endif;?>
                                <?php if(empty($bidHistories)):?>
                                <tr><td colspan="3" align="center" style="border-bottom:0px;"><p class="align-center bold" style="padding-top:30px; color:#AAA; font-size:12px;"><label><?php __('No bids have been placed yet.');?></label></p></td></tr>
                                <?php endif;?>
                            </tbody>
                        </table>
	</div>
	<?php if(empty($auction['Auction']['isFuture']) && empty($auction['Auction']['isClosed']) && empty($auction['Auction']['nail_bitter']) && $session->check('Auth.User')):?>
	<div class="bid-history bid-histories">
			<p><?= __('Bid Buddy settings') ?></p>
				<?php echo $form->create('Bidbutler', array('url' => '/bidbutlers/add/'.$auction['Auction']['id']));?>
					<fieldset class="auto">
					<?php if (Configure::read('App.bidButlerType')=='advanced') { ?>
					<label for="BidbutlerMinimumPrice"><?= __('Starting Bid') ?>&nbsp;&nbsp;&nbsp;<?= $number->currencySign($appConfigurations['currency']) ?></label><input class="disabled" name="data[Bidbutler][minimum_price]" type="text" maxlength="6" value="" id="BidbutlerMinimumPrice" />
					<br style="clear:both">
					<?php } ?>
					
					<?php
					if (!$auction['Auction']['reverse'] && Configure::read('App.bidButlerType')=='advanced') {
						?>
					<label for="BidbutlerMaximumPrice"><?= __('Maximum bid') ?>&nbsp;&nbsp;&nbsp;<?= $number->currencySign($appConfigurations['currency']) ?></label><input class="disabled" name="data[Bidbutler][maximum_price]" type="text" maxlength="6" value="" id="BidbutlerMaximumPrice" />
					<br style="clear:both">
						<?php
					}
					?>
					
					<label for="BidbutlerBids"><?= __('Number of bids') ?></label><input class="disabled" name="data[Bidbutler][bids]" type="text" maxlength="6" value="" id="BidbutlerBids" />
					</fieldset>
				<span class="submit"><input type="submit" value="<?= __('Set Bid') ?>"/></span>
				<p class="bold"><a href="/bidbutlers"><?= __('Your Bid Butler settings') ?></a></p>
	</div>
	<?php endif;?>
</div>
<script>
$(function() {
	$("ul.tabs").tabs("div.panes > div", {history: true});
});
</script>


		<!-- Feed News start -->
	<div class="detail_right_tw">
	<h3 class="orange2"><label><?= __('Auction News')?></label></h3>
	<div  id="twitter_div" style="width: 215px;height: 84px;overflow: auto;overflow-x: hidden;overflow-y: hidden;">
		<ul id="twitter_update_list"></ul>
	</div>
	</div>
	<!-- Feed News end -->
	<div class="align-right"><a href="http://twitter.com/<?php echo Configure::read('Twitter.username') ?>" target="_blank">Twitter</a><img src="<?php echo $this->webroot; ?>img/parts/external.png" width="10" height="10" border="0" align="middle" style="vertical-align: baseline; margin-left:3px;"></div>

			</div>
		</div>
	</div>
						
	<div id="payment-info" class="f-repeat clearfix">
		<div class="content" style="padding:10px 25px; margin-bottom:10px;">
			<div class="col1">
				<dl>
				<dt>Auction ID:</dt>
				<dd><?php echo $auction['Auction']['id'];?></dd>
				<dt>Auction type(s):</dt>
				<dd><?php if(!empty($auction['Auction']['free'])):?>
							<?php echo $html->link('Free auction', '/page/auction_type#free', null, null, false); ?>							
						<?php endif; ?>
						<?php if(!empty($auction['Product']['fixed'])):?>
							<?php echo $html->link('Fixed price auction', '/page/auction_type#fixed', null, null, false); ?>							
						<?php endif; ?>
						<?php if(!empty($auction['Auction']['penny'])):?>
							<?php echo $html->link('1p auction', '/page/auction_type#penny', null, null, false); ?>
						<?php endif; ?>
						<?php if(!empty($auction['Auction']['nail_bitter'])):?>
							<?php echo $html->link('Nailbiter auction', '/page/auction_type#nailbiter', null, null, false); ?>
						<?php endif; ?>
						<?php if(!empty($auction['Auction']['peak_only'])):?>
							<?php echo $html->link('Peak-only auction', '/page/auction_type#peak', null, null, false); ?>
						<?php endif; ?>
                        	<?php if(!empty($auction['Auction']['beginner'])):?>
							<?php echo $html->link('Beginner auction', '/page/auction_type#beginner', null, null, false); ?>
						<?php endif; ?>
                        			<?php if(!empty($auction['Auction']['reverse'])):?>
							<?php echo $html->link('Reverse auction', '/page/auction_type#reverse', null, null, false); ?>
						<?php endif; ?>
						<?php if(empty($auction['Auction']['free']) && empty($auction['Product']['fixed']) && empty($auction['Auction']['reverse']) && empty($auction['Auction']['penny']) && empty($auction['Auction']['nail_bitter'])  && empty($auction['Auction']['beginner']) && empty($auction['Auction']['peak_only'])):?>
							<?php echo $html->link('Regular auction', '/page/auction_type', null, null, false); ?>
						<?php endif; ?>
						</dd>
				<dt>Payment:</dt>
				<dd>Credit/Debit Cards &amp; Paypal</dd>
				</dl>
			</div>

			<div class="col2">
				<dl>
                         <?php if(!empty($auction['Auction']['isFuture'])):?>
				<dt>Prices start:</dt>
				<dd><?php echo $number->currency($auction['Product']['start_price'], $appConfigurations['currency']);?></dd>
				<dt>Scheduled start date :</dt>
				<dd><?php echo $time->nice($auction['Auction']['start_time']);?></dd>
				<dt>Closing date :</dt>
				<dd><?php echo $time->nice($auction['Auction']['end_time']);?></dd>
				
				<?php elseif(!empty($auction['Auction']['isClosed'])):?>
				<dt>Prices start at:</dt>
				<dd><?php echo $number->currency($auction['Product']['start_price'], $appConfigurations['currency']);?></dd>
				<dt>Start time:</dt>
				<dd><?php echo $time->nice($auction['Auction']['start_time']);?></dd>
				<dt>End time:</dt>
				<dd><?php echo $time->nice($auction['Auction']['end_time']);?></dd>
				<?php else: ?>
				<dt>Prices start at:</dt>
				<dd><?php echo $number->currency($auction['Product']['start_price'], $appConfigurations['currency']);?></dd>
				<dt>Start time:</dt>
				<dd><?php echo $time->nice($auction['Auction']['start_time']);?></dd>
				<dt>End time:</dt>
				<dd><?php echo $time->nice($auction['Auction']['end_time']);?></dd>
				<?php endif;?>
				</dl>
			</div>
			<div class="col3">
				<dl>
				<dt>Shipping Fee :</dt>
				 <?php if(!empty($auction['Product']['delivery_cost'])) : ?>
					<dd><?php echo $number->currency($auction['Product']['delivery_cost'], $appConfigurations['currency']);?></dd>
				 <?php endif;?>
				</dl><br class="clear_br">
				<div class="info">
				<strong>Shipping information:</strong>
				<?php if(!empty($auction['Product']['delivery_information'])) : ?>
					<p><?php echo $auction['Product']['delivery_information'];?></p>
				<?php else: ?>
				<p class="align-center" style="padding:4px;">None provided</p>
				<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix">&nbsp;</div>
</div>



<div id="product-desc" class="box">
	<div class="f-top-w clearfix" style="height:75px !important;">
	  <h2 style="font-size:20px;"><?php echo $auction['Product']['title']; ?> - Product Information</h2>
	</div>
	<div class="f-repeat-l clearfix">
		<div class="content">
			<?php echo $auction['Product']['description'];?>
		</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">TOP OF PAGE</a></p></div>
</div>