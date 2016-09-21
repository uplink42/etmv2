function number_format(number, decimals, decPoint, thousandsSep){
		decimals = decimals || 0;
		number = parseFloat(number);

		if(!decPoint || !thousandsSep){
			decPoint = '.';
			thousandsSep = ',';
		}

		var roundedNumber = Math.round( Math.abs( number ) * ('1e' + decimals) ) + '';
		var numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber;
		var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
		var formattedNumber = "";

		while(numbersString.length > 3){
			formattedNumber += thousandsSep + numbersString.slice(-3)
			numbersString = numbersString.slice(0,-3);
		}

		return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : '');
	}

	jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
	    return this.flatten().reduce( function ( a, b ) {
	        if ( typeof a === 'string' ) {
	            a = a.replace(/[^\d.-]/g, '') * 1;
	        }
	        if ( typeof b === 'string' ) {
	            b = b.replace(/[^\d.-]/g, '') * 1;
	        }
	 
	        return a + b;
	    }, 0 );
	} );

$(document).ready(function() {

    //$(".panel-loading-common").hide();
    $(".btn-clear").on('click', function() {
        console.log("cl");
    	$("input.form-control").val("");
        $("input.form-control").trigger("keyup");
    });

	$(".go-back").on('click', function() {
		window.history.back();
	});

	$(".nav-u").on('click', function(e) {
		$("section").hide();
		$(".footer-panel").hide();
		$(".panel-loading-common").show();
	});

	$(".btn-send-feedback").on('click', function() {
        var data = $(".submit-feedback").serialize(),
		    base = $(".navbar").data('url'),
            url = base + "Main/sendEmail/";
        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: function(result) {
                toastr[result.notice](result.message);
                $(".btn-close").trigger('click');
            }
        });
	});
});
