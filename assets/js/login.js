$(function() {
	// if user has a session, go to next page
	if (auth) {
		$('.panel-login').hide();
        $('.panel-loading').show();
        window.location = base + 'Updater';
	}

	$("#username").focus().select();
    $('#login-btn').on('click', () => {
        if ($("#username").val().length > 0 && $("#password").val().length > 0) {
            $('.panel-login').hide();
            $('.panel-loading').show();
        }
    });
});