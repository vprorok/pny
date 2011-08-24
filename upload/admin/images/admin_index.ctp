<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb($product['Product']['title'], '/admin/products/edit/'.$product['Product']['id']);
$html->addCrumb(__('Product Images', true), '/admin/images/index/'.$product['Product']['id']);
echo $this->element('admin/crumb');
?>

<div class="auctionImages index">
	<h2><?php __('Images for');?> <?php echo $product['Product']['title']; ?></h2>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('Upload a new image', true), array('action' => 'add', $product['Product']['id'])); ?></li>
			<?php if($this->requestAction('/image_defaults/count') > 0) : ?>
				<li><?php echo $html->link(__('Add a default image', true), array('controller' => 'image_defaults', 'action' => 'add', $product['Product']['id'])); ?></li>
			<?php endif; ?>
		</ul>
	</div>

	<?php if($paginator->counter() > 0):?>

	<?php echo $this->element('admin/pagination'); ?>

	<p><?php __('To change the order that the images are displayed, drag and drop the ordering by clicking and draging on the table below.');?></p>

	<p><?php __('The first image will be the one that shows as the thumbnail in the auction listing.');?></p>

	<div id="orderMessage" class="message" style="display: none"></div>

	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $paginator->sort('image');?></th>
			<th class="actions"><?php __('Options');?></th>
		</tr>
		<tbody id="imageList">
			<?php
			$i = 0;
			foreach ($images as $image):
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
			?>
				<tr<?php echo $class;?> id="image_<?php echo $image['Image']['id'];?>">
					<td>
						<?php if(!empty($image['Image']['image_default_id'])) : ?>
							<?php echo $html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$image['ImageDefault']['image']); ?>
						<?php else: ?>
							<?php echo $html->image('product_images/thumbs/'.$image['Image']['image']); ?>
						<?php endif; ?>
					</td>
					<td class="actions">
						<?php echo $html->link(__('Delete', true), array('action'=>'delete', $image['Image']['id']), null, sprintf(__('Are you sure you want to delete this image?', true))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('There are no images at the moment.');?></p>
<?php endif; ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Upload a new image', true), array('action' => 'add', $product['Product']['id'])); ?></li>
		<?php if($this->requestAction('/image_defaults/count') > 0) : ?>
			<li><?php echo $html->link(__('Add a default image', true), array('controller' => 'image_defaults', 'action' => 'add', $product['Product']['id'])); ?></li>
		<?php endif; ?>
		<li><?php echo $html->link(__('<< Back to Products', true), array('controller' => 'products', 'action' => 'index')); ?></li>
	</ul>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#imageList').sortable({
        'items': 'tr',
        update: function(){
            $.ajax({
                url: '/admin/images/saveorder/<?php echo $product['Product']['id'];?>',
                type: 'POST',
                data: $(this).sortable('serialize'),
                success: function(data){
                    $('#orderMessage').html(data).show('fast').animate({opacity: 1.0}, 2000).fadeOut('slow');
                }
            });
        }
    });
});
</script>
