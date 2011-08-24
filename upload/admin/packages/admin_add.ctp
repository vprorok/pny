<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Packages', true), '/admin/packages');
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="bidPackages form">
<?php echo $form->create();?>
	<fieldset>
 		<legend><?php __('Add a Package');?></legend>
	<?php
		echo $form->input('name', array('label' => __('Name *', true)));
		echo $form->input('bids', array('label' => __('Number of Bids *', true)));
		echo $form->input('price', array('label' => __('Price *', true)));
		
		if(Configure::read('App.rewardsPoint')) {
			echo $form->input('PackagePoint.points', array('label' => __('Package Points *', true)));
		}
		
		if(Configure::read('PaymentGateways.custom.active')){
			echo $form->input('gateway_url', array('label' => __('Gateway URL', true)));
		}
	?>
	</fieldset>
<?php echo $form->end('Add Package');?>
</div>

<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to packages', true), array('action' => 'index'));?></li>
	</ul>
</div>
