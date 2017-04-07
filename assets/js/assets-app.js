"use strict";
$(document).ready(function() {
    var itemID;
    var regionID = 0;
    var table = $('#assets-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        deferRender: true,
        ajax : {
            type : 'POST',
            url  : base + 'Assets/getAssetsTable/' + charID + '/' + aggr,
            data : {item_id: itemID},
            dataSrc: function (json) {
                var return_data = [];
                for (var i = 0; i < json.length; i++) {
                    return_data.push({
                        item: '<img src="' + json[i].url + '">' + '<a class="item-name" style="color:#fff">' + json.data[i].item_name + '</a>',
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
        ],
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'assets',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'assets',
            className: 'btn-sm',
            orientation: 'landscape'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        aoColumnDefs: [
            { 
            bSearchable: false, 
            aTargets: [ 3 ] 
        }]
    });

    $(".assets-body p.yellow").html("<p>There are "+ table.rows().count() + " results for a total of "
        + number_format(table.column(5).data().sum(),2, '.', ',' ) + " ISK</p>");
    $("#assets-table_filter input").keyup(function () {
        $(".assets-body p.yellow").html("There are "+ table.rows({filter: 'applied'}).count() + " results for a total of "
            + number_format(table.column(5, {"filter": "applied"} ).data().sum(),2, '.', ',' ) + " ISK");
    });

    $("table").on('click', 'a', function() {
        var name = $(this).text();
        $(".assets-body input.form-control").val(name);
        $(".assets-body input.form-control").trigger("keyup");
    });

    var i = 0;

    $(".spark-tab").on('click', function() {
        i++;
        if(i<2) {
            $(".spark-tab").trigger('click');
            renderSparkline();
        }
    });

    function renderSparkline() {
        // Sparkline charts
        var sparklineCharts = function() {
            $(".sparkline").sparkline($(".sparkline").data('profit'), {
                type: 'line',
                lineColor: '#f6a821',
                fillColor: '#f6a821',
                height: 100,
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
    }
    
});