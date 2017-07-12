$(function() {
    const url_autocomplete = base + "Citadeltax/searchCitadels/";

    if (window.location.search !== '?aggr=1') {
        updateTable();
    }

    $("#citadel").autocomplete({
        source: url_autocomplete,
        minLength: 2,
        messages: {
            noResults: '',
        },
        select: (event, ui) => {
            $("#citadel").val(ui.item.value);
        }
    });

    $(".submit-tax").on('click', (e) => {
        e.preventDefault();
        var tax = $("#tax").val();
        var taxregex = /^(0(\.\d+)?|1(\.0+)?)$/;
        if (taxregex.test(tax)) {
            var data = $(".add-tax").serialize();
            $.ajax({
                dataType: "json",
                url: base + "Citadeltax/addTax/",
                data: data,
                type: "POST",
                global: false,
                success: (result) => {
                    toastr[result.notice](result.message);
                    $("#citadel, #tax").val("");
                    updateTable();
                }
            });
        } else {
            toastr.error(errHandle.get().INVALID_FORM);
        }
    });

    function updateTable() {
        $(document).bind(".go");
        const url_req = base + "Citadeltax/getTaxList/" + charID;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: function(result) {
                $(".tax-entries tr").remove();
                if (result.length === 0) {
                    let $row = "<tr><td colspan='3' class='text-center'>No tax rules present. Create one at the left</td></tr>";
                    $(".tax-entries").append($row);
                } else {
                    $.each(result, (k, v) => {
                        const id = result[k].idcitadel_tax;
                        const name = result[k].name;
                        const value = result[k].value;
                        const rm = "<a  data-id=" + id + " href=" + base + "Citadeltax/removeTax/" + 
                            charID + "/" + id + "><button class='btn btn-danger btn-remove-tax'>Remove</button></a>";
                        const $element = "<tr><td>" + name + "</td><td>" + value + "</td><td>" + rm + "</td></tr>";
                        $(".table tbody").append($element);
                    });
                }
            }
        });
    }

    $(".tax-list").on('click', "a", function(e) {
        e.preventDefault();
        const taxid = $(this).attr('data-id');
        const url_req = base + "Citadeltax/removeTax/" + charID + "/" + taxid;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: (result) => {
                toastr[result.notice](result.message);
                updateTable();
            }
        });
    });
});