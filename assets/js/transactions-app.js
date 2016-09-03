$(document).ready(function() {
    var table = $('#transactions-table').DataTable({
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
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [[ 0, "desc" ]],
        "aoColumnDefs": [
            { 
            "bSearchable": false, "aTargets": [ 6 ] 
        }]
    });

    $(".assets-body").prepend("<p>There are "+ table.rows().count() + " results.</p>");

});