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
            title: 'ExampleFile',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'ExampleFile',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "aoColumnDefs": [
            { 
            "bSearchable": false, "aTargets": [ 2 ] 
        }]
    });
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
});