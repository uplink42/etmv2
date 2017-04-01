// nw tracker stats
var nwTracker = function() {
    var url_req = base + 'NetworthTracker/getNetworthChart/' + charID + '/' + interval + '/' + aggr;
    $.ajax({
        dataType: "json",
        url: url_req,
        global: false,
        success: function(result) {
            if (result.categories && result.chart && result.dataset) {
                FusionCharts.ready(function () {
                    var chart = new FusionCharts({
                        type: 'msline',
                        renderAt: 'nw',
                        width: '100%',
                        height: '500',
                        dataFormat: 'json',
                        dataSource: result
                    });
                    chart.render();
                });
            }
        }
    });         
}();   