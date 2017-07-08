"use strict";
$(document).ready(function() {
    let table;
    initializeTable([]);
    
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
                noResults: '',
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
                $("#item-name").text($("#item").val());
                var id = $("#id").val();
                var url = 'https://image.eveonline.com/Type/' + id + '_32.png';
                $("#item-img").html('<img src="' + url + '">')
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
                    console.log(result);
                    // assign to form
                    $('#profit-today').text(number_format(result.profit.snapshot.day.profit,2) + ' ISK');
                    $('#profit-q-today').text(number_format(result.profit.snapshot.day.quantity,0));
                    $('#bought-today').text(number_format(result.buy.snapshot.day.total,2) + ' ISK');
                    $('#bought-q-today').text(number_format(result.buy.snapshot.day.quantity,0));
                    $('#sold-today').text(number_format(result.sell.snapshot.day.total,2) + ' ISK');
                    $('#sold-q-today').text(number_format(result.sell.snapshot.day.quantity,0));

                    $('#profit-interval').text(number_format(result.profit.snapshot.interval.profit,2) + ' ISK');
                    $('#profit-q-interval').text(number_format(result.profit.snapshot.interval.quantity,0));
                    $('#bought-interval').text(number_format(result.buy.snapshot.interval.total,2) + ' ISK');
                    $('#bought-q-interval').text(number_format(result.buy.snapshot.interval.quantity,0));
                    $('#sold-interval').text(number_format(result.sell.snapshot.interval.total,2) + ' ISK');
                    $('#sold-q-interval').text(number_format(result.sell.snapshot.interval.quantity,0));

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

                    table.destroy();
                    tableData.reverse();
                    initializeTable(tableData);
                }
            }
        });
    }
});