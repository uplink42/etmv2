"use strict";
$(document).ready(function() {

    var table_active = $('#contracts-active-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        lengthMenu: [
            [50, 75, 100, -1],
            [50, 75, 100, "All"]
        ],
        deferRender: true,
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'contracts_active',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            orientation: 'landscape',
            title: 'contracts_active',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        order: [[ 0, "desc" ]],
        aoColumnDefs: [
            { 
            bSearchable: false, "aTargets": [ 6 ] 
        }]
    });

    var table_inactive = $('#contracts-inactive-table').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        lengthMenu: [
            [50, 75, 100, -1],
            [50, 75, 100, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'contracts_inactive',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'contracts_inactive',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        order: [[ 0, "desc" ]],
        aoColumnDefs: [
            { 
            bSearchable: false, aTargets: [ 6 ] 
        }]
    });


    $(".contracts-active-body p.yellow").html("<p>There are "+ table_active.rows().count() + " results</p>");
    $("#contracts-active-table_filter input").keyup(function () {
        $(".contracts-active-body p.yellow").html("There are "+ table_active.rows({filter: 'applied'}).count() + " results");
    });

    $(".contracts-inactive-body p.yellow").html("<p>There are "+ table_inactive.rows().count() + " results</p>");
    $("#contracts-inactive-table_filter input").keyup(function () {
        $(".contracts-inactive-body p.yellow").html("There are "+ table_inactive.rows({filter: 'applied'}).count() + " results");
    });
});