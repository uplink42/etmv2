$(document).ready(function() {
    

    var base = $(".navbar").data('url');
    var url_autocomplete = base + "StockLists/searchItems/";

    function populateDropdown() {
        var url = base + "StockLists/populateList/";
        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $(".dropdown-list option.value").remove();
                $.each(result, function(k, v) {
                    var idlist = result[k].iditemlist;
                    var name = result[k].name;
                    var $element = "<option class='value'" + "id=" + idlist + ">" + name + "</option>";
                    $(".dropdown-list").append($element);
                });
            }
        });
    }
    populateDropdown();

    function getItems(id) {
        var url = base + "StockLists/getItems/" + id;
        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $(".table-items tr").remove();
                $.each(result, function(k, v) {
                    var id = result[k].id;
                    var name = result[k].name;
                    var vol = result[k].vol;
                    var price = result[k].price;
                    var $element = "<tr><td>" + name + "</td><td>" + vol + "</td><td>" + price + "</td><td>" + id + "</td><tr>";
                    $(".table-items tr").append($element);
                });
            }
        });
    }

    $(".submit-list").on('click', function(e) {
        e.preventDefault();
        var url = base + "StockLists/newList/";
        var data = $(".form-horizontal").serialize();
        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: function(result) {
                toastr[result.notice](result.message);
            }
        });
    });
    $(".dropdown-list").focus(function() {
        populateDropdown();
    });
    $(".dropdown-list").change(function(e) {
        var $el = $(this).find('option:selected').text();
        $(".yellow.contents").text($el);
        var id = $(this).find('option:selected').attr('id');
        getItems(id);
        
        if($(this).find('option:selected').hasClass('value')) {
            $(".add-list-item").show();
            $("#list-id").val(id);
            $(".stocklist-content").show();
        } else {
            $(".add-list-item").hide();
            $(".stocklist-content").hide();
        }
    });

    $("#item-name").autocomplete({
        source: url_autocomplete,
        minLength: 2,
        messages: {
            noResults: '',
        },
        select: function( event, ui ) {
            $("#item-name").val(ui.item.value);
        }
    });

    $(".btn-add-item").on('click', function (e) {
        e.preventDefault();
        var url = base + "StockLists/addItem/";
        var data = $(".add-item").serialize();

        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: function(result) {
                toastr[result.notice](result.message);
            }
        });
    });


});