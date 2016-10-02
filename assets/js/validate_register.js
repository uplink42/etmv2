$(document).ready(function() {

    $("#username").focus().select();
    //only allow number input on api
    $("#apikey").keydown(function(e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    //validate email
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
    //form client validation
    $(".submit-register").on('click', 'input', function(e) {
        //client side validation
        var username = $("#username").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var repeatpassword = $("#repeatpassword").val();
        var apikey = $("#apikey").val();
        var vcode = $("#vcode").val();
        var reports = $("#reports").attr('id');
        
        $("#username").parent('div').removeClass('has-error');
        $("#email").parent('div').removeClass('has-error');
        $("#password").parent('div').removeClass('has-error');
        $("#repeatpassword").parent('div').removeClass('has-error');
        $("#apikey").parent('div').removeClass('has-error');
        $("#vcode").parent('div').removeClass('has-error');
        $("#reports").parent('div').removeClass('has-error');
        if (username.length < 6) {
            $("#username").parent('div').addClass('has-error');
            e.preventDefault();
        }
        if (!isEmail(email)) {
            $("#email").parent('div').addClass('has-error');
            e.preventDefault();
        }
        if (password.length < 6) {
            $("#password").parent('div').addClass('has-error');
            e.preventDefault();
        }
        if (password != repeatpassword || repeatpassword.length == 0) {
            $("#repeatpassword").parent('div').addClass('has-error');
            e.preventDefault();
        }
        if (apikey.length < 6) {
            $("#apikey").parent('div').addClass('has-error');
            e.preventDefault();
        }
        if (vcode.length < 6) {
            $("#vcode").parent('div').addClass('has-error');
            e.preventDefault();
        }
        if (reports.length < 6) {
            $("#reports").parent('div').addClass('has-error');
            e.preventDefault();
        }
    })
});