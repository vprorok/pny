<script type="text/javascript">

$(document).ready(function () {
});

</script>



<div class="span-30 prepend-top">

<form method="post" action="setup.php">
<fieldset class="span-30">
	<legend> Step three: Database Connection </legend>
	
	<?php
	if (isset($err)) {
		?>
		<div class="error"><?= $err ?>. <strong>Check for whitespaces!</strong></div>
		<?php
	}
	?>
    <div class="queen"><p>Please create a new database in your hosting account Control Panel, &amp; fill out all the following fields. If you are not sure what information goes here, please contact your host. This table  will be automatically populated for you!</p></div>
	
	<label for="config_db_host" class="span-4">Database host</label><br />
	<input type="text" id="config_db_host" name="config[db_host]" value="<?= $config['db_host'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Enter your MySQL hostname</span><span class="HelpToolTip_Contents" style="display:none;">Enter your hostname here. In most cases, this will be 'localhost' - however on some hosts such as Dreamhost &amp; GoDaddy, it may be the URL to your MySQL server. If unsure, consult with your hosting provider.</span></span><br />

	<label for="config_db_user" class="span-4">Database user</label><br />
	<input type="text" id="config_db_host" name="config[db_user]" value="<?= $config['db_user'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Enter your MySQL username</span><span class="HelpToolTip_Contents" style="display:none;">Your MySQL username goes here, i.e., 'phppa_username'. </span></span><br />
	
	<label for="config_db_pass" class="span-4">Database password</label><br />
	<input type="password" id="config_db_host" name="config[db_pass]" value="<?= $config['db_pass'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Enter your MySQL password</span><span class="HelpToolTip_Contents" style="display:none;">Enter your MySQL database password here.</span></span><br />	
	
	<label for="config_db_name" class="span-4">Database name</label><br />
	<input type="text" id="config_db_host" name="config[db_name]" value="<?= $config['db_name'] ?>"><span class="HelpToolTip"> ? <span class="HelpToolTip_Title" style="display:none;">Enter MySQL database name</span><span class="HelpToolTip_Contents" style="display:none;">Obtain your database name and place it here, i.e., 'phppa_table' </span></span><br />
    

</fieldset>

</div>






<?php
if (!$stop_error) {
	?>
<div class="span-5 prepend-top last">

<input type="hidden" value="genparams" name="step">
<input type="submit" value="Continue &raquo;" class="fatbutton">
</form>
	<?php
}
?>
</div>
