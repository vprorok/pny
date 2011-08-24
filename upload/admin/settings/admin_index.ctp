<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
echo $this->element('admin/crumb');
?>

<div class="settings index">

<h2><?php __('Settings');?></h2>
<blockquote><p>Customize your website's general settings here. </p></blockquote>
<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('description');?></th>
	<th><?php echo $paginator->sort('value');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($settings as $setting):

if ($setting['Setting']['name']=='local_license_key') { continue; }

	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo Inflector::humanize($setting['Setting']['name']); ?>
		</td>
		<td>
			<?php echo $setting['Setting']['description']; ?>
		</td>
		<td>
			<?php 
			
			if ($setting['Setting']['type']==SETTING_TYPE_ONOFF) {
				echo $setting_options['onoff'][$setting['Setting']['value']];
			} elseif ($setting['Setting']['type']==SETTING_TYPE_TRUEFALSE) {
				echo $setting_options['truefalse'][$setting['Setting']['value']];
			} elseif ($setting['Setting']['type']==SETTING_TYPE_YESNO) {
				echo $setting_options['yesno'][$setting['Setting']['value']];
			} else {
				echo $setting['Setting']['value']; 
			}
			
			?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $setting['Setting']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->element('admin/pagination'); ?>
<div style="clear:both;"></div>
