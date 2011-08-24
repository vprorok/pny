<?php
$html->addCrumb(__('Newsletter', true), '/admin/newsletter');
echo $this->element('admin/crumb');
?>

<div class="newsletters index">
<h2><?php __('Newsletters');?></h2>


<blockquote><p>Use Newsletters to send out important information to your customers, including promotions, general offers and more. Newsletters are a great way of getting the word across about your product(s) for sale, instantly and at no cost. <span class="helplink">[ <a href="https://members.phppennyauction.com/link.php?id=21" target="_blank">Find out more &raquo;</a> ]</span></p></blockquote>
<br />
<br />

<?php echo $html->link(__('Export subsribers (.csv)', true), array('action' => 'exportsubscribers'));?>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('subject');?></th>
	<th><?php echo $paginator->sort('sent');?></th>
	<th><?php echo $paginator->sort('Date Sent', 'modified');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($newsletters as $newsletter):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $newsletter['Newsletter']['subject']; ?>
		</td>
		<td>
			<?php echo !empty($newsletter['Newsletter']['sent']) ? __('Yes', true): __('No', true); ?>
		</td>
		<td>
			<?php if(!empty($newsletter['Newsletter']['sent'])) : ?>
				<?php echo $time->nice($newsletter['Newsletter']['modified']); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $time->nice($newsletter['Newsletter']['created']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action'=>'view', $newsletter['Newsletter']['id'])); ?>
			<?php if(empty($newsletter['Newsletter']['sent'])) : ?>
			<?php if (isset($is_scd) && $is_scd===true): ?>
				/ <?php echo $html->link(__('Send', true), array('action'=>'send', $newsletter['Newsletter']['id']), null, sprintf(__('Are you sure you want to send the newsletter with the subject: %s?', true), $newsletter['Newsletter']['subject'])); ?>
				/ <?php echo $html->link(__('Send Test', true), array('action'=>'test', $newsletter['Newsletter']['id']), null, sprintf(__('Are you about to send a test to: %s?', true), $appConfigurations['email'])); ?>
			<?php else: ?>
				/ <?php echo $html->link(__('Send', true), array('action'=>'send', $newsletter['Newsletter']['id']), null, sprintf(__('Are you sure you want to send the newsletter with the subject: %s?', true), $newsletter['Newsletter']['subject'])); ?>
				/ <?php echo $html->link(__('Send Test', true), array('action'=>'test', $newsletter['Newsletter']['id']), null, sprintf(__('Are you about to send a test to: %s?', true), $appConfigurations['email'])); ?>
			<?php endif; ?>
				/ <?php echo $html->link(__('Edit', true), array('action'=>'edit', $newsletter['Newsletter']['id'])); ?>
				/ <?php echo $html->link(__('Delete', true), array('action'=>'delete', $newsletter['Newsletter']['id']), null, sprintf(__('Are you sure you want to delete the newsletter with the subject: %s?', true), $newsletter['Newsletter']['subject'])); ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php echo $this->element('admin/pagination'); ?>
<?php else:?>
	<p><?php __('There are no newsletters at the moment.');?></p>
<?php endif;?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('New Newsletter', true), array('action'=>'add')); ?></li>
	</ul>
</div>