"use strict";
$(document).ready(function() {
    $('.table-interval').text(interval);
    var table = $('#profits-2-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        deferRender: true,
        ajax : {
            type   : 'GET',
            url    : base + 'Profits/getProfitTable/' + charID + '/' + interval + '/' + aggr,
            dataSrc: function (json) {
                var return_data = [];
                for (var i = 0; i < json.data.length; i++) {
                    return_data.push({
                        item: '<img src="' + json.data[i].url + '">' + '<a class="item-name" style="color:#fff">' + json.data[i].item_name + '</a>',
                        buy_sell_link: '<a href="' + base + 'transactions/index/' + charID + '?transID=' + json.data[i].trans_buy + '" target=_blank>' + 
                            '<span class="btn btn-xs btn-danger">B</span></a><br>' + 
                            '<a href="' + base + 'transactions/index/' + charID + '?transID=' + json.data[i].trans_sell + '" target=_blank>' + 
                            '<span class="btn btn-xs btn-success">B</span></a>',
                        systems: json.data[i].sys_buy + '<br>' + json.data[i].sys_sell,
                        isk_unit: number_format(json.data[i].buy_price, 2, '.', ',' ) + '<br>' + number_format(json.data[i].sell_price, 2, '.', ',' ),
                        quantity: number_format(json.data[i].profit_quantity, 0, '.', ',' ),
                        isk_total: number_format(json.data[i].buy_price_total, 2, '.', ',' ) + '<br>' + number_format(json.data[i].sell_price_total, 2, '.', ',' ),
                        times: json.data[i].time_buy + '<br>' + json.data[i].time_sell,
                        characters: json.data[i].character_buy + '<br>' + json.data[i].character_sell,
                        isk_profit: number_format(json.data[i].profit_total, 2, '.', ',' ),
                        margin: '<a class= "btn btn-default btn-xs">' + number_format(json.data[i].margin, 2, '.', ',' ) + '</a>',
                        duration: json.data[i].diff
                    });
                  }
                updateTableTotals();
                return return_data;
            }   
        },
        columns: [
            { data: "item" },
            { data: "buy_sell_link" },
            { data: "systems" },
            { data: "isk_unit" },
            { data: "quantity" },
            { data: "isk_total" },
            { data: "times" },
            { data: "characters" },
            { data: "isk_profit" },
            { data: "margin" },
            { data: "duration" },
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (parseFloat(aData.isk_profit) > 0) {
                $(nRow).addClass('success');
            } else {
                $(nRow).addClass('danger');
            }
        },
        lengthMenu: [
            [50, 75, 100, -1],
            [50, 75, 100, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        aoColumnDefs: [{
            "bSearchable": false,
            "aTargets": [3]
        }],
        order: []
    });

    // filters
    $("#profits-2-table_filter input").keyup(function() {
        $(".profits-2-body p.yellow").html("There are " + table.rows({
            filter: 'applied'
        }).count() + " results for a total of " + number_format(table.column(8, {
            "filter": "applied"
        }).data().sum(), 2, '.', ',') + " ISK.");
    });

    // links
    $("table").on('click', 'a', function() {
        var name = $(this).text();
        $("input.form-control").val(name);
        $("input.form-control").trigger("keyup");
    });

    // get profit by item
    if (window.location.hash) {
        var string = window.location.hash.substring(1);
        $("input.form-control").val(string);
        $("input.form-control").trigger("keyup");
    }

    // reload
    $('.dropdown-interval a').on('click', function(e) {
        e.preventDefault();
        if ($(this).attr('data-id') != interval) {
            var url;
            interval = $(this).attr('data-id'),
                url    = base + 'Profits/getProfitTable/' + charID + '/' + interval + '/' + aggr;

            table.ajax.url(url).load();
            reloadChart();
            $('.table-interval').text(interval);
        }
    });

    function updateTableTotals() {
        setTimeout(function() {
            $('.profits-2-body .input-sm').trigger('keyup');
        });
    }

    function reloadChart() {
        profitsLineChart();
    }

    // load daily chart
    var profitsLineChart = function() {
        var url_req = base + 'Profits/getProfitChart/' + charID + '/' + interval + '/' + aggr + '/' + itemID;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: function(result) {
                if (result.chart && result.data) {
                    FusionCharts.ready(function () {
                        var lineChart = new FusionCharts({
                            type: 'line',
                            renderAt: 'chart-2',
                            width: '100%',
                            height: '400',
                            dataFormat: 'json',
                            dataSource: result
                        });
                        lineChart.render();
                    });
                }
            }
        });
    };

    profitsLineChart();

});