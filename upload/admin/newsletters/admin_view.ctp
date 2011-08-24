<?php
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/view/'.$this->data['Newsletter']['id']);
echo $this->element('admin/crumb');
?>
<div class="newsletters view">
	<h2><?php __('Newsletters');?></h2>
	<h3><?php echo $newsletter['Newsletter']['subject']; ?></h3>
	<?php echo $newsletter['Newsletter']['body']; ?>
	<div>
		<?php echo __('Created', true);?>:
		<?php echo $time->nice($newsletter['Newsletter']['created']); ?>
	</div>
	<div>
		<?php echo __('Modified', true);?>:
		<?php echo $newsletter['Newsletter']['modified']; ?>
	</div>
	<div>
		<?php echo __('Sent', true);?>:
		<?php echo !empty($newsletter['Newsletter']['sent']) ? __('Yes', true) : __('No', true); ?>
	</div>
</div>
<div class="actions">
	<ul>
		<?php if(empty($newsletter['Newsletter']['sent'])) : ?>
			<li><?php echo $html->link(__('Edit Newsletter', true), array('action'=>'edit', $newsletter['Newsletter']['id'])); ?> </li>
			<li><?php echo $html->link(__('Delete Newsletter', true), array('action'=>'delete', $newsletter['Newsletter']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $newsletter['Newsletter']['id'])); ?> </li>
		<?php endif; ?>
		<li><?php echo $html->link(__('List Newsletters', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New Newsletter', true), array('action'=>'add')); ?> </li>
	</ul>
</div>
