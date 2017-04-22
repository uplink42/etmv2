"use strict";
$(document).ready(function() {

    var table1 = $('#buyorders-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, "All"]
        ],
        deferRender: true,
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'buyorders',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'buyorders',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        aoColumnDefs: [
            { 
            bSearchable: false, aTargets: [ 3 ] 
        }],
        order: []
    });

    $(".buyorders-body p.yellow").html("<p>There are "+ table1.rows().count() + " results for a total of "
        + number_format(table1.column(4).data().sum(),2, '.', ',' ) + " ISK</p>");
    $("#buyorders-table_filter input").keyup(function () {
        $(".buyorders-body p.yellow").html("There are "+ table1.rows({filter: 'applied'}).count() + " results for a total of "
            + number_format(table1.column(4, {"filter": "applied"} ).data().sum(),2, '.', ',' ) + " ISK.");
    });

    $(".buyorders-body .item-name").on('click', function() {
        var name = $(this).text();
        $(".buyorders-body input.form-control").val(name);
        $(".buyorders-body input.form-control").trigger("keyup");
    });

    var table2 = $('#sellorders-table').DataTable({
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

    $(".sellorders-body p.yellow").html("<p>There are "+ table2.rows().count() + " results for a total of "
        + number_format(table2.column(4).data().sum(),2, '.', ',' ) + " ISK</p>");
    $("#sellorders-table_filter input").keyup(function () {
        $(".sellorders-body p.yellow").html("There are "+ table2.rows({filter: 'applied'}).count() + " results for a total of "
            + number_format(table2.column(4, {"filter": "applied"} ).data().sum(),2, '.', ',' ) + " ISK.");
    });

    $(".sellorders-body .item-name").on('click', function() {
        var name = $(this).text();
        $(".sellorders-body input.form-control").val(name);
        $(".sellorders-body input.form-control").trigger("keyup");
    });

    $(".btn-check").on('click', function() {
        $(".main-panel-orders").hide();
        $('.panel-loading').show();
    });
});