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
            "positionClass": "toast-top-right",
            "closeButton": true,
            "progressBar": true,
            "showEasing": "swing",
            "timeOut": "6000"
        };
        toastr.warning('<strong>Welcome to Eve Trade Master 2.0!</strong> <br/><small>\n\
        Hope you enjoy the new layout and new features. Make sure to report any bugs you find.</small>');
    }, 1600)
    
    //datatables
    var table = $('#profits-table').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
            "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
            buttons: [],
            "order": [],
            

    });
});