"use strict";
$(document).ready(function() {

    var table = $('#assets-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [
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
        "aoColumnDefs": [
            { 
            "bSearchable": false, "aTargets": [ 3 ] 
        }]
    });

    $(".assets-body p.yellow").html("<p>There are "+ table.rows().count() + " results for a total of "
        + number_format(table.column(5).data().sum(),2, '.', ',' ) + " ISK</p>");
    $("#assets-table_filter input").keyup(function () {
        $(".assets-body p.yellow").html("There are "+ table.rows({filter: 'applied'}).count() + " results for a total of "
            + number_format(table.column(5, {"filter": "applied"} ).data().sum(),2, '.', ',' ) + " ISK");
    });

    $("table").on('click', 'a', function() {
        console.log("clicked");
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