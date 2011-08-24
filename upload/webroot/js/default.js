$(document).ready(function(){

    // Variable to hold auction data
    var auctions = '';
    var auctionObjects = new Array();

    // Collecting auction data, the layer id and auction id
    $('.auction-item').each(function(){
        var auctionId    = $(this).attr('id');
        var auctionTitle = $(this).attr('title');

        if($('#' + auctionId + ' .countdown').length){
        	
            // collect the id for post data
            auctions = auctions + auctionId + '=' + auctionTitle + '&';

            // collect the object
            auctionObjects[auctionId]                           = $('#' + auctionId);
            auctionObjects[auctionId]['flash-elements']         = $('#' + auctionId + ' .countdown, #' + auctionId + ' .bid-price, #' + auctionId + ' .bid-bidder, #' + auctionId+ ' .bid-savings-price, #' + auctionId + ' .bid-savings-percentage, #' + auctionId + ' .closes-on');
            auctionObjects[auctionId]['countdown']              = $('#' + auctionId + ' .countdown');
            auctionObjects[auctionId]['closes-on']              = $('#' + auctionId + ' .closes-on');
            auctionObjects[auctionId]['bid-bidder']             = $('#' + auctionId + ' .bid-bidder');
            auctionObjects[auctionId]['bid-button']             = $('#' + auctionId + ' .bid-button');
            auctionObjects[auctionId]['bid-button-a']           = $('#' + auctionId + ' .bid-button a');
            auctionObjects[auctionId]['bid-button-p']           = $('#' + auctionId + ' .bid-button p');
            auctionObjects[auctionId]['bid-price']              = $('#' + auctionId + ' .bid-price');
            auctionObjects[auctionId]['bid-price2']             = $('#' + auctionId + ' .bid-price2');
            auctionObjects[auctionId]['buy-it-now']             = $('#' + auctionId + ' .price_bin');
            auctionObjects[auctionId]['bid-price-fixed']        = $('#' + auctionId + ' .bid-price-fixed');
            auctionObjects[auctionId]['bid-loading']            = $('#' + auctionId + ' .bid-loading');
            auctionObjects[auctionId]['bid-message']            = $('#' + auctionId + ' .bid-message');
            auctionObjects[auctionId]['bid-flash']              = $('#' + auctionId + ' .bid-flash');
            auctionObjects[auctionId]['bid-savings-price']      = $('#' + auctionId + ' .bid-savings-price');
            auctionObjects[auctionId]['bid-savings-percentage'] = $('#' + auctionId + ' .bid-savings-percentage');
            auctionObjects[auctionId]['bid-bookbidbutler']      = $('#' + auctionId + ' .bid-bookbidbutler');
            auctionObjects[auctionId]['bid-increment']      = $('#' + auctionId + ' .bid-increment');
            auctionObjects[auctionId]['price-increment']      = $('#' + auctionId + ' .price-increment');

            auctionObjects[auctionId]['bid-histories']          = $('#bidHistoryTable' + auctionTitle);
            auctionObjects[auctionId]['bid-histories-p']        = $('#bidHistoryTable' + auctionTitle + ' p');
            auctionObjects[auctionId]['bid-histories-tbody']    = $('#bidHistoryTable' + auctionTitle + ' tbody');
        }
    });

    // additional object
    var bidOfficialTime        = $('.bid-official-time');
    var bidBalance             = $('.bid-balance');
    var price                  = '';
    var priceFixed             = '';
    var getstatus_url_time;
    var getstatus_url;

    if($('.bid-histories').length){
        getstatus_url = '/getstatus.php?histories=yes&ms=';
    }else{
        getstatus_url = '/getstatus.php?ms=';
    }

    function convertToNumber(sourceString){
        return sourceString.replace(/&#[0-9]{1,};/gi, "")
                            .replace(/&[a-z]{1,};/gi, "")
                            .replace(/[a-zA-Z]+/gi, "")
                            .replace(/[^0-9\,\.]/gi, "");
    }

    // Do the loop when auction available only
    if(auctions){
        setInterval(function(){
            getstatus_url_time = getstatus_url + new Date().getTime();
            $.ajax({
                url: getstatus_url_time,
                dataType: 'json',
                type: 'POST',
                data: auctions,
                success: function(data){
                    if(data[0]){
                        if(data[0].Auction.serverTimeString){
                            if(bidOfficialTime.html()){
                                bidOfficialTime.html(data[0].Auction.serverTimeString);
                            }
                        }

                        if(data[0].Balance){
                            if(bidBalance.html()){
                                bidBalance.html(data[0].Balance.balance);
                            }
                        }
                    }

                    $.each(data, function(i, item){
                        if (typeof(auctionObjects[item.Auction.element])=='undefined') return true; //continue
			
                        if(auctionObjects[item.Auction.element]['bid-price-fixed'].html()){

                            if(auctionObjects[item.Auction.element]['bid-price-fixed'].length > 1){
                                auctionObjects[item.Auction.element]['bid-price-fixed'].each(function(){
                                    price = $(this).html();
                                });
                            }else{
                                price = auctionObjects[item.Auction.element]['bid-price-fixed'].html();
                            }

                        }else{

                            if(auctionObjects[item.Auction.element]['bid-price'].length > 1){
                                auctionObjects[item.Auction.element]['bid-price'].each(function(){
                                    price = $(this).html();
                                });
                            }else{
                                price = auctionObjects[item.Auction.element]['bid-price'].html();
                            }

                        }

                        if (item.Auction.price_increment) {
                            auctionObjects[item.Auction.element]['price-increment'].html(item.Auction.price_increment);
                        }
                        
                        price = convertToNumber(price);

                        if(auctionObjects[item.Auction.element]['bid-bidder'].html() != item.LastBid.username){
                            auctionObjects[item.Auction.element]['bid-bidder'].html(item.LastBid.username);
                        }

                        auctionObjects[item.Auction.element]['buy-it-now'].html(item.Auction.buy_it_now);
                        
                        if(price != convertToNumber(item.Auction.price)){
                            auctionObjects[item.Auction.element]['bid-price'].html(item.Auction.price);
                            auctionObjects[item.Auction.element]['bid-price2'].html(item.Auction.price);
                            auctionObjects[item.Auction.element]['bid-price-fixed'].html(item.Auction.price);

                            if(auctionObjects[item.Auction.element]['bid-flash'] && item.Message){
                                auctionObjects[item.Auction.element]['bid-flash'].html(item.Message.message).show(1).animate({opacity: 1.0}, 2000).hide(1);
                            }

                            if(auctionObjects[item.Auction.element]['bid-histories'].length){
                                if(auctionObjects[item.Auction.element]['bid-histories-p'].html()){
                                    auctionObjects[item.Auction.element]['bid-histories-p'].remove();
                                }

                                auctionObjects[item.Auction.element]['bid-histories-tbody'].empty();


                                $.each(item.Histories, function(n, tRow){
                                    var row = '<tr><td>' + tRow.Bid.created + '</td><td>' + tRow.User.username + '</td><td>' + tRow.Bid.description + '</td></tr>';

                                    auctionObjects[item.Auction.element]['bid-histories-tbody'].append(row);
                                });

                                auctionObjects[item.Auction.element]['closes-on'].html(item.Auction.closes_on);
                                auctionObjects[item.Auction.element]['bid-savings-percentage'].html(item.Auction.savings.percentage);
                                auctionObjects[item.Auction.element]['bid-savings-price'].html(item.Auction.savings.price);
                            }

                            auctionObjects[item.Auction.element]['flash-elements'].effect("highlight", {}, 1500);
                        }

                        if(item.Auction.peak_only == 1 && item.Auction.isPeakNow == 0){
                            auctionObjects[item.Auction.element]['countdown'].html('Paused');

                            auctionObjects[item.Auction.element]['bid-button-a'].hide();
                            if(auctionObjects[item.Auction.element]['bid-button-p'].html() == ''){
                                auctionObjects[item.Auction.element]['bid-button'].append('<p>Peak Only Auction</p>');
                            }
                        }else{
                            if(item.Auction.end_time - item.Auction.serverTimestamp > 0){
                                auctionObjects[item.Auction.element]['countdown'].html(item.Auction.end_time_string);

                                if(item.Auction.time_left <= 10){
                                    auctionObjects[item.Auction.element]['countdown'].css('color', '#ff0000');
                                }else{
                                    auctionObjects[item.Auction.element]['countdown'].removeAttr('style');
                                }
                            }

                            if(auctionObjects[item.Auction.element]['bid-button-p'].html()){
                                auctionObjects[item.Auction.element]['bid-button-a'].show();
                                auctionObjects[item.Auction.element]['bid-button-p'].remove();
                            }
                        }

                        if(item.Auction.time_left < 1 && item.Auction.closed == 1){
                            auctionObjects[item.Auction.element]['countdown'].html('Ended');
                            auctionObjects[item.Auction.element]['bid-button'].hide();
                            auctionObjects[item.Auction.element]['bid-bookbidbutler'].hide();
                        }
                    });
                },

                error: function(XMLHttpRequest, textStatus, errorThrown){
                    // nothing implement here
                    // have an idea?
                }
            });
        }, 1000);
    }else{
        if(bidOfficialTime.length){
            setInterval(function(){
                var gettime = '/gettime.php?' + new Date().getTime();
                $.ajax({
                    url: gettime,
                    success: function(data){
                        bidOfficialTime.html(data);
                    }
                });
            }, 1000);
        }
    }

    // Function for bidding
    $('.bid-button-link').click(function(){
        var auctionElement = 'auction_' + $(this).attr('title');

        auctionObjects[auctionElement]['bid-button'].hide(1);
        auctionObjects[auctionElement]['bid-loading'].show(1);

        $.ajax({
            url: $(this).attr('href') + '&ms=' + new Date().getTime(),
            dataType: 'json',
            success: function(data){
                auctionObjects[auctionElement]['bid-message'].html(data.Auction.message)
                                                             .show(1)
                                                             .animate({opacity: 1.0}, 2000)
                                                             .hide(1);

                auctionObjects[auctionElement]['bid-button'].show(1);
                auctionObjects[auctionElement]['bid-loading'].hide(1);
            }
        });

        return false;
    });

    // Function to check limit and change the icon whenever it's changed
    // Run only when bid icon available
    if($('.bid-limit-icon').length){
        setInterval(function(){
            var count = $('.bid-limit-icon').length
            if(count > 0){
                $.ajax({
                    url: '/limits/getlimitsstatus/?ms=' + new Date().getTime(),
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $('.bid-limit-icon').each(function(i){
                                if(data[i].image){
                                    $(this).attr('src', '/img/'+data[i].image);
                                }
                            });
                        }
                    }
                });
            }
        }, 30000);
    }

    if($('.productImageThumb').length){
        $('.productImageThumb').click(function(){
            $('.productImageMax').fadeOut('fast').attr('src', $(this).attr('href')).fadeIn('fast');
            return false;
        });
    }

    if($('#CategoryId').length){
        $('#CategoryId').change(function(){
            document.location = '/categories/view/' + $('#CategoryId option:selected').attr('value');
        });
    }

    if($('#myselectbox').length){
        $('#myselectbox').change(function(){
            document.location = '/categories/view/' + $('#myselectbox option:selected').attr('value');
        });
    }
});