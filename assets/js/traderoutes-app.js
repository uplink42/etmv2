$(function() {
    $(".origin-station").focus().select();
    let url = base + "Traderoutes/searchStations";

    // list routes
    function list() {
        var listurl = base + "Traderoutes/listTradeRoutes/" + charID; 
        $.ajax({
            dataType: "json",
            url: listurl,
            global: false,
            success: function(result) {
                $("table tbody tr").empty();
                if (result.length == 0) {
                    var $row = "<tr><td colspan='3' class='text-center'>No trade routes present. Create one at the left</td></tr>";
                    $("table").prepend($row);
                } else {
                    $.each(result, function(k, v) {
                        var iddel = result[k].id;
                        var $row = "<tr><td>" + result[k].s1 + "</td><td>" + result[k].s2 + 
                            "</td><td><button class='btn btn-danger btn-delete' data-iddel=" + iddel + ">Delete</button></tr></tr>";
                        $("table").prepend($row);
                    });
                }
            }
        }); 
    }
    list();

    // stations autocomplete
    $(".origin-station, .destination-station").autocomplete({
        source: url,
        minLength: 2,
        messages: {
            noResults: '',
        },
        select: function(event, ui) {
            if ($(this).hasClass('origin-station')) {
                $(".origin").val(ui.item.value);
            } else {
                $(".destination").val(ui.item.value);
            }
        }
    });

    // submit route
    $(".submit-traderoute").on('click', (e) => {
        e.preventDefault();
        const origin_val = $(".origin-station").val();
        const dest_val = $(".destination-station").val();

        $(".origin").val(origin_val);
        $(".destination").val(dest_val);
        url = base + "Traderoutes/submitRoute/" + charID;
        let data = $(".form-horizontal").serialize();

        $.ajax({
            dataType: "json",
            url: url,
            global: false,
            data: data,
            type: "POST",
            success: (result) => {
                toastr[result.notice](result.message);
                $(".origin-station, .destination-station").val("");
                list();
            }
        });
    });
    
    // delete route
    $("table").on('click', 'button', function() {
        const $this = $(this);
        const url = base + "Traderoutes/" + "deleteRoute/" + $(this).attr('data-iddel');

        $.ajax({
            dataType: "json",
            url: url,
            global: false,
            success: (result) => {
                $this.closest("tr").remove();
                toastr[result.notice](result.message);
                list();
            }
        });
    });
});