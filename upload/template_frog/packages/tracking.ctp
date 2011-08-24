<p><a href="/accounts">Click here to view your account transactions</a></p>

<?php if(Configure::read('GoogleTracking.bidPurchase.active')) : ?>
	<!-- Google Code for Registration Conversion Page -->
	<script language="JavaScript" type="text/javascript">
	<!--
	var google_conversion_id = <?php echo Configure::read('GoogleTracking.bidPurchase.id'); ?>;
	var google_conversion_language = "<?php echo Configure::read('GoogleTracking.bidPurchase.language'); ?>";
	var google_conversion_format = "<?php echo Configure::read('GoogleTracking.bidPurchase.format'); ?>";
	var google_conversion_color = "<?php echo Configure::read('GoogleTracking.bidPurchase.color'); ?>";
	var google_conversion_label = "<?php echo Configure::read('GoogleTracking.bidPurchase.label'); ?>";
	//-->
	</script>
	<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
	<img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/<?php echo Configure::read('GoogleTracking.bidPurchase.id'); ?>/?label=<?php echo Configure::read('GoogleTracking.bidPurchase.label'); ?>&amp;guid=ON&amp;script=0"/>
	</noscript>
<?php endif; ?>