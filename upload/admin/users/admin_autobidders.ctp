<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Auto Bidders', true), '/admin/'.$this->params['controller'].'/autobidders');
echo $this->element('admin/crumb');
?>

<h2><?php __('Auto Bidders');?></h2>

<blockquote><p>Auto-bidders or test-bidders mimic real-life users and can be used for testing your website prior to launch to make sure all the basic functions are correct and in working order. <span class="helplink">[ <a href="https://members.phppennyauction.com/link.php?id=20" target="_blank">Find out more &raquo;</a> ]</span> </p></blockquote>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new autobidder', true), array('action' => 'autobidder_add')); ?></li>
	</ul>
</div>

<?php if(!empty($users)): ?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('username');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort('Active');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	$delete = 1;
?>
	<tr<?php echo $class;?>>
		<td><?php echo $user['User']['username']; ?></td>
		<td>
			<?php if(!empty($user['User']['active'])) : ?>
				Yes
			<?php else: ?>
				No
			<?php endif; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action' => 'autobidder_edit', $user['User']['id'])); ?>
			<?php if(!empty($user['Auction'])) : ?>
				<?php $delete = 0; ?>
				/ <?php echo $html->link(__('Won Auctions', true), array('controller' => 'auctions', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(empty($user['Bid'])) : ?>
				/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $user['User']['id'], 'autobidder'), null, sprintf(__('Are you sure you want to delete the autobidder: %s?', true), $user['User']['username'])); ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
<p><?php __('There are no auto bidders at the moment.');?></p>
<?php endif;?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new autobidder', true), array('action' => 'autobidder_add')); ?></li>
	</ul>
</div>
