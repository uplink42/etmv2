"use strict";
$(document).ready(function() {
    $(".origin-station").focus().select();
    var base = $(".navbar").data('url');
    var url = base + "TradeRoutes/searchStations";
    var id = $(".navbar").data('id');

    function list() {
        var listurl = base + "TradeRoutes/listTradeRoutes/" + id;
        $.ajax({
            dataType: "json",
            url: listurl,
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
    //put base url and char id on header
    $(".submit-traderoute").on('click', function(e) {
        e.preventDefault();

        var origin_val = $(".origin-station").val();
        var dest_val = $(".destination-station").val();
        $(".origin").val(origin_val);
        $(".destination").val(dest_val);
        url = base + "TradeRoutes/submitRoute/" + id;

        var data = $(".form-horizontal").serialize();
        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: function(result) {
                toastr[result.notice](result.message);
                $(".origin-station, .destination-station").val("");
                list();
            }
        });
    });
    $("table").on('click', 'button', function() {
        var $this = $(this);
        var url = base + "TradeRoutes/" + "deleteRoute/" + $(this).data('iddel');

        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $this.closest("tr").remove();
                toastr[result.notice](result.message);
            }
        });
        list();
    });
});