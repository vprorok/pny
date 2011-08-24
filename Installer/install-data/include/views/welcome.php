<script type="text/javascript">

$(document).ready(function () {
	$('input#install_radio1').click(function () {
		$('input#custom_dir').attr('readonly','readonly')
	});
	$('input#install_radio2').click(function () {
		$('input#custom_dir').removeAttr('readonly').focus();
	});
		
	$('input#custom_dir').attr('readonly','readonly').click(function () {
		$(this).removeAttr('readonly');
		$('input#install_radio2').attr('checked', 'checked');
	});
});

</script>

<h1>Thank you for purchasing phpPennyAuction - the fully-featured penny auction software solution. </h1>

<div class="queen"><p>This automated installation program will quickly guide you through the setup of your new software. If you need help or aren't sure about something, please contact phpPennyAuction Support for assistance by logging in to the <a href="https://members.phppennyauction.com" target="_blank">Support Center</a> and opening a ticket.</p></div>

<fieldset class="span-30">
	<legend>Step one: Choose location</legend>
	
<p>Where would you like to install to?</p>

<form method="post" action="setup.php">
<input type="hidden" name="step" value="servercheck">
<input type="radio" name="install_radio" id="install_radio1" value="1" checked="checked"> <label for="install_radio1" selected>Current directory: <?= $_SERVER['DOCUMENT_ROOT'] ?></label><br>

<input type="radio" name="install_radio" id="install_radio2" value="2"> <label for="install_radio2">Custom directory:</label> <input type="text" id="custom_dir" name="custom_dir" value="<?= $_SERVER['DOCUMENT_ROOT'] ?>"><br>

<br>
<div class="span-5 prepend-top last">
<input type="submit" value="Continue &raquo;" class="fatbutton"></div>
</form>
</fieldset>
<div style="font-size:9px;">Please note, we do not have any resellers. If you did not purchase this software through the phpPennyAuction website at http://www.phppennyauction.com, this is not a legitimate copy of the software - please <a href="http://www.phppennyauction.com/contact/">report software piracy to us</a>.</div>
