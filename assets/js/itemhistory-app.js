"use strict";
$(document).ready(function() {
    let table;
    initializeTable([]);
    $('.item-data').hide();

    if (itemID) {
        getItemData(itemID);
    }
    
    function initializeTable(data) {
        table = $('#snapshot-table').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B>>tp",
            deferRender: true,
            autoWidth: false,
            data: data,
            columns: [
                { data: "date" },
                { data: "q_buy" },
                { data: "total_buy" },
                { data: "q_sell" },
                { data: "total_sell" },
                { data: "q_profit" },
                { data: "total_profit" },
                { data: "p_margin" },
            ],
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (parseFloat(aData.total_profit) > 0) {
                    $(nRow).addClass('success');
                } else if (parseFloat(aData.total_profit) < 0) {
                    $(nRow).addClass('danger');
                }
            },
            lengthMenu: [
                [25, 50, 100, -1],
                [25, 50, 100, "All"]
            ],
            buttons: [{
                extend: 'copy',
                className: 'btn-sm'
            }, {
                extend: 'csv',
                title: 'profits for item',
                className: 'btn-sm'
            }, {
                extend: 'pdf',
                title: 'profits for item',
                orientation: 'landscape',
                className: 'btn-sm'
            }, {
                extend: 'print',
                className: 'btn-sm'
            }],
            order: []
        });

        $("#item").autocomplete({
            global: false,
            source: base + 'Stocklists/searchItems/',
            minLength: 2,
            messages: {
                noResults: 'No results found',
                results : function(resultsCount) {}
            },
            select: function(event, ui) {
                $("#item").val(ui.item.value);
                $("#id").val(ui.item.id);
            }
        });

        $(".btn-search").on('click', function() {
            if (!$("#item").val() || !$("#id").val()) {
                return false;
            } else {
                let id = $("#id").val();
                getItemData(id);
            }
        });
    }

    function getItemData(id) {
        $.ajax({
            dataType: "json",
            url: base + "Itemhistory/getItemStats/" + id,
            data: { chars: charID, interval: interval, aggr: aggr },
            type: "POST",
            success: function(result) {
                if (result) {
                    $('.item-data').show();

                    $("#item-name").text(result.item.name);
                    var id = result.item.eve_iditem;
                    var url = 'https://image.eveonline.com/Type/' + id + '_32.png';
                    $("#item-img").html('<img src="' + url + '">');

                    // assign to form
                    $('#profit-today').text(number_format(result.profit.snapshot.day.profit,2) + ' ISK');
                    $('#profit-q-today').text(number_format(result.profit.snapshot.day.quantity,0));
                    $('#profit-margin-today').text(number_format(result.profit.snapshot.day.margin,2));
                    $('#bought-today').text(number_format(result.buy.snapshot.day.total,2) + ' ISK');
                    $('#bought-q-today').text(number_format(result.buy.snapshot.day.quantity,0));
                    $('#sold-today').text(number_format(result.sell.snapshot.day.total,2) + ' ISK');
                    $('#sold-q-today').text(number_format(result.sell.snapshot.day.quantity,0));

                    $('#profit-interval').text(number_format(result.profit.snapshot.interval.profit,2) + ' ISK');
                    $('#profit-q-interval').text(number_format(result.profit.snapshot.interval.quantity,0));
                    $('#profit-margin-interval').text(number_format(result.profit.snapshot.interval.margin,2));
                    $('#bought-interval').text(number_format(result.buy.snapshot.interval.total,2) + ' ISK');
                    $('#bought-q-interval').text(number_format(result.buy.snapshot.interval.quantity,0));
                    $('#sold-interval').text(number_format(result.sell.snapshot.interval.total,2) + ' ISK');
                    $('#sold-q-interval').text(number_format(result.sell.snapshot.interval.quantity,0));

                    $('#profit-lifetime').text(number_format(result.profit.snapshot.lifetime.profit,2) + ' ISK');
                    $('#profit-q-lifetime').text(number_format(result.profit.snapshot.lifetime.quantity,0));
                    $('#profit-margin-lifetime').text(number_format(result.profit.snapshot.lifetime.margin,2));
                    $('#bought-lifetime').text(number_format(result.buy.snapshot.lifetime.total,2) + ' ISK');
                    $('#bought-q-lifetime').text(number_format(result.buy.snapshot.lifetime.quantity,0));
                    $('#sold-lifetime').text(number_format(result.sell.snapshot.lifetime.total,2) + ' ISK');
                    $('#sold-q-lifetime').text(number_format(result.sell.snapshot.lifetime.quantity,0));

                    $('.chart-profit').remove();
                    $('.chart-sell').remove();
                    $('.chart-buy').remove();

                    //draw charts
                    FusionCharts.ready(function () {
                        const chart = new FusionCharts({
                            type: 'mscombidy2d',
                            renderAt: 'chart-profit',
                            width: '100%',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: result.profit.chart,
                        });
                        chart.render();
                    });

                    FusionCharts.ready(function () {
                        const chart = new FusionCharts({
                            type: 'mscombidy2d',
                            renderAt: 'chart-margin',
                            width: '100%',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: result.profit.margin_chart,
                        });
                        chart.render();
                    });

                    FusionCharts.ready(function () {
                        const chart = new FusionCharts({
                            type: 'mscombidy2d',
                            renderAt: 'chart-sell',
                            width: '100%',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: result.sell.chart,
                        });
                        chart.render();
                    });

                    FusionCharts.ready(function () {
                        const chart = new FusionCharts({
                            type: 'mscombidy2d',
                            renderAt: 'chart-buy',
                            width: '100%',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: result.buy.chart,
                        });
                        chart.render();
                    });

                    // update tables
                    const tableData = [];
                    const types = ['buy', 'sell', 'profit'];

                    types.forEach((type) => {
                        result[type].chart.categories[0].category.forEach((value, index) => {
                            tableData[index] = tableData[index] ? tableData[index] : {};
                            tableData[index].date = value.label;
                        });

                        result[type].chart.dataset[0].data.forEach((value, index) => {
                            let key1 = 'total_' + type;
                            tableData[index][key1] = number_format(value.value, 2, '.', ',' );
                        });

                        result[type].chart.dataset[1].data.forEach((value, index) => {
                            let key2 = 'q_' + type;
                            tableData[index][key2] = number_format(value.value, 0, '.', ',' );
                        });
                    });

                    // margin
                    result.profit.margin_chart.dataset[0].data.forEach((value, index) => {
                        tableData[index].p_margin = number_format(value.value, 2, '.', ',' );
                    });

                    table.destroy();
                    tableData.reverse();
                    initializeTable(tableData);
                }
            }
        });
    }
});