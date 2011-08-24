<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb($auction['Product']['title'], '/admin/auctions/edit/'.$auction['Auction']['id']);
$html->addCrumb(__('Bids Placed', true), '/admin/bids/auctions'.$auction['Auction']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php echo $auction['Product']['title'] ?> <?php __('Statistics'); ?></h2>

<dl><?php $i = 0; $class = ' class="altrow"';?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Current Price:'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?>
	</dd>

	<?php if(!empty($appConfigurations['autobids'])) : ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Autobid Limit:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $auction['Product']['autobid_limit']; ?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Number of Autobids placed in a row:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $auction['Auction']['autobids']; ?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Minimum Price:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $number->currency($auction['Auction']['minimum_price'], $appConfigurations['currency']); ?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Number of Autobids placed (total):'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $autobids; ?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Number of Real Bids placed:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $realbids; ?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Each time a bid is placed the price will increase by:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $number->currency($priceIncrement, $appConfigurations['currency']); ?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Number of Real Bids required before this auction is won by a real user:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $realBidsRequired; ?>
		</dd>
	<?php endif; ?>

</dl>