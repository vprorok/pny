<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Categories', true), '/admin/categories');
$html->addCrumb(__('Edit', true), '/admin/categories/edit/'.$this->data['Category']['id']);
echo $this->element('admin/crumb');
?>

<div class="categories form">
<?php echo $form->create('Category', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('Edit Category');?></legend>
	<?php echo $form->input('id'); ?>

	<label for="CategoryParentId">Parent Category</label>
	<?php echo $form->select('parent_id', $parentCategories, null, array(), 'No Parent (top level)'); ?>

	<?php
		echo $form->input('name', array('label' => __('Name *', true)));
		echo $form->input('meta_description');
		echo $form->input('meta_keywords');
		
		echo $form->input('featured');
	?>

	<?php if(!empty($this->data['Category']['image']) && (!is_array($this->data['Category']['image']))) : ?>
	<label><?php __('Current Image');?>:</label>
	<div><?php echo $html->image('category_images/max/'.$this->data['Category']['image']); ?></div>
	<label>&nbsp;</label>
	<?php echo $form->checkbox('image_delete', array('value' => $this->data['Category']['id'])); ?> Delete this image?
	<?php endif; ?>

	<?php echo $form->input('image', array('type' => 'file'));?>

	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>
</div>

<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to categories', true), array('action'=>'index'));?></li>
		<?php if(!empty($this->data['ChildCategory'])) : ?>
         		<li><?php echo $html->link(__('View Child Categories', true), array('action' => 'index', $form->value('Category.id'))); ?></li>
        <?php elseif(empty($this->data['Auction'])) : ?>
				<li><?php echo $html->link(__('Delete category', true), array('action' => 'delete', $form->value('Category.id')), null, sprintf(__('Are you sure you want to delete this category?', true))); ?></li>
		<?php endif; ?>
	</ul>
</div>
