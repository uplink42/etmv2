"use strict";
$(document).ready(function() {
    var table = $('#profits-2-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [
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
        "aoColumnDefs": [{
            "bSearchable": false,
            "aTargets": [3]
        }],
        "order": []
    });
    $(".profits-2-body p.yellow").html("<p>There are " + table.rows().count() 
        + " results for a total of " + number_format(table.column(8).data().sum(), 2, '.', ',') 
        + " ISK</p>");
    $("#profits-2-table_filter input").keyup(function() {
        $(".profits-2-body p.yellow").html("There are " + table.rows({
            filter: 'applied'
        }).count() + " results for a total of " + number_format(table.column(8, {
            "filter": "applied"
        }).data().sum(), 2, '.', ',') + " ISK.");
    });

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

    // load daily chart
    var url_req = base + 'Profits/getProfitChart/' + charID + '/' + interval + '/' + itemID;
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
});