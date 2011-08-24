<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb($product['Product']['title'], '/admin/products/edit/'.$product['Product']['id']);
$html->addCrumb(__('Product Images', true), '/admin/images/index/'.$product['Product']['id']);
$html->addCrumb(__('Select Default', true), '/admin/'.$this->params['controller'].'/add/'.$product['Product']['id']);
echo $this->element('admin/crumb');
?>

<?php if($images > 0) : ?>
	<div class="auctionImages form">
	<?php echo $form->create('ImageDefault', array('type' => 'file', 'url' => '/admin/image_defaults/add/'.$product['Product']['id']));?>
		<fieldset>
	 		<legend><?php __('Select Image(s) for: ');?> <?php echo $product['Product']['title']; ?></legend>

			<table cellpadding="0" cellspacing="0">
				<tr>
					<th><?php __('Name');?></th>
					<th><?php __('Image');?></th>
				</tr>
				<tbody>
					<?php
					$i = 0;
					foreach ($images as $image):
						$class = null;
						if ($i++ % 2 == 0) {
							$class = ' class="altrow"';
						}
					?>
						<tr<?php echo $class;?>>
							<td>
								<?php echo $form->input('id.'.$image['ImageDefault']['id'], array('type' => 'checkbox', 'label' => $image['ImageDefault']['name'])); ?>
							</td>
							<td>
								<?php echo $html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$image['ImageDefault']['image']); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</fieldset>
	<?php echo $form->end(__('Select Image >>', true));?>
	</div>
<?php else : ?>
	<p><?php __('There are no default images at the moment.');?></p>
<?php endif; ?>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to images for ', true).$product['Product']['title'], array('controller' => 'images', 'action' => 'index', $product['Product']['id']));?></li>
	</ul>
</div>
