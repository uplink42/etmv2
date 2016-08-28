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

    //time picker ajax request
    $(".dropdown-interval a").on('click', function () {
        var charid = $(".profil-link").data('character');
        var domain = $(".profil-link").data('url');
        var interval = $(this).parent('li').data('id');
        var url = domain + "Dashboard/getProfitsTable/" + charid + "/" + interval;

        console.log(url);

        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $("table tbody").empty();

                if(result.length ==0) {
                    table.rows().remove().draw();
                } else {
                    $.each(result, function(index, value) {

                        $element = "<tr><td>" + "<img src='" + value.url + "'alt='icon'>"
                                              + value.item_name + "</td><td>" 
                                              + value.system_name + "</td><td>"
                                              + value.sell_time + "</td><td>"
                                              + number_format(value.quantity,0, ',', '.') + "</td><td>"
                                              + number_format(value.profit_total,2, ',','.') + "</td><td>"
                                              + number_format(value.margin,2,',','.') + "</td></tr>";
                        $("table tbody").append($element);
                    }); 
                }

                
            }
        });

    });

    

   
    




      




});