<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
$html->addCrumb(__('Rewards Points', true), '/admin/points/user/'.$user['User']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php __('Rewards Points');?></h2>
<blockquote><p>Award users with any number of points here, manually. They will be credited instantly. Use negative figures to subtract, e.g., "-10". </p></blockquote>

<?php echo $form->create('Point', array('url' => '/admin/points/user/'.$user['User']['id']));?>

<fieldset>

<?php echo $form->input('id'); ?>
<?php echo $form->input('points'); ?>

<?php echo $form->end(__('Save Changes >>', true));?>

</fieldset>