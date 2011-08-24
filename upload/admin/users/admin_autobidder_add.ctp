<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Auto Bidders', true), '/admin/'.$this->params['controller'].'/autobidders');
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/autobidder_add');
echo $this->element('admin/crumb');
?>

<?php echo $form->create('User', array('url' => '/admin/users/autobidder_add'));?>
	<fieldset>
 		<legend><?php __('Add an Auto bidder');?></legend>
        
        <blockquote><p>Make sure to check the 'active' box if you want to use this autobidder on your website. Leaving the box unchecked will mean it will be not be active. </p></blockquote>
	<?php
		echo $form->input('username');
		echo $form->input('active');
	?>
	</fieldset>
<?php echo $form->end(__('Add Autobidder', true));?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to auto bidders', true), array('action' => 'autobidders')); ?> </li>
	</ul>
</div>
