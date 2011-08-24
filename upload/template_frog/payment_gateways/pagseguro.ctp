<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
	<form target="pagseguro" name="frmPagseguro" action="https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx" method="post">
		<?php echo $data; ?>
		<input type="submit" value="<?php __('Click here if this page appears for more than 5 seconds');?>"/>
	</form>	 
	
   <script type="text/javascript">
        $(document).ready(function(){
            document.frmPagseguro.submit();
        });
	</script>
</div>