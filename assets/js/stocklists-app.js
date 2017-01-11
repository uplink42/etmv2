"use strict";
$(document).ready(function() {

    var base = $(".navbar").data('url');
    var url_autocomplete = base + "Stocklists/searchItems/";

    function populateDropdown() {
        var url = base + "Stocklists/populateList/";

        $.ajax({
            dataType: "json",
            url: url,
            global: false,
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

    //get items from a list
    function getItems(id) {
        var url = base + "Stocklists/getItems/" + id;
        $(".table-items").attr('data-id', id);
        $.ajax({
            dataType: "json",
            url: url,
            global: false,
            success: function(result) {
                $(".table-items tr").remove();
                $.each(result, function(k, v) {
                    var id_item = result[k].id;
                    var url = "https://image.eveonline.com/Type/"+id_item+"_32.png";
                    var $img = "<img src='"+url+"' alt='item'>";
                    var name = result[k].name;
                    var vol = number_format(result[k].vol,2, '.', ',' );
                    var price = number_format(result[k].price,2, '.', ',');
                    var $btn = "<a href="+base+"Stocklists/removeItem/"+id_item+"/"+id+"><button class='btn btn-danger btn-remove-item'>Remove</button></a>";
                    var $element = "<tr><td> " + $img + " " + name + "</td><td>" + vol + "</td><td>" + price + "</td><td>" + $btn + "</td><tr>";
                    $(".table tbody").append($element);
                });
            }
        });
    }

    //submit new list
    $(".submit-list").on('click', function(e) {
        e.preventDefault();
        var url = base + "Stocklists/newList/";
        var data = $(".form-horizontal").serialize();
        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: function(result) {
                toastr[result.notice](result.message);
                var id_list = result.id,
                    list_name = $("#list-name").val();

                populateDropdown();
                $(".add-list-item").show();
                $(".stocklist-content").show();
                $(".yellow.contents").text(list_name);
                $("#list-id").val(id_list);
                getItems(id_list);
            }
        });
    });

    //select list from dropdown
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

    //item name autocomplete
    $("#item-name").autocomplete({
        global: false,
        source: url_autocomplete,
        minLength: 2,
        messages: {
            noResults: '',
        },
        select: function( event, ui ) {
            $("#item-name").val(ui.item.value);
        }
    });

    //add item
    $(".btn-add-item").on('click', function (e) {
        e.preventDefault();
        var list_id =  $("#list-id").val();
        
        var url = base + "Stocklists/addItem/";
        var data = $(".add-item").serialize();

        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            global: false,
            success: function(result) {
                toastr[result.notice](result.message);
                if(result.notice == "success") {
                    getItems(list_id);
                }
            }
        });

        $("#item-name").val("");
    });

    //remove item
    $(".table-items").on('click', 'a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var list_id = $(".table-items").attr('data-id');

        $.ajax({
            dataType: "json",
            url: url,
            global: false,
            success: function(result) {
                toastr[result.notice](result.message);
                if(result.notice == "success") {
                    getItems(list_id);
                }
            }
        });

    });

    //remove List
    $(".btn-delete-list-confirm").on('click', function(e) {
        e.preventDefault();
        var id = $(".table-items").attr('data-id');
        var url = base + "Stocklists/removeList/"+id;

        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                toastr[result.notice](result.message);
                if(result.notice == "success") {
                    $(".modal-close").trigger('click');
                    $(".add-list-item").hide();
                    $(".stocklist-content").hide();
                    $(".dropdown-list").val('0');
                    populateDropdown();
                }
            }
        });
    });
});