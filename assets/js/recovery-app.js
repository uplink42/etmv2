"use strict";
$(document).ready(function() {

    var base = $(".mainwrapper").data('url');

    $(".reset-password").on('click', function(e) {
        e.preventDefault();
        
        var url  = base + "Recovery/recoverPassword";
        var data = $("#recovery").serialize();
        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function(result) {
                toastr[result.notice](result.message);
            }
        });
    });

    $(".forgot-username").on('click', function(e) {
        e.preventDefault();
        
        var url  = base + "Recovery/recoverUsername";
        var data = $("#recovery").serialize();
        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: function(result) {
                toastr[result.notice](result.message);
            }
        });
    });



});