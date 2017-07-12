$(function() {
    $(".reset-password").on('click', (e) => {
        e.preventDefault();
        
        const url  = base + "Recovery/recoverPassword";
        const data = $("#recovery").serialize();
        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: (result) => {
                toastr[result.notice](result.message);
            }
        });
    });

    $(".forgot-username").on('click', (e) => {
        e.preventDefault();
        
        const url  = base + "Recovery/recoverUsername";
        const data = $("#recovery").serialize();
        $.ajax({
            dataType: "json",
            type: "POST",
            url: url,
            data: data,
            success: (result) => {
                toastr[result.notice](result.message);
            }
        });
    });
});