
<ul class="categories">
<?php foreach($categories as $category): ?>
	<li>
		<div class="align-center auction-item">
			<div class="title">
				<h3><?php echo $html->link($category['Category']['name'], AppController::CategoryLink($category['Category']['id'], $category['Category']['name'])); ?></h3>
			</div>
			<div class="thumb">
				<?php if(!empty($category['Category']['image'])) : ?>
					<?php echo $html->link($html->image('category_images/thumbs/'.$category['Category']['image'], array('border' => 0)), AppController::CategoryLink($category['Category']['id'], $category['Category']['name']), null, null, false); ?>
				<?php else : ?>
					<?php echo $html->link($html->image('category_images/thumbs/no-image.gif', array('border' => 0)), AppController::CategoryLink($category['Category']['id'], $category['Category']['name']), null, null, false); ?>
				<?php endif; ?>
			</div>
		</div>
	</li>
<?php endforeach;?>
</ul>
<br class="clear_br">
<h2 class="blue" style="font-size:1.5em;">Other auctions</h2>
<ul class="categories">
	<li style="margin-bottom:20px;">
		<div class="align-center auction-item">
			<div class="title">
				<h3><a href="/auctions">All items</a></h3>

			</div>
			<div class="thumb">
				<a href="/auctions"><img src="<?php echo $this->webroot; ?>img/cat_thumb/cat_all_thumb.png" alt="<?php echo $appConfigurations['name']; ?> : All auction items"  title="<?php echo $appConfigurations['name']; ?> : All auction items" /></a>
			</div>
		</div>
	</li>
	<li style="margin-bottom:20px;">
		<div class="align-center auction-item">
			<div class="title">
				<h3><a href="/auctions/future">Future auctions</a></h3>

			</div>
			<div class="thumb">
				<a href="/auctions/future"><img src="<?php echo $this->webroot; ?>img/cat_thumb/cat_future_thumb.png" alt="<?php echo $appConfigurations['name']; ?> : Future auctions"  title="<?php echo $appConfigurations['name']; ?> : Future auctions" /></a>
			</div>
		</div>
	</li>
	<li style="margin-bottom:20px;">
		<div class="align-center auction-item">
			<div class="title">
				<h3><a href="/auctions/winners">Previous winners</a></h3>

			</div>
			<div class="thumb">
				<a href="/auctions/winners"><img src="<?php echo $this->webroot; ?>img/cat_thumb/cat_win_thumb.png" alt="<?php echo $appConfigurations['name']; ?> : List of auction winners"  title="<?php echo $appConfigurations['name']; ?> : List of auction winners" /></a>
			</div>
		</div>
	</li>
	<li style="margin-bottom:20px;">
		<div class="align-center auction-item">
			<div class="title">
				<h3><a href="/auctions/free">Free auctions</a></h3>

			</div>
			<div class="thumb">
				<a href="/auctions/free"><img src="<?php echo $this->webroot; ?>img/cat_thumb/cat_free_thumb.png" alt="<?php echo $appConfigurations['name']; ?> : Free bid auctions"  title="<?php echo $appConfigurations['name']; ?> : Free bid auctions" /></a>
			</div>
		</div>
	</li>
</ul>