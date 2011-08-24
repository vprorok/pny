<?php
$html->addCrumb(__('Sources', true), '/admin/sources');
$html->addCrumb(__('Sources', true), '/admin/sources');
echo $this->element('admin/crumb');
?>

<div class="sources index">
<h2><?php __('Sources');?></h2>
<blockquote><p>Sources allow you to discover where your website visitors state that they are coming from so you can find out how effective your marketing is. Customemers select this option when registering for your website.</p></blockquote>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new source', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<div id="orderMessage" class="message" style="display: none"></div>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('extra');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<tbody id="sourceList">
<?php
$i = 0;
foreach ($sources as $source):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?> id="source_<?php echo $source['Source']['id'];?>">
		<td>
			<?php echo $source['Source']['name']; ?>
		</td>
		<td>
			<?php echo !empty($source['Source']['extra']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $source['Source']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action'=>'delete', $source['Source']['id']), null, sprintf(__('Are you sure you want to delete the source titled: %s?', true), $source['Source']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
	<p><?php __('There are no source at the moment.');?></p>
<?php endif;?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new source', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#sourceList').sortable({
        'items': 'tr',
        update: function(){
            $.ajax({
                url: '/admin/sources/saveorder',
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
