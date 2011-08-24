<!-- Start Results Bar -->
<?php
	$paginator->options(array('url'=>Router::getParam('pass')));
?>
<div class="paging">
	<div class="pagenumber" style="width:500px;">
		<ul>
			<?php if($paginator->hasPrev()) : ?>
				<li><?php echo $paginator->first(__('« First', true), null, null, array('class' => 'disabled')); ?></li>
				<li><?php echo $paginator->prev(__('« Previous', true), null, null, array('class' => 'disabled')); ?></li>
			<?php endif; ?>
				
			<?php if (is_string($paginator->numbers())): ?>
				<?php echo $paginator->numbers(array('separator' => '</li><li>', 'before' => '<li>', 'after' => '</li>'));?>
			<?php endif; ?>
			
			<?php if($paginator->hasNext()): ?>
				<li><?php echo $paginator->next(__('Next »', true), null, null, array('class' => 'disabled')); ?></li>
				<li><?php echo $paginator->last(__('Last »', true), null, null, array('class' => 'disabled')); ?></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="totalresults">
		<?php echo $paginator->counter(array('format' => '<strong>%count%</strong><span class="side_2px">Results</span><strong>%start% - %end%</strong><span class="side_2px">Results</span>')); ?>
	</div>
</div>
<!-- End Results Bar -->