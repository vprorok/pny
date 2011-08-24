<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Default Images', true), '/admin/image_defaults');
echo $this->element('admin/crumb');
?>

<div class="defaultImages index">
	<h2><?php __('Default Images');?></h2>
<blockquote><p>
	<p><?php __('Default Images allow you to set different images for different versions of your website.');?></p>

	<p><?php __('In the CMS add the file name of the image you which to create.  Then using your favour FTP client, connect to the FTP and load the images into the first folder.  The folder you want is \'app/webroot/img/default_images\'');?></p></p></blockquote>

	<h2><?php __('Thumbnail Generator');?></h2>

	<p><?php echo $html->link(__('Click here to run the thumbnail generator', true), array('action' => 'thumbs')); ?></p>

	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('Create a new default image', true), array('action' => 'create')); ?></li>
		</ul>
	</div>

	<?php if($paginator->counter() > 0):?>

	<?php echo $this->element('admin/pagination'); ?>

	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $paginator->sort('name');?></th>
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
				<tr<?php echo $class;?>>
					<td><?php echo $image['ImageDefault']['name']; ?></td>
					<td><?php echo $image['ImageDefault']['image']; ?></td>
					<td class="actions">
						<?php echo $html->link(__('Delete', true), array('action'=>'delete', $image['ImageDefault']['id']), null, sprintf(__('Are you sure you want to delete this default image?', true))); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('There are no default images at the moment.');?></p>
<?php endif; ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new default image', true), array('action' => 'create')); ?></li>
	</ul>
</div>
