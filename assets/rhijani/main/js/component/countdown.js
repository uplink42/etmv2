$(function(){
    var element = $('.countdown').find('.cd-content'),
        year = $(element).data('year'),
        month = $(element).data('month'),
        day = $(element).data('day'),
        newDate = new Date(year, month - 1, day);

    $(element).countdown({ 
        until: newDate,
        layout: '<div class="col-xs-6 col-sm-3 col-md-2 col-md-offset-2 not-right-column top-column"><div class="cd-element cd-days"><div class="cd-value">{dnn}</div><div class="cd-label">{dl}</div></div></div>' +
        '<div class="col-xs-6 col-sm-3 col-md-2 not-right-column top-column"><div class="cd-element cd-hours"><div class="cd-value">{hnn}</div><div class="cd-label">{hl}</div></div></div>' +
        '<div class="col-xs-6 col-sm-3 col-md-2 not-right-column"><div class="cd-element cd-minutes"><div class="cd-value">{mnn}</div><div class="cd-label">{ml}</div></div></div>' +
        '<div class="col-xs-6 col-sm-3 col-md-2"><div class="cd-element cd-seconds"><div class="cd-value">{snn}</div><div class="cd-label">{sl}</div></div></div>'
    });
});