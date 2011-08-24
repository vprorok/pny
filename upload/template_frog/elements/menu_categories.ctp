<div id="select-categories">
	<?php if(!empty($menuCategories)) : ?>
	<ul id="nav"><!--  
--><li class="list"><a href="<?php echo $appConfigurations['url']; ?>/auctions">All items (<?php echo $menuCategoriesCount['live']; ?>)</a></li><!--
--><?php

$curwidth=0; $secondaryCategories=array();
foreach ($menuCategories as $menuCategory) {
	if (!isset($menuCategory['Category']['featured']) || (isset($menuCategory['Category']['featured']) && $menuCategory['Category']['featured']==1)) {
		$curwidth+=40+(5*strlen($menuCategory['Category']['name']));
		
		if ($curwidth<700) {
			?><li class="list"><?= $html->link($menuCategory['Category']['name'], AppController::CategoryLink($menuCategory['Category']['id'], $menuCategory['Category']['name'])) ?></li><?php
		} else {
			$secondaryCategories[]=$menuCategory;
		}
	}
}
?>
--><li style="border-right:none;">
			<a>Other Auctions<span style="margin-left:1px;">▼</span></a>
			<ul>
<?php
		foreach ($secondaryCategories as $secondaryCategory) {
?>                                              
				<li><?= $html->link($secondaryCategory['Category']['name'], AppController::CategoryLink($secondaryCategory['Category']['id'], $secondaryCategory['Category']['name'])) ?></li>
<?php
		}
?>
				<li><a href="<?= $this->webroot ?>auctions/future">Upcoming auctions (<?php echo $menuCategoriesCount['comingsoon']; ?>)</a></li>
				<li><a href="<?= $this->webroot ?>auctions/closed">Closed auctions (<?php echo $menuCategoriesCount['closed']; ?>)</a></li>
				<li><a href="<?= $this->webroot ?>auctions/winners">Winners</a></li>
				<li><a href="<?= $this->webroot ?>categories">Categories</a>
			</ul>
		</li>
	</ul>
	<?php endif; ?>
	<?php
	?>
</div>
