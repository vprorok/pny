    <?php if(!empty($bottom_pages)):?>
        <?php foreach($bottom_pages as $page):?>
            <?php echo $html->link($page['Page']['name'], array('controller' => 'pages', 'action'=>'view', $page['Page']['slug'])); ?> 
        <?php endforeach;?>
        	<?php echo $html->link('Contact', array('controller' => 'contact', 'action'=>'index')); ?>
    <?php endif;?>