<?php
$html->addCrumb('Manage Users', '/admin/users');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb('Online Users', '/admin/'.$this->params['controller'].'/online');
echo $this->element('admin/crumb');
?>

<h2><?php __('Online Users'); ?></h2>

<?php if(!empty($users)) : ?>
	<table class="results" cellpadding="0" cellspacing="0">
	  <tr>
		<th><?php __('Username');?></th>
		<th><?php __('First Name');?></th>
		<th><?php __('Last Name');?></th>
		<th><?php __('Email');?></th>
	  </tr>
	<?php foreach($users as $user) : ?>
			<tr>
				<td><a href="/users/view/<?php echo $user['User']['id']; ?>"><?php echo $user['User']['username']; ?></a></td>
				<td><?php echo $user['User']['first_name']; ?></td>
				<td><?php echo $user['User']['last_name']; ?></td>
				<td><a href="mailto:<?php echo $user['User']['email']; ?>"><?php echo $user['User']['email']; ?></a></td>
			</tr>
	<?php endforeach; ?>
	</table>
<?php else : ?>	
	<p><?php __('There are no users online at the moment.'); ?></p>
<?php endif; ?>