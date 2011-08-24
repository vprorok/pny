<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb('Admin User Won Auctions', '/admin/auctions/adminusers');
echo $this->element('admin/crumb');
?>

<div class="auctions index">

<h2><?php __('Admin Users Won Auctions');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Manage your Products', true), array('controller' => 'products', 'action' => 'index')); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort(__('ID',true), 'Auction.id');?></th>
	<th><?php echo $paginator->sort(__('Title',true), 'Product.title');?></th>
	<th><?php echo $paginator->sort(__('Category',true), 'Category.name');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Featured',true));?></th>
	<th><?php echo $paginator->sort(__('Fixed Price', true), 'Product.fixed_price');?></th>
	<th><?php echo $paginator->sort(__('End Time', true), 'end_time');?></th>
	<th><?php echo $paginator->sort(__('Price', true), 'price');?></th>
	<?php if(!empty($appConfigurations['autobids'])) : ?>
		<th><?php echo $paginator->sort(__('Minimum Price', true), 'minimum_price');?></th>
	<?php endif; ?>	
	<th><?php echo $paginator->sort(__('Active', true));?></th>
	<th><?php echo $paginator->sort(__('Hits', true));?></th>
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
			<?php echo $auction['Auction']['id']; ?>
		</td>
		<td>
			<?php echo $auction['Product']['title']; ?>
		</td>
		<td>
			<?php echo $html->link($auction['Product']['Category']['name'], array('admin' => false, 'controller'=> 'categories', 'action'=>'view', $auction['Product']['Category']['id']), array('target' => '_blank')); ?>
		</td>
		<td>
			<?php echo !empty($auction['Auction']['featured']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo !empty($auction['Product']['fixed']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo $time->nice($auction['Auction']['end_time']); ?>
		</td>
		<td>
			<?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?>
		</td>
		<?php if(!empty($appConfigurations['autobids'])) : ?>
			<td>
				<?php echo $number->currency($auction['Auction']['minimum_price'], $appConfigurations['currency']); ?>
			</td>
		<?php endif; ?>
		<td>
			<?php echo !empty($auction['Auction']['active']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo $auction['Auction']['hits']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'action' => 'view', $auction['Auction']['id']), array('target' => '_blank')); ?>
			/ <?php echo $html->link(__('List Similar', true), array('action' => 'add', $auction['Auction']['product_id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $auction['Auction']['id']), null, sprintf(__('Are you sure you want to delete auction titled: %s?', true), $auction['Product']['title'])); ?>
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
		<li><?php echo $html->link(__('Manage your Products', true), array('controller' => 'products', 'action' => 'index')); ?></li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		if($('#selectStatus').length){
			$('#selectStatus').change(function(){
				location.href = '/admin/auctions/won/' + $('#selectStatus').val();
			});
		}
	});
</script>