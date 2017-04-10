"use strict";
$(document).ready(function() {

	$(".api-insert-2").hide();
    var apikey;
    var vcode;

	$(".submit-add").on('click', function(e) {
		e.preventDefault();
        apikey = $("#keyid").val();
        vcode = $("#vcode").val();
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
                        var $element = "<tr><td class='char'><img src='" + url + "'alt='icon'></img>" + " " + name + "</td>"+
                                       "<td><input type='checkbox' class='" + cl + "' data-id='" + id + "'></td></tr>";
                        count++;
                        $(".table-character-selection tbody").append($element);
                    }); 
                }  
            }
        });
    })

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
                    window.location.href = '/v2/main/login/logout';
                }
            }
        });
    })
});