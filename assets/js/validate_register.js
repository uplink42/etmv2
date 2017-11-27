$(function() {
    $("#username").focus().select();
        $("#username").val('asdrubal' + Math.random());
        $("#email").val('asdrubal' + Math.random() + '@eee.com');
        $("#password").val('123123');
        $("#repeatpassword").val('123123');
        $("#reports").attr('id',0);

    $(".submit-register").on('click', 'input', function(e) {
        let username = $("#username").val();
        let email = $("#email").val();
        let password = $("#password").val();
        let repeatpassword = $("#repeatpassword").val();

        function setInvalid(e) {
            window.scrollTo(0,0);
            e.preventDefault();
        }
        
        $("#username").parent('div').removeClass('has-error');
        $("#email").parent('div').removeClass('has-error');
        $("#password").parent('div').removeClass('has-error');
        $("#repeatpassword").parent('div').removeClass('has-error');

        if (username.length < 6) {
            $("#username").parent('div').addClass('has-error');
            setInvalid(e);
        }
        if (!isEmail(email)) {
            $("#email").parent('div').addClass('has-error');
            setInvalid(e);
        }
        if (password.length < 6) {
            $("#password").parent('div').addClass('has-error');
            setInvalid(e);
        }
        if (password != repeatpassword || repeatpassword.length == 0) {
            $("#repeatpassword").parent('div').addClass('has-error');
            setInvalid(e);
        }
    });
});