$(document).ready(function() {

    if(window.location.hash == "#success") {
        toastr["success"]("Transaction unlinked successfully", "Notice");
    }

    if(window.location.hash == "#error") {
        toastr["error"]("Unable to unlink transaction. This transaction is already unlinked or does not belong to you.", "Error");
    }
    window.location.hash = "";
    
    var table = $('#transactions-table').DataTable({
        "order": [],
        "bSortClasses": false,
        "autoWidth": false,
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
            title: 'transactions',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'transactions',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "aoColumnDefs": [
            { 
            "bSearchable": false, "aTargets": [ 6 ] 
        }]
    });


    $(".transactions-body p.yellow").html("<p>There are "+ table.rows().count() + " results for a total of "
        + number_format(table.column(4).data().sum(),2, '.', ',' ) + " ISK</p>");
    $("#transactions-table_filter input").keyup(function () {
        $(".transactions-body p.yellow").html("There are "+ table.rows({filter: 'applied'}).count() + " results for a total of "
            + number_format(table.column(4, {"filter": "applied"} ).data().sum(),2, '.', ',' ) + " ISK");
    });
    

    $("table").on('click', 'button', function() {
        var id = $(this).data('transaction');
        var url = $("#unlink").data('url');
        var full_url = url + "/" + id;
        $(".btn-unlink-confirm").attr('data-url', full_url);
        
    });


    $(".btn-unlink-confirm").on('click', function() {
        var url = $(".btn-unlink-confirm").data('url');
        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $('#unlink').modal('toggle');
                if(result.result == "true") {
                    window.location.hash = "#success";
                    location.reload();
                } else {
                    window.location.hash = "#error";
                    location.reload();
                }
            }
        });
    });
});