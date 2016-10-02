$(document).ready(function() {
    
    var base = $(".navbar").data('url');
    var email_req = base + "Settings/email/";

    getEmail();

    function getEmail() {
        $.ajax({
            dataType: "json",
            url: email_req,
            success: function(result) {
                $("#ch-email-current").val(result.email.email);
            }
        });
    }

    $(".btn-change-email").on('click', function() {

        //var 

    });




});