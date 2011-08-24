<div class="payment-redirect">
    <h1><?php __('iDeal - Select Your Bank');?></h1>
    <form name="bankselect" method="POST">
        <label><?php __('Bank');?></label>
        <select name="bank" onChange="document.bankselect.submit();">
        <script src="http://www.targetpay.nl/ideal/issuers-nl.js"></script>
        </select>
    </form>
</div>