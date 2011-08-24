<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Product']['id']);
echo $this->element('admin/crumb');
?>

<div class="auctions form">
<?php echo $form->create('Product');?>
	<fieldset>
 		<legend><?php __('Edit a Product');?></legend>

		<?php
			echo $form->input('id');
			echo $form->input('title', array('label' => 'Title <span class="required">*</span> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Choose a product title</span><span class="HelpToolTip_Contents" style="display:none;">This product title will appear on-site. For example \'Sony Playstation 3 Black 160GB with controller\' is much better than \'Playstation 3\' only.</span></span>'));
			echo $form->input('code', array('label' => 'Product Code <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Product Code</span><span class="HelpToolTip_Contents" style="display:none;">To help with organizing and understanding your inventory, you can set codes here to help identify the product quickly. Recommended if you have many items for sale.</span></span>'));
			echo $form->input('brief', array('label' => 'Brief <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Brief description to show on some pages</span><span class="HelpToolTip_Contents" style="display:none;">This section will show on the homepage, on some templates, and provides a snippet of text to give a better understanding of what the product is about before it is clicked.</span></span>'));
		?>

		<label for="PageContent"><?php __('Description');?></label>
		<?php echo $fck->input('Product.description'); ?>
		<p>&nbsp;</p>

		<?php
			echo $form->input('category_id', array('label' => 'Category <span class="required">*</span> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Choose a category</span><span class="HelpToolTip_Contents" style="display:none;">Select a category from the drop down menu to list the product in this category. Only one can be selected.</span></span>', 'empty' => 'Select Category'));
			if(!empty($appConfigurations['limits']['active'])) :
				echo $form->input('limit_id', array('label' => 'Limit', 'empty' => 'No Limit'));
			endif;
		echo $form->input('rrp', array('label' => 'RRP/MSRP <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">RRP/MSRP</span> <span class="HelpToolTip_Contents" style="display:none;">The RRP is the Recommended Retail Price, sometimes known as the <b>MSRP</b>, this will be shown on your website as \'worth up to \'.</span></span>'));
			if(!empty($appConfigurations['buyNow'])) :
			echo $form->input('buy_now', array('label' => 'Buy Now Price <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Buy It Now price</span><span class="HelpToolTip_Contents" style="display:none;">The Buy It Now price is the price that your customers will purchase at. Depending on your settings, this will either close the auction or it may continue.</span></span>'));
			endif;
			echo $form->input('start_price', array('label' => 'Start Price <span class="required">*</span>  <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Start price</span><span class="HelpToolTip_Contents" style="display:none;">The basic price at which you want your auction to begin. This will be publicly viewable in some templates.</span></span>'));
		echo $form->input('delivery_cost', array('label' => 'Delivery Costs <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Delivery Costs</span><span class="HelpToolTip_Contents" style="display:none;">Here you can set your own P\'n\'P charges. These will be billed to the customer at purchase time when they win the item. Set to \'0\' for free delivery.</span></span>'));
			echo $form->input('delivery_information', array('label' => 'Delivery Information <span class="required">*</span>  <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Delivery Information</span><span class="HelpToolTip_Contents" style="display:none;">Optional data that will be showed to all visitors of your auction page. Helpful to specify your delivery timeframes, ie \'delivery within 5 working days to mainland USA\'.</span></span>'));
				echo $form->input('meta_description', array('label' => 'Meta Description <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">META Description</span><span class="HelpToolTip_Contents" style="display:none;">Enter a description here to assist with Search Engine Optimizaton (SEO), this will allow search engines to find this product more easily.</span></span>'));
				echo $form->input('meta_keywords', array('label' => 'Meta Keywords <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">META Keywords</span><span class="HelpToolTip_Contents" style="display:none;">Enter a comma-separated list of keywords here to assist with Search Engine Optimizaton (SEO), this will allow search engines to find this product more easily. Eg: <em>console,games,gaming,fun,sony,playstation</em></span></span>'));
			echo $form->input('fixed', array('type' => 'checkbox', 'label' => 'Fixed Price Auction - bidders still bid and the price increases, however they only pay the price you set here'));
		?>
		<div id="FixedPriceBlock"><?php echo $form->input('fixed_price');?></div>
		<?php
	echo $form->input('stock', array('type' => 'checkbox', 'label' => 'Use stock control? <span class="HelpToolTip" style="float:none";><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Use stock control?</span><span class="HelpToolTip_Contents" style="display:none;">If you have only a small number of this product to sell in your inventory, you can enable stock control to prevent this product being relisted too many times. Eg: if 3 items in stock, the item will auto-relist only 3 times if this is enabled.</span></span>'));
		?>
		<div id="StockNumberBlock"><?php echo $form->input('stock_number', array('label' => 'Number in stock <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Number in Stock</span><span class="HelpToolTip_Contents" style="display:none;">If you have 5 items in stock, enter \'5\' here. This will show customers how many items you have left (valid for specific templates only).</span></span>'));?></div>
		<?php
			if(!empty($appConfigurations['autobids'])) :
				echo $form->input('minimum_price', array('label' => 'Minimum Price <span class="required">*</span> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Minimum sale price</span><span class="HelpToolTip_Contents" style="display:none;">The \'minimum price\' allows you to guarantee that the auction will not close before the minimum price is set.</span></span>'));
				echo $form->input('autobid', array('label' => 'Allow autobidders to bid on this auction? <span class="HelpToolTip" style="float:none";><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Allow autobidders to bid?</span><span class="HelpToolTip_Contents" style="display:none;">Check this box to allow autobidders to place bids on this auction listing/product. Please make sure you are not using these on a live website.</span></span>'));
				echo $form->input('autobid_limit', array('label' => 'Autobid limit <em>eg: 999</em> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Autobidding limit</span><span class="HelpToolTip_Contents" style="display:none;">Use this feature to set the <em>maximum</em> number of autobids which will be placed simultaneously before the auction closes.  If the limit is met, an autobidder will win the auction. Set this to 0 to not use this feature. </span></span>'));
			endif;
		?>
		<?php
			if($appConfigurations['bidIncrements'] == 'product') :
				echo $form->input('SettingIncrement.id');
				echo $form->input('SettingIncrement.bid_debit', array('label' => 'Bid Debit *'));
				echo $form->input('SettingIncrement.price_increment', array('label' => 'Price Increment *'));
				echo $form->input('SettingIncrement.time_increment', array('Time Increment (in seconds) *'));
			endif;
		?>
		
	

		<?php if(!empty($appConfigurations['autobids'])) : ?>
 		<p><?php __('Please note, every time an auto bid is placed, the minimum price will extend by the bid increase amount to ensure that you no not undersell the product.');?></p>
		<?php endif; ?>

	</fieldset>
<?php echo $form->end(__('Save Changes >>', true));?>
</div>
<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to products', true), array('action' => 'index'));?></li>
	</ul>
</div>