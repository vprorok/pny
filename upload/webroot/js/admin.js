$(document).ready(function(){

    $('li.headlink').hover(
        function() { $('ul', this).css('display', 'block'); },
        function() { $('ul', this).css('display', 'none'); });

	
	
    $.ajaxSetup({
        cache: false
    });

    $('#pageListTop').sortable({
        'axis': 'y',
        'items': 'tr.sortable_top',
        'opacity': 50,
        update: function(){
            $.ajax({
                url: '/admin/pages/saveorder/top',
                type: 'POST',
                data: $(this).sortable('serialize'),
                success: function(data){
                    $('#orderMessageTop').html(data).show('fast').animate({opacity: 1.0}, 2000).fadeOut('slow');
                }
            });
        }
    });

    $('#pageListBottom').sortable({
        'axis': 'y',
        'items': 'tr.sortable_bottom',
        'opacity': 50,
        update: function(){
            $.ajax({
                url: '/admin/pages/saveorder/bottom',
                type: 'POST',
                data: $(this).sortable('serialize'),
                success: function(data){
                    $('#orderMessageBottom').html(data).show('fast').animate({opacity: 1.0}, 2000).fadeOut('slow');
                }
            });
        }
    });

    // For initial load
    var fixedPrice = $('#ProductFixedPrice').val();

    if(fixedPrice == 0){
        $('#ProductFixed').removeAttr('checked');
        $('#FixedPriceBlock').hide(1);
    }else{
        $('#ProductFixed').attr('checked', true);
        $('#FixedPriceBlock').show(1);
    }

    // For change
    $('#ProductFixed').click(function(){
        if($(this).attr('checked')){
            $('#ProductFixedPrice').val(fixedPrice);
            $('#FixedPriceBlock').show(1);
        }else{
            $('#ProductFixedPrice').val(0);
            $('#FixedPriceBlock').hide(1);
        }
    });
    
    // For initial load
    var stockNumber = $('#ProductStockNumber').val();

    if(stockNumber == 0){
        $('#ProductStock').removeAttr('checked');
        $('#StockNumberBlock').hide(1);
    }else{
        $('#ProductStock').attr('checked', true);
        $('#StockNumberBlock').show(1);
    }

    // For change
    $('#ProductStock').click(function(){
        if($(this).attr('checked')){
            $('#ProductStockNumber').val(stockNumber);
            $('#StockNumberBlock').show(1);
        }else{
            $('#ProductStockNumber').val(0);
            $('#StockNumberBlock').hide(1);
        }
    });
});
