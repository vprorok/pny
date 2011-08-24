<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpPennyAuction Installer (v2.4)</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>
<body>
	<div class="container">
		<div class="span-24 last colborder box" style="background-color:#395584; text-align:center; padding:10px">
        <p style="font-size:20px; color:White">phpPennyAuction</p>
		</div>
		
		<div class="span-24">
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

<h1>phpPennyAuction is not yet completely installed.</h1>

<p>Please check the following:</p>

<ol>
	<li>That both of your .htaccess files are present and correct.</li>
	<li>That your server has mod_rewrite installed</li>
	<li>That your server is configured with <b>AllowOverride</b> set to <b>All</b></li>
</ol>

<p>If you need further assistance, please contact <a href="http://members.phppennyauction.com" target="_blank">phpPennyAuction Support</a>.

		</div>
		
		<div class="last span-24 quiet box prepend-top">
		All contents &copy; 2010 Scriptmatix Ltd. <a href="http://www.phppennyauction.com" target="_blank">phpPennyAuction</a>

		</div>
	</div>
</body>
</html>
