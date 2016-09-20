$(document).ready(function() {

    var table1 = $('#sellorders-table').DataTable({
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
            title: 'sellorders',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'sellorders',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "aoColumnDefs": [
            { 
            "bSearchable": false, "aTargets": [ 3 ] 
        }],
        "order": []
    });

    $(".sellorders-body p.yellow").html("<p>There are "+ table.rows().count() + " results for a total of "
        + number_format(table.column(8).data().sum(),2, '.', ',' ) + " ISK</p>");
    $("#sellorders-table_filter input").keyup(function () {
        $(".sellorders-body p.yellow").html("There are "+ table.rows({filter: 'applied'}).count() + " results for a total of "
            + number_format(table.column(8, {"filter": "applied"} ).data().sum(),2, '.', ',' ) + " ISK.");
    });

    $(".item-name").on('click', function() {
        var name = $(this).text();
        $("input.form-control").val(name);
        $("input.form-control").trigger("keyup");
    });


});