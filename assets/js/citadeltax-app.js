$(document).ready(function() {

    var base = $(".navbar").data('url');
    var url_autocomplete = base + "CitadelTax/searchCitadels/";

    $("#citadel").autocomplete({
        source: url_autocomplete,
        minLength: 2,
        messages: {
            noResults: '',
        },
        select: function( event, ui ) {
            $("#citadel").val(ui.item.value);
        }
    });

});