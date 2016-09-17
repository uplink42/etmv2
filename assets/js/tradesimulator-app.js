$(document).ready(function() {

    var base = $(".navbar").data('url');
    var url = base + "TradeRoutes/searchStations";

	$("#origin-station, #destination-station").autocomplete({
        source: url,
        minLength: 2,
        messages: {
            noResults: '',
        },
        select: function( event, ui ) {
            if($(this).hasClass('origin-station')) {
                $(".origin").val(ui.item.value);
            } else {
                $(".destination").val(ui.item.value);
            }
        }
    });


    $("#traderoute").change(function() {
        var fromStationName = $("#traderoute option:selected").text().substring(0, $("#traderoute option:selected").text().indexOf('>>'));
        var toStationName = $("#traderoute option:selected").text().substr($("#traderoute option:selected").text().indexOf(">>")+3);

        $("#origin-station").val(fromStationName);
        $("#destination-station").val(toStationName);
    });


});