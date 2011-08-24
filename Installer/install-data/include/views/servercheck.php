<script type="text/javascript">

$(document).ready(function () {
});

</script>

<?php
if ($stop_error) {
	?>
<h1>Whoops! Something's amiss...</h1>

<div class="queen"><p>Some issues have been found in your server configuration and the installation cannot continue. Please correct the following issue(s):</p></div>
	<?php
} else {
	?>
<h1>Hooray! Your server is fully compatible.</h1>	

<div class="queen"><p>Great news! Your server is compatible. Click <strong> Continue &raquo; </strong> at the bottom of the page to proceed with the installation.</p></div>

	<?php
}
?>
<fieldset class="span-30">
	<legend>Step two: Requirements</legend>
	
<?php
foreach ($check as $item=>$data) {
	if ($data[0]=='ok') {
		$img='ok';
	} elseif ($data[0]=='warn') {
		$img='warn';
	} elseif ($data[0]=='error') {
		$img='error';
	}
	
	?>
	<div class="span-12 box last <?= $img ?>">
		<img src="install-data/img/<?= $img ?>.gif" width="12px" height="12px" align="absmiddle" />
		<?= $data[1] ?>
	</div>
	<?php
}

?>

<br style="clear:both">

<div class="span-5 prepend-top">
<form method="post" action="setup.php">
<input type="hidden" value="servercheck" name="step">
<input type="submit" value="Try Again" class="fatbutton">
</form>
</div>

<div class="span-5 prepend-top last">
<form method="post" action="setup.php">
<input type="submit" value="Start Over" class="fatbutton">
</form>
</div>

<?php
if (!$stop_error) {
	?>
<div class="span-5 prepend-top last">
<form method="post" action="setup.php">
<input type="hidden" value="dbparams" name="step">
<input type="submit" value="Continue &raquo;" class="fatbutton">
</form>
	<?php
}
?>
</div>
</fieldset>