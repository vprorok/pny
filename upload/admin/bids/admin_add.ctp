<?php
$html->addCrumb(Inflector::humanize('Users'), '/admin/users');
$html->addCrumb($user['User']['username'], '/admin/users/view/'.$user['User']['id']);
$html->addCrumb(__('Bids', true), '/admin/bids/user/'.$user['User']['id']);
$html->addCrumb(__('Add', true), '/admin/bids/add/'.$user['User']['id']);
echo $this->element('admin/crumb');
?>

<p>
<?php __('To manually enter in a bid transaction fill out the form below.');?>
<?php __('A positive total will add bids to the users account (i.e. credit their account).');?>
<?php __('Use a negative number to subtract bids (i.e. debit) the users bid account.');?>
</p>

<?php echo $form->create(null, array('url' => '/admin/bids/add/'.$user['User']['id']));?>
	<fieldset>
 		<legend><?php __('Add a Bid Transaction');?></legend>
	<?php
		echo $form->input('description', array('label' => __('Description *', true)));
		echo $form->input('total', array('label' => __('Total *', true)));
	?>
	</fieldset>
<?php echo $form->end(__('Add Transaction', true));?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to the users bids', true), array('action' => 'user', $user['User']['id'])); ?> </li>
	</ul>
</div>
