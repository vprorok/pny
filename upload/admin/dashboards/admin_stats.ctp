<?php
if ($enabled) {
	?>
	<iframe src="<?= $appConfigurations['baseUrl'] ?>/admin_stats.php" style="border:none;height:700px; width:1000px;">
	<?php
} else {
	?>
	<h1>Stats not enabled</h1>
	<p>&nbsp;</p>
	<p>Please enable stats first by setting <b>'enabled'<b> to <em>true</em> in the stats section of your config.php</p>
	
	<?php
}

?>
