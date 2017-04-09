"use strict";
$(document).ready(function() {
    $(".api-insert-2").hide();
    var apikey,
        vcode;
    list();

    function list() {
        var url = base + "Apikeymanagement/getCharacters/";
        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $(".table-character-list tbody tr").empty();
                if (result.length === 0) {
                    var $row = "<tr><td colspan='3' class='text-center'>No characters found</td></tr>";
                    $(".table-character-list tbody tr").prepend($row);
                } else {
                    $.each(result, function(k, v) {
                        var id = result[k].charid;
                        var img = "<img src='https://image.eveonline.com/Character/" + id + "_32.jpg'></img>"; 
                        var $row = "<tr><td>" + img + " " + result[k].name + "</td><td>" + result[k].api + 
                               "</td><td><button class='btn btn-danger btn-delete' data-iddel=" + id + 
                               " data-toggle='modal' data-target='#delete'>Remove</button></tr></tr>";
                        $(".table-character-list").prepend($row);
                    });
                }
            }
        });
    }

    // start delete
    $("table").on('click', 'button', function() {
        var id = $(this).data('iddel');
        var url = base + 'Apikeymanagement/removeCharacter/' + id;
        $(".btn-delete-confirm").attr('data-url', url);
    });

    // confirm delete
    $(".btn-delete-confirm").on('click', function() {
        var url = $(this).attr('data-url');
        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $('#delete').modal('toggle');
                toastr[result.notice](result.message);
                list();
            }
        });
    });

    //submit a new key
    $(".submit-add").on('click', function(e) {
        apikey = $("#keyid").val();
        vcode = $("#vcode").val();
        e.preventDefault();
        var url = base + "Apikeymanagement/addCharacters/";
        var data = $(".add-apikey").serialize();

        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: function(result) {
                if (typeof(result.notice) != "undefined") {
                    toastr[result.notice](result.message);
                } else {
                    $(".api-insert-1").toggle();
                    $(".api-insert-2").toggle();
                        
                    var count = 1;
                    $.each(result, function(k, v) {
                        var id = result[k][1].id;
                        var name = result[k][0].name;
                        var url = "https://image.eveonline.com/Character/" + id + "_32.jpg";
                        var cl = "character" + count;
                        var $element = "<tr><td><img src='" + url + "'alt='icon'></img>" + " " + name + "</td>"+
                                       "<td><input type='checkbox' class='" + cl + "' data-id='" + id + "'></td></tr>";
                        count++;
                        $(".table-character-selection tbody").append($element);
                    }); 
                }  
            }
        });
    });

    // submit characters
    $(".submit-add-2").on('click', function(e) {
        e.preventDefault();

        var selected = [];
        $(".character1").is(':checked') ? selected.push($(".character1").attr('data-id')) : "";
        $(".character2").is(':checked') ? selected.push($(".character2").attr('data-id')) : "";
        $(".character3").is(':checked') ? selected.push($(".character3").attr('data-id')) : "";
        
        var args = selected.join('/');
        var url = base + "Apikeymanagement/addCharactersStep/" + apikey + "/" + vcode + "/" + args;

        $.ajax({ 
            dataType: "json",
            url: url,
            success: function(result) {
                toastr[result.notice](result.message);
                if(result.notice === 'success') {
                    window.location.href = '../../logout';
                }
            }
        });
    });
});