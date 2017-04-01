"use strict";
$(document).ready(function() {
    var url = base + "Main/headerData/" + charID + "/" + aggr;
    
    if (typeof(charID) !== "undefined") {
        $.ajax({
            dataType: "json",
            url: url,
            global: false,
            success: function(result) {
                $(".header-balance").html(result.balance);
                $(".header-networth").html(result.networth);
                $(".header-orders").html(result.total_sell);
                $(".header-escrow").html(result.escrow);
            }
        });
    }

    if (aggr == 1) {
        $(".profil-link a").css('color', '#cc0044');
    }
    
    // highlight the correct option
    var option = $(".navbar-brand").data('selected');
    $("li" + "." + option).addClass('active');
});