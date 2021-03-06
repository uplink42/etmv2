$(function() {
    let totalValue = 0;
    let table = $('#assets-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax : {
            type : 'POST',
            url  : base + 'Assets/getAssetsTable/' + charID + '/' + aggr,
            data : {region_id: regionID},
            dataSrc: (json) => {
                let return_data = [];
                totalValue = json.recordsSum;
                for (let i = 0; i < json.data.length; i++) {
                    return_data.push({
                        item_name: '<img src="' + json.data[i].url + '" class="pr-5">' + '<a class="item-name" style="color:#fff">' +  json.data[i].item_name,
                        owner: json.data[i].owner,
                        quantity: number_format(json.data[i].quantity, 0, '.', ',' ),
                        loc_name: json.data[i].loc_name,
                        total_volume: number_format(json.data[i].total_volume, 2, '.', ',' ),
                        unit_value: number_format(json.data[i].unit_value, 2, '.', ',' ),
                        total_value: number_format(json.data[i].total_value, 2, '.', ',' )
                    });
                  }
                updateTableTotals(totalValue);
                return return_data;
            }   
        },
        initComplete: function (settings, json) { 
            let info = this.api().page.info();
        },
        columns: [
            { data: "item_name" },
            { data: "owner" },
            { data: "quantity" },
            { data: "loc_name" },
            { data: "total_volume" },
            { data: "unit_value" },
            { data: "total_value" },
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
        }],
        autoWidth: false
    });

    function updateTableTotals() {
        setTimeout(() => {
            $('.assets-body .input-sm').trigger('keyup');
        });
    }

    $("#assets-table_filter input").keyup(() => {
        let info = table.page.info();
        $(".assets-body p.yellow").html("There are " + info.recordsTotal + " results for a total value of " + 
            number_format(totalValue, 2, '.', ',' ) + " ISK");
    });

    // item filter
    $("table").on('click', 'a', function () {
        let name = $(this).text();
        $(".assets-body input.form-control").val(name);
        $(".assets-body input.form-control").trigger("keyup");
    });

    // normalize sparkline
    var i = 0;
    $(".spark-tab").on('click', () => {
        i++;
        if (i<2) {
            $(".spark-tab").trigger('click');
            renderSparkline();
        }
    });

    function renderSparkline() {
        var sparklineCharts = () => {
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
        $(window).resize(() => {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparklineCharts, 100);
        });
        // Run sparkline
        sparklineCharts();
    }

    // load assets chart
    var assetsChart = () => {
        FusionCharts.ready(() => {
            var lineChart = new FusionCharts({
                type: 'pie3d',
                renderAt: 'pie',
                width: '100%',
                height: '500',
                dataFormat: 'json',
                dataSource: pieData
            });
            lineChart.render();
        });
    };

    assetsChart();
});