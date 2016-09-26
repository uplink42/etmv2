$(document).ready(function() {

    var base = $(".navbar").data('url');
    var url_autocomplete = base + "CitadelTax/searchCitadels/";
    var url_req = base + "CitadelTax/addTax/";

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

    $(".submit-tax").on('click', function(e) {
        e.preventDefault();
        var tax = $("#tax").val();
        var taxregex =  /^(0(\.\d+)?|1(\.0+)?)$/;
        
        if(taxregex.test(tax)) {
            var data = $(".add-tax").serialize();
            console.log("valid");
        
            $.ajax({
                dataType: "json",
                url: url_req,
                data: data,
                type: "POST",
                success: function(result) {
                    console.log(result);
                    toastr[result.notice](result.message);
                    updateTable();
                }
            });
            
        } else {
            toastr["error"]("Please fill in the form correctly");
        }
    });

    function updateTable() {

    }

});