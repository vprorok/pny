<?php
$html->addCrumb(__('Manage Pages', true), '/admin/pages');
$html->addCrumb(__('Pages', true), '/admin/pages');
echo $this->element('admin/crumb');
?>

<div class="pages index">

<h2><?php __('Pages');?></h2>

<blockquote><p>Add new content &amp; pages here to your website. You can specify where you want the pages to appear - either 'top' (at the top of the page), 'bottom' (at the bottom of the page), or neither ('static'). </p></blockquote>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a new page', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<h2><?php __('Top Menu Pages');?></h2>
<?php if(!empty($topPages)):?>

<div class="tip">To change the order that the pages are displayed, drag and drop the ordering by clicking and dragging on the table below.</div>

<div id="orderMessageTop" class="message" style="display: none"></div>
<table id="pageListTop" cellpadding="0" cellspacing="0">
<tr>
	<th><?php __('Name');?></th>
	<th><?php __('Last Modified');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($topPages as $page):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow sortable_top"';
	}else{
		$class = ' class="sortable_top"';
	}
?>
	<tr <?php echo $class;?> id="page_<?php echo $page['Page']['id'];?>">
		<td>
			<?php echo $page['Page']['name']; ?>
		</td>
		<td>
			<?php echo $time->niceShort($page['Page']['modified']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'controller' => false, 'action'=>'view', $page['Page']['slug']), array('target' => '_blank')); ?>
			/ <?php echo $html->link(__('Edit', true), array('action'=>'edit', $page['Page']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $page['Page']['id']), null, sprintf(__('Are you sure you want to delete the page named: %s?', true), $page['Page']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php else:?>
	<p><?php __('There are no top menu pages at the moment.');?></p>
<?php endif;?>

<h2><?php __('Bottom Menu Pages');?></h2>
<?php if(!empty($bottomPages)):?>

<div class="tip">To change the order that the pages are displayed, drag and drop the ordering by clicking and dragging on the table below.</div>

<div id="orderMessageBottom" class="message" style="display: none"></div>
<table id="pageListBottom" cellpadding="0" cellspacing="0">
<tr>
	<th><?php __('Name');?></th>
	<th><?php __('Last Modified');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($bottomPages as $page):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow sortable_bottom"';
	}else{
		$class = ' class="sortable_bottom"';
	}
?>
	<tr <?php echo $class;?> id="page_<?php echo $page['Page']['id'];?>">
		<td>
			<?php echo $page['Page']['name']; ?>
		</td>
		<td>
			<?php echo $time->niceShort($page['Page']['modified']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'controller' => false, 'action'=>'view', $page['Page']['slug']), array('target' => '_blank')); ?>
			/ <?php echo $html->link(__('Edit', true), array('action'=>'edit', $page['Page']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $page['Page']['id']), null, sprintf(__('Are you sure you want to delete the page named: %s?', true), $page['Page']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php else:?>
	<p><?php __('There are no bottom menu pages at the moment.');?></p>
<?php endif;?>

<h2><?php __('Static Pages');?></h2>

<p><?php __('Static pages are not assigned to either the top menu or the bottom menu.');?></p>

<?php if(!empty($staticPages)):?>
<div id="sortableStatus" class="message" style="display: none"></div>
<table id="pageList" cellpadding="0" cellspacing="0">
<tr>
	<th><?php __('Name');?></th>
	<th><?php __('Last Modified');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($staticPages as $page):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr <?php echo $class;?>>
		<td>
			<?php echo $page['Page']['name']; ?>
		</td>
		<td>
			<?php echo $time->niceShort($page['Page']['modified']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'controller' => false, 'action'=>'view', $page['Page']['slug']), array('target' => '_blank')); ?>
			/ <?php echo $html->link(__('Edit', true), array('action'=>'edit', $page['Page']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $page['Page']['id']), null, sprintf(__('Are you sure you want to delete the page named: %s?', true), $page['Page']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php else:?>
	<p><?php __('There are no static pages at the moment.');?></p>
<?php endif;?>


</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a new page', true), array('action' => 'add')); ?></li>
	</ul>
</div>
