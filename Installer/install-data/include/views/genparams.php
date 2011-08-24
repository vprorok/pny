<script type="text/javascript">

$(document).ready(function () {
	$('input[type=submit]').click(function () {
		var timeout=function () {
			$('input[type=submit]').removeAttr('disabled', '').slideDown('medium');
			$('img#load_img').hide();
			
			alert("The installation failed to proceed. Please try again. If this message persists, please contact phpPennyAuction support");
		}
			
		$(this).attr('disabled', 'true').slideUp('medium');
		$('img#load_img').slideDown('medium');
		setTimeout(timeout, 30000);
	});
});

</script>


<div class="span-30 prepend-top">

<form method="post" action="setup.php">
<fieldset class="span-30">
	<legend>General Settings</legend>
	
	<?php
	if (isset($err)) {
		?>
		<div class="error"><?= $err ?></div>
		<?php
	}
	?>
	<div class="queen"><p>Your website's main settings go here. You can change all settings on this page later, however it's important to make sure the <strong>Site URL</strong> and <strong>Site domain name</strong> fields are correct. </p></div>
	<label for="config_site_name">Your Site's Name</label><br />
	<input type="text" id="config_site_name" name="config[site_name]" value="<?= $config['site_name'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Site Title</span><span class="HelpToolTip_Contents" style="display:none;">The title of your website goes here, but you can easily change this later.</span></span><br />

	<label for="config_site_url">Site URL</label><br />
	http:// <input type="text" id="config_site_url" name="config[site_url]" value="<?= $config['site_url'] ?>"><br />
	
	<label for="config_site_url">Site domain name (no www. or http://)</label><br />
	http:// <input type="text" id="config_site_domain" name="config[site_domain]" value="<?= $config['site_domain'] ?>"><br />

	<label for="config_site_encoding">Text encoding (choose UTF-8 if unsure)</label><br />
	<select name="config[site_encoding]" id="config_site_encoding" style="width:460px">
	<?php 
	
		$encodings=getEncodings();
		foreach ($encodings as $enc=>$label) {
			echo "<option value=\"$enc\"".($zone==$config['time_zone'] ? ' selected' : '').">$label</option>\n";
		}
	?>
	</select><br />
	
	<label for="config_site_currency">Currency</label><br />
	<select name="config[site_currency]" id="config_site_currency" style="width:460px">
	<?php 
	
		$encodings=getCurrencies();
		foreach ($encodings as $curr=>$label) {
			echo "<option value=\"$curr\"".($zone==$config['site_currency'] ? ' selected' : '').">$label</option>\n";
		}
	?>
	</select><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Currency type</span><span class="HelpToolTip_Contents" style="display:none;">Choose which currency you want to use. E.g., 'USD' = United States Dollars / $. If unsure, search on Google for 'php currency'.</span></span><br />
	
	
	<label for="config_time_zone">Time Zone</label><br />
	<select name="config[time_zone]" id="config_time_zone" style="width:460px">
	<?php 
	
		$zones=getTimezones();
		foreach ($zones as $zone=>$label) {
			echo "<option value=\"$zone\"".($zone==$config['time_zone'] ? ' selected' : '').">$label</option>\n";
		}
	?>
	</select><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Choose your time zone</span><span class="HelpToolTip_Contents" style="display:none;">Choose the desired time zone and the software will remember this. Note, this can easily be changed later.</span></span><br />
	
	<label for="config_license_number">License Key (copy &amp; paste from <a href="https://members.phppennyauction.com" target="_blank">here</a>)</label><br />
	<input type="text" id="config_license_number" name="config[license_number]" value="<?= $config['license_number'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Enter your 21-digit License Key</span><span class="HelpToolTip_Contents" style="display:none;">Enter your phpPennyAuction License Key here, which is in the License Center. Starts with 'phpPa-', e.g: <em>phpPA-38XsnCjd3rLYuw89</em>. If unsure, please use the Support Center.</span></span><br />
	
</fieldset>

<fieldset>
	<legend>Administrator Information</legend>
	<label for="config_site_name">Admin Email Address:</label><br />
	<input type="text" id="config_admin_email" name="config[admin_email]" value="<?= $config['admin_email'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Admin Email Address</span><span class="HelpToolTip_Contents" style="display:none;">Pick a default admin email address for the website, e.g., <em>admin@yourwebsite.com</em>. This can be changed later. </span></span><br />
	
	<label for="config_site_name">Choose an Admin Username:</label><br />
	<input type="text" id="config_admin_login" name="config[admin_login]" value="<?= $config['admin_login'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Choose an Admin Username</span><span class="HelpToolTip_Contents" style="display:none;">Choose an admin username here. This will by default, be your first administrator account on your website.</span></span><br />
	
	<label for="config_site_name">Choose an Admin Password:</label><br />
	<input type="password" id="config_admin_password" name="config[admin_password]" value="<?= $config['admin_password'] ?>"><br />
	
	<label for="config_site_name">Confirm Admin Password:</label><br />
	<input type="password" id="config_admin_password2" name="config[admin_password2]" value="<?= $config['admin_password2'] ?>"><br />
	



</fieldset>
</div>

<div class="notice"><strong>Please make sure that the 'Site URL' and 'Site domain name' fields above are correct</strong> before submitting this form!</div>

<?php
if (!$stop_error) {
	?>
<div class="span-5 prepend-top last">

<input type="hidden" value="install1" name="step">
<input type="submit" value="Continue &raquo;" class="fatbutton">
<img src="install-data/img/working.gif" id="load_img" style="display:none; margin-left:100px">
</form>
	<?php
}
?>
</div>


<p>&nbsp;</p>




