<?php
$html->addCrumb(__('Manage Auctions',true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb($product['Product']['title'], '/admin/products/edit/'.$product['Product']['id']);
$html->addCrumb(__('Translations', true), '/admin/translations/index/'.$product['Product']['id']);
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/add/'.$this->data['Translation']['id']);
echo $this->element('admin/crumb');
?>

<div class="translation form">
<?php echo $form->create('Translation', array('url' => '/admin/translations/edit/'.$this->data['Translation']['id']));?>
	<fieldset>
 		<legend><?php __('Edit an Translation for');?> <?php echo $product['Product']['title']; ?></legend>
		<?php
			echo $form->input('id');
			echo $form->input('language_id', array('empty' => 'Select'));
			echo $form->input('title');
			echo $form->input('brief');
			?>
			<label for="TranslationDescription">Description</label>
			<?php echo $fck->input('Translation.description'); ?>
			<p>&nbsp;</p>
			<?php
			echo $form->input('meta_description');
			echo $form->input('meta_keywords');
			echo $form->input('delivery_information');
		?>
	</fieldset>
<?php echo $form->end(__('Save Changes >>', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to translations for ', true).$product['Product']['title'], array('action' => 'index', $product['Product']['id']));?></li>
	</ul>
</div>
