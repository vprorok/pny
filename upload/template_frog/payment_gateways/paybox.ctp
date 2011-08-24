<div class="payment-redirect">
    <h1><?php __('Please wait while we transfering you to the payment gateway.');?></h1>
    <form name="frmPaybox" method="post" action="<?php echo Configure::read('PaymentGateways.Paybox.URL');?>">
		<input type="hidden" name="PBX_PAYBOX" value="https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi" />
		<input type="hidden" name="PBX_BACKUP1" value="https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi" />
		<input type="hidden" name="PBX_BACKUP2" value="https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi" />
		<input type="hidden" name="PBX_MODE" value="<?php echo $data['PBX_MODE'];?>" />
		<input type="hidden" name="PBX_SITE" value="<?php echo $data['PBX_SITE'];?>" />
		<input type="hidden" name="PBX_RANG" value="<?php echo $data['PBX_RANG'];?>" />
		<input type="hidden" name="PBX_IDENTIFIANT" value="<?php echo $data['PBX_IDENTIFIANT'];?>" />
		<input type="hidden" name="PBX_DEVISE" value="<?php echo $data['PBX_DEVISE'];?>" />
		<input type="hidden" name="PBX_PORTEUR" value="<?php echo $data['PBX_PORTEUR'];?>" />
		<input type="hidden" name="PBX_TOTAL" value="<?php echo $data['PBX_TOTAL'];?>" />
		<input type="hidden" name="PBX_CMD" value="<?php echo $data['PBX_CMD'];?>" />
		<input type="hidden" name="PBX_REPONDRE_A" value="<?=$data['PBX_REPONDRE_A'];?>"/>
		<input type="hidden" name="PBX_RETOUR" value="<?php echo $data['PBX_RETOUR'];?>" />
		<input type="hidden" name="PBX_EFFECTUE" value="<?php echo $data['PBX_EFFECTUE'];?>" />
		<input type="hidden" name="PBX_REFUSE" value="<?php echo $data['PBX_REFUSE'];?>" />
		<input type="hidden" name="PBX_ANNULE" value="<?php echo $data['PBX_ANNULE'];?>" />
        <input type="submit" value="<?php __('Click here if this page appears for more than 5 seconds');?>"/>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){
            //document.frmPaybox.submit();
        });
    </script>
</div>