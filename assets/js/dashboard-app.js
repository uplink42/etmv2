"use strict";
$(document).ready(function() {
    var base = $(".navbar").attr('data-url'),
        charID = $('.profil-link').attr('data-character');
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
    
    // datatables
    var table = $('#profits-table').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
            lengthMenu: [ [25, 50, -1], [25, 50, "All"] ],
            buttons: [],
            order: [],
    });

    // load pie chart
    var url_req = base + 'Dashboard/getPieChart/' + charID;
    $.ajax({
        dataType: "json",
        url: url_req,
        global: false,
        success: function(result) {
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
    });
});