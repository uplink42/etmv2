$(document).ready(function() {
    
    var base = $(".navbar").data('url');
    var email_req = base + "Settings/email/";
    getEmail();
    getReport();

    function getEmail() {
        $.ajax({
            dataType: "json",
            url: email_req,
            success: function(result) {
                $("#ch-email-current").val(result.email.email);
            }
        });
    }

    function getReport() {
        var report_req = base + "Settings/reports/";
        
        $.ajax({
            dataType: "json",
            url: report_req,
            success: function(result) {
                var res = result.data.reports;
                console.log(res);
                $(".report-options").val(res);
                //$("#ch-email-current").val(result.email.email);
            }
        });
    }

    $(".btn-change-reports").on('click', function(e) {
        e.preventDefault();
        var data = $(".change-reports").serialize();
        var report_req = base + "Settings/changeReports/";
        
        $.ajax({
            dataType: "json",
            url: report_req,
            type: "POST",
            data: data,
            success: function(result) {
                toastr[result.notice](result.message);
                if (result.notice == "success") {
                    getReport();
                }
            }
        });
    });

    $(".btn-change-email").on('click', function(e) {
        e.preventDefault();
        var email = $("#ch-email-new").val();
        var password = $("#ch-email-pw").val();
        
        if (isEmail(email)) {
            if (password.length >= 6) {
                var req = "Settings/changeEmail";
                var data = $(".change-email").serialize();
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    data: data,
                    url: base + req,
                    success: function(result) {
                        console.log(result);
                        toastr[result.notice](result.message);
                        getEmail();
                        $("#ch-email-new").val("");
                        $("#ch-email-pw").val();
                    }
                });
            } else {
                toastr["error"](errHandle.get().PASSWORD_TOO_SHORT);
            }
        } else {
            toastr["error"](errHandle.get().INVALID_EMAIL);
        }
    });

    $(".btn-change-pw").on('click', function(e) {
        e.preventDefault();
        //var oldpw = $("#ch-pw-old");
        var newpw1 = $("#ch-pw-new1").val();
        var newpw2 = $("#ch-pw-new2").val();
        var data = $(".change-password").serialize();
        
        if (newpw1 === newpw2) {
            if (newpw1.length >= 6) {
                var req = "Settings/changePassword";
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    data: data,
                    url: base + req,
                    success: function(result) {
                        console.log(result);
                        toastr[result.notice](result.message);
                        $("#ch-pw-new1").val("");
                        $("#ch-pw-new2").val("");
                        $("#ch-pw-old").val("");
                    }
                });
            } else {
                toastr["error"](errHandle.get().PASSWORD_TOO_SHORT, "Error");
            }
        } else {
            toastr["error"](errHandle.get().PASSWORDS_MISMATCH, "Error");
        }
    });
});