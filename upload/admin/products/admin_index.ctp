<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
echo $this->element('admin/crumb');
?>

<div class="auctions index">

<h2><?php __('Products');?></h2>
<blockquote><p>Manage your products from here. You will need to <a href="/admin/products/add">add a product</a> first, and then click on 'create auction' to begin selling on your website. This allows you to start multiple auctions  selling the same product. <span class="helplink">[ <a href="https://members.phppennyauction.com/link.php?id=12" target="_blank">Find out more &raquo;</a> ]</span></p></blockquote>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a Product', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if(!empty($products)): ?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort(__('Title',true), 'title');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Product Code',true), 'code');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Category',true), 'Category.name');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('MSRP (RRP)',true));?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Start Price',true));?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Fixed Price',true), 'fixed');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($products as $product):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
        			<img src="<?php echo $appConfigurations['url']?>/admin/img/add.png" alt="" align="left" />&nbsp;<?php echo $product['Product']['title']; ?> 
		</td>
		<td>
			<?php if(!empty($product['Product']['code'])) : ?>
				<?php echo $product['Product']['code']; ?>
			<?php else: ?>
				n/a
			<?php endif; ?>
		</td>
		<td>
			<?php echo $html->link($product['Category']['name'], array('admin' => false, 'controller'=> 'categories', 'action'=>'view', $product['Category']['id']), array('target' => '_blank')); ?>
		</td>
		<td>
			<?php if(!empty($product['Product']['rrp'])) : ?>
				<?php echo $number->currency($product['Product']['rrp'], $appConfigurations['currency']); ?>
			<?php else: ?>
				n/a
			<?php endif; ?>
		</td>
		<td>
			<?php echo $number->currency($product['Product']['start_price'], $appConfigurations['currency']); ?>
		</td>
		<td>
			<?php echo !empty($product['Product']['fixed']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<ul class="cssdropdown">
				<li class="headlink">Actions
				<ul>
				<li><?php echo $html->link(__('Edit', true), array('action' => 'edit', $product['Product']['id'])); ?></li>
				<li><?php echo $html->link(__('Images', true), array('controller' => 'images', 'action' => 'index', $product['Product']['id'])); ?></li>
				<?php if($languages > 1) : ?>
					<li><?php echo $html->link(__('Translations', true), array('controller' => 'translations', 'action' => 'index', $product['Product']['id'])); ?></li>
				<?php endif; ?>
				<li><?php echo $html->link(__('<span style="color: #f00;">Create Auction</span>', true), array('controller' => 'auctions', 'action' => 'add', $product['Product']['id'])); ?></li>
				<?php if(!empty($product['Auction'])) : ?>
					<li><?php echo $html->link(__('Auctions', true), array('action' => 'auctions', $product['Product']['id'])); ?></li>
				<?php endif; ?>
				<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $product['Product']['id']), null, sprintf(__('Are you sure you want to delete product titled: %s?', true) . "\\n\\n" . __('All of this product\'s auctions will be deleted as well!', true), $product['Product']['title'])); ?></li>
				</ul>
				</li>
			</ul>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('There are no products at the moment.');?></p>
<?php endif; ?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a Product', true), array('action' => 'add')); ?></li>
	</ul>
</div>
