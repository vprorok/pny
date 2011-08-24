<div class="span-30 prepend-top">

<fieldset class="span-30">
	<legend>Fatal Error</legend>
	
	<p>Sorry, a fatal error occurred and phpPennyAuction setup cannot recover. Error is below:</p>
	
	<?php
	if (isset($err)) {
		?>
		<div class="error"><?= $err ?></div>
		<?php
	}
	?>
	
	<p>If you need assistance, please contact 
	<a href="http://members.phppennyauction.com/" target="_blank">phpPennyAuction support</a>.
	
	
	<p><a href="setup.php">&raquo; Start installation over</a></p>
	
</fieldset>
</div>
