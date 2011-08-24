<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb($product['Product']['title'], '/admin/products/edit/'.$product['Product']['id']);
echo $this->element('admin/crumb');
?>

<div class="auctions index">

<h2><?php __('Auctions for: ');?><?php echo $product['Product']['title']; ?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create an Auction', true), array('controller' => 'auctions', 'action' => 'add', $product['Product']['id'])); ?></li>
	</ul>
</div>

<?php if(!empty($auctions)):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('featured');?></th>
	<th><?php echo $paginator->sort('peak_only');?></th>
	<th><?php echo $paginator->sort('fixed_price');?></th>
	<th><?php echo $paginator->sort('end_time');?></th>
	<th><?php echo $paginator->sort('max_end_time');?></th>
	<th><?php echo $paginator->sort('Active');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort('Status', 'Status.name');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($auctions as $auction):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo !empty($auction['Auction']['featured']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo !empty($auction['Auction']['peak_only']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo !empty($auction['Product']['fixed']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo $time->nice($auction['Auction']['end_time']); ?>
		</td>
		<td>
			<?php if(!empty($auction['Auction']['max_end'])) : ?>
				<?php echo $time->nice($auction['Auction']['max_end_time']); ?>
			<?php else : ?>
			n/a
			<?php endif; ?>
		</td>
		<td>
			<?php echo !empty($auction['Auction']['active']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo $auction['Status']['name']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'controller' => 'auctions', 'action' => 'view', $auction['Auction']['id']), array('target' => '_blank')); ?>
			<?php if(!empty($auction['Winner']['id'])) : ?>
				<?php if($auction['Winner']['autobidder'] == 0) : ?>
					/ <?php echo $html->link(__('View Winner', true), array('controller' => 'auctions', 'action' => 'winner', $auction['Auction']['id'])); ?>
				<?php endif; ?>
			<?php elseif(empty($auction['Auction']['closed'])) : ?>
				/ <?php echo $html->link(__('Edit', true), array('controller' => 'auctions', 'action' => 'edit', $auction['Auction']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($auction['Bid'])) : ?>
				/ <?php echo $html->link(__('Refund Bids', true), array('controller' => 'auctions', 'action' => 'refund', $auction['Auction']['id']), null, sprintf(__('Are you sure you want to refund ALL the bids for this auction titled: %s?  This will delete ALL the bids on the auction to date.  If the auction has a winner, you should update the status to \'Refunded\' for the auction if you are refunding the winner.', true), $auction['Product']['title'])); ?>
			<?php else: ?>
				/ <?php echo $html->link(__('Delete', true), array('controller' => 'auctions', 'action' => 'delete', $auction['Auction']['id']), null, sprintf(__('Are you sure you want to delete auction titled: %s?', true), $auction['Product']['title'])); ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('There are no auctions at the moment.');?></p>
<?php endif; ?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create an Auction', true), array('controller' => 'auctions', 'action' => 'add', $product['Product']['id'])); ?></li>
	</ul>
</div>
