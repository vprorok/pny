<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb($product['Product']['title'], '/admin/products/edit/'.$product['Product']['id']);
$html->addCrumb(__('Product Images', true), '/admin/images/index/'.$product['Product']['id']);
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add/'.$product['Product']['id']);
echo $this->element('admin/crumb');
?>

<div class="auctionImages form">
<?php echo $form->create('Image', array('type' => 'file', 'url' => '/admin/images/add/'.$product['Product']['id']));?>
	<fieldset>
 		<legend><?php __('Add an Image to');?> <?php echo $product['Product']['title']; ?></legend>

		<?php echo $form->input('image1', array('label'=>'Image 1','type' => 'file')); ?>
		<?php echo $form->input('image2', array('label'=>'Image 2','type' => 'file')); ?>
		<?php echo $form->input('image3', array('label'=>'Image 3','type' => 'file')); ?>
	</fieldset>
<?php echo $form->end(__('Upload Images >>', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to images for ', true).$product['Product']['title'], array('action' => 'index', $product['Product']['id']));?></li>
	</ul>
</div>
