$(function() {
    // nw tracker stats
    const nwTracker = function() {
        const url_req = base + 'Networthtracker/getNetworthChart/' + charID + '/' + interval + '/' + aggr;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: function(result) {
                if (result.categories && result.chart && result.dataset) {
                    FusionCharts.ready(() => {
                        const chart = new FusionCharts({
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
});
