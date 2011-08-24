<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb($product['Product']['title'], '/admin/products/edit/'.$product['Product']['id']);
$html->addCrumb(__('Translations', true), '/admin/translations/index/'.$product['Product']['id']);
echo $this->element('admin/crumb');
?>

<div class="bidPackages index">
<h2><?php __('Translations');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a new translation', true), array('action' => 'add', $product['Product']['id'])); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('Language', 'Lanaguage.language');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($translations as $translation):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $translation['Language']['language']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $translation['Translation']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $translation['Translation']['id']), null, sprintf(__('Are you sure you want to delete this translation for: %s?', true), $translation['Language']['language'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
	<p><?php __('There are no translations for this product.');?></p>
<?php endif;?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a new translation', true), array('action' => 'add', $product['Product']['id'])); ?></li>
	</ul>
</div>
