"use strict";
if (typeof _404 != 'undefined') {
    throw new Error('404 error, FC what do?');
}

function number_format(number, decimals, decPoint, thousandsSep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number;
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
    var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
    var dec = (typeof decPoint === 'undefined') ? '.' : decPoint;
    var s = '';

    var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };

    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    
    return s.join(dec);
}

//validate email
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}


function searchToObject() {
    var pairs = window.location.search.substring(1).split("&"),
        obj = {},
        pair,
        i;

    for (i in pairs) {
        if (pairs[i] === "") continue;
        pair = pairs[i].split("=");
        obj[ decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    }

    return obj;
}

//override jquery ui - disable ajax calls when typing
$.ui.autocomplete.prototype._initSource = function() {
    var array, url,
        that = this;
    if ( $.isArray( this.options.source ) ) {
        array = this.options.source;
        this.source = function( request, response ) {
            response( $.ui.autocomplete.filter( array, request.term ) );
        };
    } else if ( typeof this.options.source === "string" ) {
        url = this.options.source;
        this.source = function( request, response ) {
            if ( that.xhr ) {
                that.xhr.abort();
            }
            that.xhr = $.ajax( {
                url: url,
                data: request,
                dataType: "json",
                global: false,
                success: function( data ) {
                    response( data );
                },
                error: function() {
                    response( [] );
                }
            } );
        };
    } else {
        this.source = this.options.source;
    }
};

//datetables api
jQuery.fn.dataTable.Api.register('sum()', function() {
    return this.flatten().reduce(function(a, b) {
        if (typeof a === 'string') {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if (typeof b === 'string') {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }
        return a + b;
    }, 0);
});

//load error messages
var errHandle = (function() {
    var loc = window.location.href,
    base = loc.substr(0, loc.indexOf('v2')),
    data;

    $.ajax({
        dataType: "json",
        url: base + "v2/main/getMsgHandles",
        success: function(result) {
            data = result;
        }
    });
    return {
        get: function() {
            if (data) return data;
        }
    };
})();

$(document).ready(function() {
    $("body").removeClass('loading-body');

    $(".btn-clear").on('click', function() {
        $("input.form-control").val("");
        $("input.form-control").trigger("keyup");
        window.location.hash = "";
    });

    $(".go-back").on('click', function() {
        window.history.back();
    });

    // default dropdown select
    var $options = $('.dropdown-interval li');
    $.each($options, function (index, value) {
        if (interval === $(value).find('a').attr('data-id')) {
            $(value).addClass('selected');
        }
    });

    // when selecting a time interval
    $('.dropdown-interval a').on('click', function(e) {
        var that = this;
        var $options = $(this).parent('li').siblings();
        $.each($options, function (index, value) {
            $(value).removeClass('selected');
        });
        $(this).parent('li').addClass('selected');

        // update character switch links
        var $chars = $('.character-select li a');
        $.each($chars, function(index, value) {
            var path = $(value).attr('href').split('/');
            var pos = path.indexOf('index') + 2;
            var toReplace = path[pos].substring(0, path[pos].indexOf('?'));
            // replace interval segment
            var res = path[pos].replace(toReplace, $(that).attr('data-id'));
            path[pos] = res;
            //stitch url back toguether
            var newPath = "";
            for (i = 1; i < path.length; i++) {
                newPath += "/";
                newPath += path[i];
            }
            // assign to url
            $(value).attr('href', newPath);
        });
    });

    $(".nav-u").on('click', function(e) {
        $("section").hide();
        $(".footer-panel").hide();
        $(".panel-loading-common").show();
    });

    $(".btn-send-feedback").on('click', function() {
        var data = $(".submit-feedback").serialize(),
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

    // show loading spinner for waiting periods over 250ms
    var shouldLoad = true;
        didLoad    = false;
    $(document).bind("ajaxStart.go", function () {
        shouldLoad = true;
        setTimeout(function() {
            if (shouldLoad) {
                console.log('spinner started');
                didLoad = true;
                $(".mainwrapper").addClass('loading-body');
                $('.panel-loading-ajax').show();
            }
        },250);
    });

    $(document).bind("ajaxStop.go", function () {
        shouldLoad = false;
        if (didLoad) {
            $(".mainwrapper").removeClass('loading-body');
            $('.panel-loading-ajax').hide();  
        }
    });
});