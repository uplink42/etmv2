"use strict";
$(document).ready(function() {
    // Sparkline charts
    var sparklineCharts = function() {
        $(".sparkline").sparkline($(".sparkline").data('profit'), {
            type: 'line',
            lineColor: '#FFFFFF',
            lineWidth: 3,
            fillColor: '#404652',
            height: 47,
            width: '100%'
        });
    };

    var sparkResize;
    // Resize sparkline charts on window resize
    $(window).resize(function() {
        clearTimeout(sparkResize);
        sparkResize = setTimeout(sparklineCharts, 100);
    });
    // Run sparkline
    sparklineCharts();
    
    // Run toastr notification with Welcome message
    setTimeout(function() {
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "closeButton": true,
            "progressBar": true,
            "showEasing": "swing",
            "timeOut": "6000"
        };
        toastr.success('<strong>Welcome to Eve Trade Master 2.1!</strong> <br/><small>\n\
        Hope you enjoy the new layout and new features. Make sure to report any bugs you find. \n\
        Donations are always welcome, too!</small>');
    }, 1600);

    drawPieChart();

    // load pie chart
    function drawPieChart() {
        var url_req = base + 'Dashboard/getPieChart/' + charID + '/' + aggr;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: function(result) {
                if (result.chart && result.data) {
                    FusionCharts.ready(function () {
                        var pieChart = new FusionCharts({
                            type: 'pie2d',
                            renderAt: 'pie',
                            width: '100%',
                            height: '300',
                            dataFormat: 'json',
                            dataSource: result
                        });
                        pieChart.render();
                    });
                }
            }
        });
    }
    
    $('.table-interval').text(interval);
    // datatables
    var table = $('#profits-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        lengthMenu: [ [25, 50, -1], [25, 50, "All"] ],
        buttons: [],
        order: [],
        deferRender: true,
        ajax : {
            type: 'GET',
            url: base + 'Dashboard/getProfitTable/' + charID + '/' + interval + '/' + aggr,
            dataSrc: function (json) {
                var return_data = [];
                for (var i = 0; i < json.data.length; i++) {
                    return_data.push({
                        item: '<img src="' + json.data[i].url + '">' + json.data[i].item_name,
                        system: json.data[i].system_name,
                        date: json.data[i].sell_time,
                        quantity: number_format(json.data[i].quantity, 0, '.', ',' ),
                        profit: number_format(json.data[i].profit_total, 2, '.', ',' ),
                        margin: '<a class= "btn btn-default btn-xs">' + number_format(json.data[i].margin, 2, '.', ',' ) + '</a>',
                    });
                  }
                return return_data;
            }
        },
        columns: [
            { data: "item" },
            { data: "system" },
            { data: "date" },
            { data: "quantity" },
            { data: "profit" },
            { data: "margin" }
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (parseFloat(aData.profit) > 0) {
                $(nRow).addClass('success');
            } else {
                $(nRow).addClass('danger');
            }
        }
    });

    $('.dropdown-interval a').on('click', function(e) {
        e.preventDefault();
        if ($(this).attr('data-id') != interval) {
            var url;
            interval = $(this).attr('data-id'),
            url    = base + 'Dashboard/getProfitTable/' + charID + '/' + interval + '/' + aggr;

            table.ajax.url(url).load();
            $('.table-interval').text(interval);
        }
    }); 
});