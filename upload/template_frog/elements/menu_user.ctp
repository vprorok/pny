<div class="module">
	<h3 class="heading"><?php echo @$j['user_side_menu_title1']; ?></h3>
	<ul class="menu">
		<li><?php echo $html->link(__('Dash Board', true), array('controller' => 'users', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('Edit Profile', true), array('controller' => 'users', 'action' => 'edit'));?></li>
		<li><?php echo $html->link(__('Change Password', true), array('controller' => 'users', 'action' => 'changepassword'));?></li>
		<li><?php echo $html->link(__('My Addresses', true), array('controller' => 'addresses', 'action' => 'index'));?></li>
	</ul>
</div>

<div class="module">
	<h3 class="heading"><?php echo @$j['user_side_menu_title2']; ?></h3>
	<ul class="menu2">
		<li><?php echo $html->link(__('Purchase Bids', true), array('controller' => 'packages', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('My Watchlist', true), array('controller' => 'watchlists', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('My Bid Butlers', true), array('controller' => 'bidbutlers', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('Won Auctions', true), array('controller' => 'auctions', 'action' => 'won'));?></li>

	</ul>
	<ul class="menu2">
		<li><?php echo $html->link(__('My Bids', true), array('controller' => 'bids', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('My Account', true), array('controller' => 'accounts', 'action' => 'index'));?></li>
		<?php if(!empty($appConfigurations['credits']['active'])) : ?>
			<li><?php echo $html->link(__('My Credits', true), array('controller' => 'credits', 'action' => 'index'));?></li>
		<?php endif; ?>
		<?php if(!empty($appConfigurations['limits']['active'])) : ?>
			<li><?php echo $html->link(__('My Limits', true), array('controller' => 'limits', 'action' => 'auctions'));?></li>
		<?php endif; ?>
	</ul>
	<ul class="menu">
		<li><?php echo $html->link(__('Referrals', true), array('controller' => 'referrals', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('Invite My Friends', true), array('controller' => 'invites', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('Cancel My Account', true), array('controller' => 'users', 'action' => 'cancel'));?></li>
	</ul>
</div>