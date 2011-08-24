<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('My Addresses');?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<div id="leftcol">
				<?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
			</div><!-- -->
			<div id="rightcol">
				<?php
				$html->addCrumb('My Addresses', '/addresses');
				echo $this->element('crumb_user');
				?>
				
				<?php if(!empty($address)) : ?>
					<?php foreach($address as $name => $address) : ?>
						<h3><?php echo $name; ?> Address</h3>
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
							<p><a href="/addresses/add/<?php echo $name; ?>"><?php echo sprintf(__('Add a %s address', true), $name); ?></a></p>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>