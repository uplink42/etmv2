"use strict";
$(document).ready(function() {

    var base = $(".navbar").data('url');
    var email_req = base + "Settings/email/";
    getEmail();
    getTrackingSettings();
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
                $(".report-options").val(res);
            }
        });
    }

    function getTrackingSettings() {
        var tracking_req = base + "Settings/tracking/";
        
        $.ajax({
            dataType: "json",
            url: tracking_req,
            success: function(result) {
                var res = result.data;
                console.log(res);
                for (var key in res) {
                    if (res.hasOwnProperty(key)) {
                        var $radio = $('input:radio[name=' + key + ']');
                        $radio.filter('[value=1]').prop('checked', res[key] === '1' ? true : false);
                        $radio.filter('[value=0]').prop('checked', res[key] === '0' ? true : false);
                        
                    }
                }

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


    $(".btn-change-tracking").on('click', function(e) {
        e.preventDefault();
        var data = $(".change-tracking").serialize();
        var tracking_req = base + "Settings/changeTracking/";
        
        $.ajax({
            dataType: "json",
            url: tracking_req,
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
});