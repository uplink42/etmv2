$(function() {
    const email_req = base + "Settings/email/";
    getEmail();
    getTrackingSettings();
    getReport();

    function getEmail() {
        $.ajax({
            dataType: "json",
            url: email_req,
            success: (result) => {
                $("#ch-email-current").val(result.email.email);
            }
        });
    }

    function getReport() {
        const report_req = base + "Settings/reports/";
        
        $.ajax({
            dataType: "json",
            url: report_req,
            success: (result) => {
                const res = result.data.reports;
                $(".report-options").val(res);
            }
        });
    }

    function getTrackingSettings() {
        const tracking_req = base + "Settings/tracking/";
        
        $.ajax({
            dataType: "json",
            url: tracking_req,
            success: (result) => {
                let res = result.data;
                for (let key in res) {
                    if (res.hasOwnProperty(key)) {
                        const $radio = $('input:radio[name=' + key + ']');
                        $radio.filter('[value=1]').prop('checked', res[key] === '1' ? true : false);
                        $radio.filter('[value=0]').prop('checked', res[key] === '0' ? true : false);
                        
                    }
                }

            }
        });
    }

    $(".btn-change-reports").on('click', (e) => {
        e.preventDefault();
        const data = $(".change-reports").serialize();
        const report_req = base + "Settings/changeReports/";
        
        $.ajax({
            dataType: "json",
            url: report_req,
            type: "POST",
            data: data,
            success: (result) => {
                toastr[result.notice](result.message);
                if (result.notice == "success") {
                    getReport();
                }
            }
        });
    });


    // change email
    $(".btn-change-email").on('click', (e) => {
        e.preventDefault();
        const email = $("#ch-email-email").val();
        const password = $("#ch-email-pw").val();

        if (isEmail(email)) {
            if (password.length >= 6) {
                const req = "Settings/changeEmail";
                const data = $(".change-email").serialize();
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    data: data,
                    url: base + req,
                    success: (result) => {
                        toastr[result.notice](result.message);
                        getEmail();
                        $("#ch-email-new").val("");
                        $("#ch-email-pw").val();
                    }
                });
            } else {
                toastr.error(errHandle.get().PASSWORD_TOO_SHORT);
            }
        } else {
            toastr.error(errHandle.get().INVALID_EMAIL);
        }
    });

    // change pw
    $(".btn-change-pw").on('click', function(e) {
        e.preventDefault();
        const newpw1 = $("#ch-pw-new1").val();
        const newpw2 = $("#ch-pw-new2").val();
        const data = $(".change-password").serialize();
        
        if (newpw1 === newpw2) {
            if (newpw1.length >= 6) {
                let req = "Settings/changePassword";
                $.ajax({
                    dataType: "json",
                    type: "POST",
                    data: data,
                    url: base + req,
                    success: (result) => {
                        toastr[result.notice](result.message);
                        $("#ch-pw-new1").val("");
                        $("#ch-pw-new2").val("");
                        $("#ch-pw-old").val("");
                    }
                });
            } else {
                toastr.error(errHandle.get().PASSWORD_TOO_SHORT, "Error");
            }
        } else {
            toastr.error(errHandle.get().PASSWORDS_MISMATCH, "Error");
        }
    });


    // change profit tracking settings
    $(".btn-change-tracking").on('click', (e) => {
        e.preventDefault();
        const data = $(".change-tracking").serialize();
        const tracking_req = base + "Settings/changeTracking/";
        
        $.ajax({
            dataType: "json",
            url: tracking_req,
            type: "POST",
            data: data,
            success: (result) => {
                toastr[result.notice](result.message);
                if (result.notice == "success") {
                    getReport();
                }
            }
        });
    });
});