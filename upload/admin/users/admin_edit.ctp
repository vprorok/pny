<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['User']['id']);
echo $this->element('admin/crumb');
?>

<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit a User');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('username');
		echo $form->input('first_name');
		echo $form->input('last_name');
		echo $form->input('email');
		if(!empty($appConfigurations['simpleBids'])) :
			echo $form->input('bid_balance');
		endif;
		echo $form->input('date_of_birth', array('minYear' => $appConfigurations['Dob']['year_min'], 'maxYear' => $appConfigurations['Dob']['year_max'], 'label' => 'Date of Birth'));
		echo $form->input('gender_id', array('type' => 'select', 'label' => 'Gender'));
		if(!empty($appConfigurations['taxNumberRequired'])) {
			echo $form->input('tax_number');
		}
		echo $form->input('newsletter', array('label' => 'Receive the newsletter?'));

		if($this->data['User']['id'] !== $session->read('Auth.User.id')) :
			echo $form->input('active');
			echo $form->input('admin', array('label' => 'Grant this user admin rights?'));
		endif;
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('View', true), array('action' => 'view', $this->data['User']['id'])); ?></li>
		<?php if(!empty($this->data['Bid'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Bids', true), array('controller' => 'bids', 'action' => 'user', $this->data['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($this->data['Bidbutler'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Bid Butlers', true), array('controller' => 'bidbutlers', 'action' => 'user', $this->data['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($this->data['Auction'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Won Auctions', true), array('controller' => 'auctions', 'action' => 'user', $this->data['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($this->data['Account'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Account', true), array('controller' => 'accounts', 'action' => 'user', $this->data['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($this->data['Referred'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Referred Users', true), array('controller' => 'referrals', 'action' => 'user', $this->data['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($delete)) : ?>
			<li><?php echo $html->link(__('Delete User', true), array('action' => 'delete', $this->data['User']['id']), null, sprintf(__('Are you sure you want to delete this user?', true))); ?> </li>
		<?php endif; ?>
		<li><?php echo $html->link(__('<< Back to users', true), array('action' => 'index')); ?> </li>
	</ul>
</div>
