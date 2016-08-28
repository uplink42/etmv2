$(document).ready(function() {
    var charid = $(".profil-link").data('character');
    var domain = $(".profil-link").data('url');
    var url = domain + "Main/headerData/" + charid;
    console.log(url);
    //sends an ajax request to fill the top header data
    $.ajax({
        dataType: "json",
        url: url,
        success: function(result) {
            $(".header-balance").html(result.balance);
            $(".header-networth").html(result.networth);
            $(".header-orders").html(result.total_sell);
            $(".header-escrow").html(result.escrow);
        }
    });
    //highlight the correct option
    var option = $(".navbar-brand").data('selected');
    $("li" + "." + option).addClass('active');
});