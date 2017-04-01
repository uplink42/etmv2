"use strict";
$(document).ready(function() {

    var url_autocomplete = base + "Citadeltax/searchCitadels/";
        url_req = base + "Citadeltax/addTax/";

    if (window.location.search !== '?aggr=1') {
        updateTable();
    }

    $("#citadel").autocomplete({
        source: url_autocomplete,
        minLength: 2,
        messages: {
            noResults: '',
        },
        select: function(event, ui) {
            $("#citadel").val(ui.item.value);
        }
    });

    $(".submit-tax").on('click', function(e) {
        e.preventDefault();
        var tax = $("#tax").val();
        var taxregex = /^(0(\.\d+)?|1(\.0+)?)$/;
        if (taxregex.test(tax)) {
            var data = $(".add-tax").serialize();
            $.ajax({
                dataType: "json",
                url: url_req,
                data: data,
                type: "POST",
                global: false,
                success: function(result) {
                    toastr[result.notice](result.message);
                    $("#citadel, #tax").val("");
                    updateTable();
                }
            });
        } else {
            toastr["error"](errHandle.get().INVALID_FORM);

        }
    });

    function updateTable() {
        $(document).bind(".go");
        var url_req = base + "Citadeltax/getTaxList/" + charID;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: function(result) {
                $(".tax-entries tr").remove();
                if (result.length === 0) {
                    var $row = "<tr><td colspan='3' class='text-center'>No tax rules present. Create one at the left</td></tr>";
                    $(".tax-entries").append($row);
                } else {
                    $.each(result, function(k, v) {
                        var id = result[k].idcitadel_tax;
                        var name = result[k].name;
                        var value = result[k].value;
                        var rm = "<a  data-id=" + id + " href=" + base + "Citadeltax/removeTax/" 
                        + charID + "/" + id + "><button class='btn btn-danger btn-remove-tax'>Remove</button></a>";
                        var $element = "<tr><td>" + name + "</td><td>" + value + "</td><td>" + rm + "</td></tr>";
                        $(".table tbody").append($element);
                    });
                }
            }
        });
    }

    $(".tax-list").on('click', "a", function(e) {
        e.preventDefault();
        var taxid = $(this).attr('data-id');
        var url_req = base + "Citadeltax/removeTax/" + charID + "/" + taxid;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: function(result) {
                toastr[result.notice](result.message);
                updateTable();
            }
        });
    });
});