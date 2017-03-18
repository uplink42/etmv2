"use strict";
$(document).ready(function() {
    var bestraw = $('#bestraw').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
    var bestiph = $('#bestiph').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
    var bestcus = $('#bestcus').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
    var topstations = $('#topstations').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
    var blunders = $('#blunders').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
    var bestmargin = $('#bestmargin').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
    var fastest = $('#fastest').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
    var timezones = $('#timezones').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
    var problematic = $('#problematic').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        "lengthMenu": [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        buttons: [{
            extend: 'copy',
            className: 'btn-sm'
        }, {
            extend: 'csv',
            title: 'profits',
            className: 'btn-sm'
        }, {
            extend: 'pdf',
            title: 'profits',
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        "order": [],
    });
});