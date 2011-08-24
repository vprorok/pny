<?php
$html->addCrumb(__('Dashboard', true), '/admin');
echo $this->element('admin/crumb');
?>

<?php if (isset($_GET['is_home'])): ?>
	<div class="homepage_admin_box">
		<h1><img src="/admin/img/notice.png" class="absmiddle" alt="" />&nbsp; <?php echo urldecode(base64_decode($_GET['is_home']));?></h1>
		<p><a href='?license_reset=y'>Click here</a> to try again or enter a new license key in /config/config.php or contact support if you feel this is in error.</p>
	</div>
<?PHP else: ?>
	<?php include 'versioncheck.php'; //dont remove this ... yet ?>
	<br />

	<blockquote><p><strong>Welcome to the Admin Panel dashboard.</strong>. From here you can administer every aspect of your website. You may wish to start by <?php echo sprintf(__('%s an auction', true), $html->link(__('creating', true), array('controller' => 'auctions', 'action'=>'index')));?> or <?php echo sprintf(__('%s.', true), $html->link(__('checking your site settings', true), array('controller' => 'settings', 'action'=>'index')));?></p></blockquote>
    <div style="clear:both;"> </div>

	<?php if (isset($upgrade_available)) { ?>
	<blockquote>
	<p><strong>Upgraded version is available!</strong> <?= $html->link('Get the latest version', 'https://members.phppennyauction.com/') ?></p>
	</blockquote>
	<?php	} ?>


	<div class="homepage_admin_box">
			<h1><img src="/admin/img/onlineuserslarge.gif" class="absmiddle" alt="" />&nbsp; <?php __('Online Users');?></h1>
			<p>
				<?php echo sprintf(__('There are %d online user(s) at the moment.', true), number_format($onlineUsers));?> (<?php echo $html->link(__('View who\'s online', true), array('controller' => 'users', 'action'=>'online')); ?>)
			</p>


			<h2><?php __('Users');?></h2>
			<p><?php echo sprintf(__('%s the users who are registered on the site.', true), $html->link(__('Manage', true), array('controller' => 'users', 'action'=>'index')));?></p>


			<h2><?php __('Auctions');?></h2>
			<p><?php echo sprintf(__('%s and control your auctions.', true), $html->link(__('Manage', true), array('controller' => 'auctions', 'action'=>'index')));?></p>
	<br /><br /><?php 

	if (Configure::read('Settings.autobidders')) { ?>
		<p><?php echo __('Warning: Autobidders are <strong>enabled</strong>.'); ?>
			<?php echo $html->link(__('Change', true), '/admin/settings/edit/autobidders'); ?>
			</p>	
	<?php }else{ ?>
		<p><?php /*__('Autobidders are <strong>disabled</strong>'); */?></p>
	<?php }?>
	<?php
	if (isset($warn_version)) {
		?>
		<p><?php echo __('Warning: Your database version is incorrect. Please upgrade your database or contact support for assistance.') ?></p>
		<?php
	}
	?>
	</div>
	<div style="clear:both;"> </div>


	<div class="homepage_admin_box">
	<h1><img src="/admin/img/shoppingbasketlarge.gif" alt="" class="absmiddle" />&nbsp; <?php __('Bid Purchases');?></h1>



			<p><?php __('Today:');?> <?php echo $number->currency($dailyIncome, $appConfigurations['currency']); ?></p>
			<p><?php __('Yesterday:');?> <?php echo $number->currency($yesterdayIncome, $appConfigurations['currency']); ?></p>


			<p><?php __('Last 7 days:');?> <?php echo $number->currency($weeklyIncome, $appConfigurations['currency']); ?></p>
			<p><?php __('Previous 7 days:');?> <?php echo $number->currency($lastweekIncome, $appConfigurations['currency']); ?></p>


			<p><?php __('This Month:');?> <?php echo $number->currency($monthlyIncome, $appConfigurations['currency']); ?></p><br />
	<br />
	<br />
	</div>
	<div style="clear:both;"> </div>



	<div class="homepage_admin_box">
	<h1><img src="/admin/img/latestbidslarge.gif" class="absmiddle" alt="" />&nbsp; Latest Bids</h1>
	<?php if(!empty($bids)):?>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<th><?php __('Username');?></th>
			<th><?php __('Auction');?></th>
			<th><?php __('Bid Placed');?></th>
		</tr>
		<?php
		$i = 0;
		foreach ($bids as $bid):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
			<tr<?php echo $class;?>>
				<td>
					<?php echo $html->link($bid['User']['username'], array('controller' => 'users', 'action' => 'view', $bid['User']['id'])); ?>
				</td>
				<td>
					<?php echo $html->link($bid['Auction']['Product']['title'], array('admin' => false, 'controller' => 'auctions', 'action' => 'view', $bid['Auction']['id']), array('target' => '_blank')); ?>
				</td>
				<td>
					<?php echo $time->niceShort($bid['Bid']['created']); ?>
				</td>
			</tr>
		<?php
		endforeach;
		?>
		</table>
	<div style="clear: both;">	<?php echo $html->link(__('View all bids', true), array('controller' => 'bids', 'action' => 'index')); ?>
	<?php else:?>
		<p><?php __('There have not been any bids placed on the site yet.');?></p></div>
	<?php endif;?>
	</div>
<?PHP endif; ?>
<div style="clear:both;"> </div>
