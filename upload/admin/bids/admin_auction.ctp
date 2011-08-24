<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb($auction['Product']['title'], '/admin/auctions/edit/'.$auction['Auction']['id']);
$html->addCrumb(__('Bids Placed', true), '/admin/bids/auctions'.$auction['Auction']['id']);
echo $this->element('admin/crumb');
?>

<div class="auctions index">

<h2><?php __('Bids Placed on Auction');?> <?php echo $auction['Product']['title']; ?></h2>

<?php if(!empty($appConfigurations['autobids'])) : ?>
	<?php if(!empty($realBidsOnly)) : ?>
		<p><?php echo $html->link('Click here to view all the bids on this auction.', array('action' => 'auction', $auction['Auction']['id'])); ?></p>
	<?php else : ?>
		<p><?php echo $html->link('Click here to view only bids placed by real users.', array('action' => 'auction', $auction['Auction']['id'], true)); ?></p>
	<?php endif; ?>
	
	
<?php endif; ?>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('Username', 'User.username');?></th>
	<th><?php echo $paginator->sort('Bid Type', 'Bid.description');?></th>
	<th><?php echo $paginator->sort('Date Placed', 'Bid.created');?></th>
</tr>
<?php
$i = 0;
foreach ($bids as $bid):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $html->link($bid['User']['username'], array('controller'=> 'users', 'action' => 'edit', $bid['User']['id'])); ?>
		</td>
		<td>
			<?php echo $bid['Bid']['description']; ?>
			<?php if(!empty($bid['User']['autobidder'])) : ?>
				- Autobid
			<?php endif; ?>
		</td>
		<td>
			<?php echo $time->niceShort($bid['Bid']['created']); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('No bids have been placed on this auction yet.');?></p>
<?php endif; ?>
</div>
