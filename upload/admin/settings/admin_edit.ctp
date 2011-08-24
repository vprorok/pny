<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Setting']['id']);
echo $this->element('admin/crumb');
?>

<div class="settings form">
<?php echo $form->create('Setting');?>
	<fieldset>
 		<legend><?php echo sprintf(__('Edit the %s setting', true), Inflector::humanize($this->data['Setting']['name']));?></legend>
 		
 		<br>
 		<p><?php echo $this->data['Setting']['description'] ?></p>
 		<br>
 	<?php
 	if ($this->data['Setting']['name']=='autobidders') {
 		?>
 		<p style="color:red; font-weight:bold"><?php __('WARNING: It is the site owner\'s sole responsibility to verify that autobidders are lawful for use on a live website.') ?> <a href="http://www.phppennyauction.com/terms/">Check the Terms of Use for details.</a></p>
 		<?php	
 	}
 	?>
 		
 		
	<?php
		echo $form->input('id');
		echo $form->hidden('name');
		
			if ($this->data['Setting']['type']==SETTING_TYPE_ONOFF) {
				echo $form->input('value', array('label' => __('Value *', true), 'options'=>$setting_options['onoff']));
			} elseif ($this->data['Setting']['type']==SETTING_TYPE_TRUEFALSE) {
				echo $form->input('value', array('label' => __('Value *', true), 'options'=>$setting_options['truefalse']));
			} elseif ($this->data['Setting']['type']==SETTING_TYPE_YESNO) {
				echo $form->input('value', array('label' => __('Value *', true), 'options'=>$setting_options['yesno']));
			} else {
				echo $form->input('value', array('label' => __('Value *', true)));
			}
			
		
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>
</div>

<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to settings', true), array('action'=>'index'));?></li>
	</ul>
</div>
