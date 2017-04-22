"use strict";
$(document).ready(function() {
    let recap = $('#daily').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        deferRender: true,
        lengthMenu: [
            [25, 50, -1],
            [25, 50, "All"]
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
        order: [],
    });
    let bestraw = $('#bestraw').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        deferRender: true,
        lengthMenu: [
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
        order: [],
    });
    let bestiph = $('#bestiph').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        lengthMenu: [
            [5, 10, -1],
            [5, 10, "All"]
        ],
        deferRender: true,
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
        order: []
    });
    let bestcus = $('#bestcus').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        deferRender: true,
        lengthMenu: [
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
        order: []
    });
    let topstations = $('#topstations').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        deferRender: true,
        lengthMenu: [
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
        order: []
    });
    var blunders = $('#blunders').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        lengthMenu: [
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
        order: [],
        deferRender: true
    });
    let bestmargin = $('#bestmargin').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        lengthMenu: [
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
        order: [],
        deferRender: true
    });
    let fastest = $('#fastest').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        lengthMenu: [
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
        order: [],
        deferRender: true
    });
    let timezones = $('#timezones').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        lengthMenu: [
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
        order: [],
        deferRender: true
    });
    let problematic = $('#problematic').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        lengthMenu: [
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
        order: [],
        deferRender: true
    });

    // trade volumes chart
    var barChartStats = function() {
        let url_req = base + 'Statistics/getVolumesChart/' + charID + '/' + interval + '/' + aggr;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: function(result) {
                if (result.chart && result.dataset && result.categories) {
                    FusionCharts.ready(function () {
                        var barChart = new FusionCharts({
                            type: 'mscolumn2d',
                            renderAt: 'bar',
                            width: '100%',
                            height: '500',
                            dataFormat: 'json',
                            dataSource: result
                        });
                        barChart.render();
                    });
                }
            }
        });
    }();
    

    // profit distribution chart
    var pieChartStats = function() {
        let url_req = base + 'Statistics/getDistributionChart/' + charID + '/' + interval + '/' + aggr;
        $.ajax({
            dataType: "json",
            url: url_req,
            global: false,
            success: function(result) {
                if (result.chart && result.data) {
                    FusionCharts.ready(function () {
                        var chart = new FusionCharts({
                            type: 'pie3d',
                            renderAt: 'pie',
                            width: '100%',
                            height: '500',
                            dataFormat: 'json',
                            dataSource: result
                        });
                        chart.render();
                    });
                }
            }
        });         
    }();            
});