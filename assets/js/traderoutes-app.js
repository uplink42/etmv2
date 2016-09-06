$(document).ready(function() {

    $(".origin-station").focus().select();

    var base = $(".navbar").data('url');
    var url = base + "TradeRoutes/searchStations";
    var id = $(".navbar").data('id');

    $(".origin-station, .destination-station").autocomplete({
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

    //put base url and char id on header
    $(".submit-traderoute").on('click', function (e){
        e.preventDefault();
        var origin_val = $(".origin-station").val();
        var dest_val = $(".destination-station").val();
        $(".origin").val(origin_val);
        $(".destination").val(dest_val);

        url = base + "TradeRoutes/submitRoute/"+id;
        console.log(url);
        var data = $(".form-horizontal").serialize();

        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: function(result) {
                if(result.notice == "success") {
                    toastr["success"](result.message);   
                } else {
                    toastr["error"](result.message);
                }
            }
        });

    }); 


});