"use strict";
$(document).ready(function() {
	
	$("#username").focus().select();

    $('#login-btn').on('click', function() {
        if ($("#username").val().length > 0 && $("#password").val().length > 0) {
            $('.panel-login').hide();
            $('.panel-loading').show();
        }
    })
});