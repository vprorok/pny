<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php echo $news['News']['title']; ?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<?php
			$html->addCrumb(__('Latest News', true), '/admin/news');
			$html->addCrumb($news['News']['title'], '/admin/news/view/'.$news['News']['id']);
			echo $this->element('crumb');
			?>
			
			<p><?php echo nl2br($news['News']['content']); ?></p>
			
			<div class="meta">Date &amp; Time Posted <?php echo $time->niceShort($news['News']['created']); ?></div>
			
			<p><?php echo $html->link('<< Back to news', array('action'=>'index')); ?></p>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>