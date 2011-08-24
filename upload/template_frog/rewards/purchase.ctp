<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Purchase');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div>
				<p><?php echo sprintf(__('You are about to purchase %s. It costs you %s points.', true), '<strong>'.$reward['Reward']['title'].'</strong>', '<strong>'.$reward['Reward']['points'].'</strong>');?></p>
			
				<p><?php __('Please confirm your address details below and ensure the details are correct before purchasing this reward.');?></p>
			
				<?php if(!empty($address)) : ?>
					<?php foreach($address as $name => $address) : ?>
						<h2><?php echo $name; ?> Address</h2>
						<?php if(!empty($address)) : ?>
							<table class="results" cellpadding="0" cellspacing="0">
							<tr>
								<th><?php __('Name');?></th>
								<th><?php __('Address');?></th>
								<th><?php __('Suburb / Town');?></th>
								<th><?php __('City / State / County');?></th>
								<th><?php __('Postcode');?></th>
								<th><?php __('Country');?></th>
								<th><?php __('Phone Number');?></th>
								<th class="actions"><?php __('Options');?></th>
							</tr>
			
							<tr>
								<td><?php echo $address['Address']['name']; ?></td>
								<td><?php echo $address['Address']['address_1']; ?><?php if(!empty($address['Address']['address_2'])) : ?>, <?php echo $address['Address']['address_2']; ?><?php endif; ?></td>
								<td><?php if(!empty($address['Address']['suburb'])) : ?><?php echo $address['Address']['suburb']; ?><?php else: ?>n/a<?php endif; ?></td>
								<td><?php echo $address['Address']['city']; ?></td>
								<td><?php echo $address['Address']['postcode']; ?></td>
								<td><?php echo $address['Country']['name']; ?></td>
								<td><?php if(!empty($address['Address']['phone'])) : ?><?php echo $address['Address']['phone']; ?><?php else: ?>n/a<?php endif; ?></td>
								<td><a href="/addresses/edit/<?php echo $name; ?>">Edit</a></td>
							</tr>
							</table>
						<?php else: ?>
							<p><a href="/addresses/add/<?php echo $name; ?>">Add a <?php echo $name; ?> Address</a></p>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			
				<?php if(!empty($addressRequired)) : ?>
					<h2><?php __('Missing Address information');?></h2>
					<p><?php __('Before purchasing, please <a href="/addresses">click here to update your address information</a>.');?></p>
				<?php else : ?>
					<p><?php __('If you feel there is an error, or if you are unsure about anything please <a href="/contact">contact us before purchasing the reward.</a>');?></p>
					<p>
						<?php echo $form->create('Reward', array('action' => 'purchase/'.$reward['Reward']['id']));?>
						<?php echo $form->hidden('id', array('value' => $reward['Reward']['id']));?>
						<?php echo $form->end(__('Purchase Now', true));?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>