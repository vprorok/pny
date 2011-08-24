<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Bids Placed', true), '/admin/bids');
echo $this->element('admin/crumb');
?>

<div class="auctions index">

<h2><?php __('Bids Placed');?></h2>
<blockquote><p>Below you can see an overview of all bids that have been placed. Clicking on 'View only this auction' will display the bid history for each particular auction. </p></blockquote>
<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort(__('Auction ID', true), 'Auction.id');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Username', true), 'User.username');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Auction Title', true), 'Auction.id');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /></th>
	<th><?php echo $paginator->sort(__('Bid Type', true), 'Bid.description');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /></th>
	<th><?php echo $paginator->sort(__('Date Placed', true), 'Bid.created');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /></th>
	<th class="actions"><?php __('Options');?></th>
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
			<?php echo $bid['Auction']['id']; ?>
		</td>
		<td>
			<?php echo $html->link($bid['User']['username'], array('controller'=> 'users', 'action' => 'edit', $bid['User']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($bid['Auction']['Product']['title'], array('admin' => false, 'controller'=> 'auctions', 'action'=>'view', $bid['Auction']['id']), array('target' => '_blank')); ?>
		</td>
		<td>
			<?php echo $bid['Bid']['description']; ?>
			<?php if(!empty($bid['User']['autobidder'])) : ?>
				- <?php __('Autobid');?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $time->niceShort($bid['Bid']['created']); ?>
		</td>
		<td>
			<?php echo $html->link(__('View only this Auction', true), array('action' => 'auction', $bid['Auction']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('No bids have been placed on the site yet.');?></p>
<?php endif; ?>
</div>
