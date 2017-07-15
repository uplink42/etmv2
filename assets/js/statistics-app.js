$(function() {
    let recap = $('#daily').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        deferRender: true,
        autoWidth: false,
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
        processing: true,
        serverSide: true,
        deferRender: true,
        autoWidth: false,
        ajax : {
            type: 'GET',
            url: base + 'Statistics/getBestItemsProfit/' + charID + '/' + interval + '/' + aggr,
            dataSrc: (json) => {
                let return_data = [];
                for (let i = 0; i < json.data.length; i++) {
                    return_data.push({
                        item: "<a href='" + base + "itemhistory/index/" + charID + "/" + interval + "/" + json.data[i].item_id + "?aggr=" + aggr + "' target='_blank'>" + 
                            "<img src='" + json.data[i].url + "' alt='icon' class='pr-5'>" + json.data[i].item + "</a>",
                        quantity: number_format(json.data[i].quantity, 0, '.', ',' ),
                        profit: number_format(json.data[i].profit, 2, '.', ',' ),
                    });
                }
                return return_data;
            }
        },
        columns: [
            { data: "item" },
            { data: "quantity" },
            { data: "profit" },
        ],
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

    let bestmargin = $('#bestmargin').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        processing: true,
        serverSide: true,
        deferRender: true,
        autoWidth: false,
        ajax : {
            type: 'GET',
            url: base + 'Statistics/getBestItemsMargin/' + charID + '/' + interval + '/' + aggr,
            dataSrc: (json) => {
                let return_data = [];
                for (let i = 0; i < json.data.length; i++) {
                    return_data.push({
                        item: "<a href='" + base + "itemhistory/index/" + charID + "/" + interval + "/" + json.data[i].item_id + 
                            "?aggr=" + aggr + "' target='_blank'>" + 
                            "<img src='" + json.data[i].url + "' alt='icon' class='pr-5'>" + json.data[i].item + "</a>",
                        quantity: number_format(json.data[i].quantity, 0, '.', ',' ),
                        margin: number_format(json.data[i].margin, 2, '.', ',' ),
                    });
                }
                return return_data;
            }
        },
        columns: [
            { data: "item" },
            { data: "quantity" },
            { data: "margin" },
        ],
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

    let problematic = $('#problematic').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        processing: true,
        serverSide: true,
        deferRender: true,
        autoWidth: false,
        ajax : {
            type: 'GET',
            url: base + 'Statistics/getProblematicItems/' + charID + '/' + interval + '/' + aggr,
            dataSrc: (json) => {
                let return_data = [];
                for (let i = 0; i < json.data.length; i++) {
                    return_data.push({
                        item: "<a href='" + base + "itemhistory/index/" + charID + "/" + interval + "/" + 
                            json.data[i].item_id + "?aggr=" + aggr + "' target='_blank'>" + 
                            "<img src='" + json.data[i].url + "' alt='icon' class='pr-5'>" + json.data[i].item + "</a>",
                        quantity: number_format(json.data[i].quantity, 0, '.', ',' ),
                        profit: number_format(json.data[i].profit, 2, '.', ',' ),
                    });
                }
                return return_data;
            }
        },
        columns: [
            { data: "item" },
            { data: "quantity" },
            { data: "profit" },
        ],
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
        processing: true,
        serverSide: true,
        deferRender: true,
        autoWidth: false,
        ajax : {
            type: 'GET',
            url: base + 'Statistics/getBestIPH/' + charID + '/' + interval + '/' + aggr,
            dataSrc: (json) => {
                let return_data = [];
                for (let i = 0; i < json.data.length; i++) {
                    return_data.push({
                        item: "<a href='" + base + "itemhistory/index/" + charID + "/" + interval + "/" + 
                            json.data[i].item_id + "?aggr=" + aggr + "' target='_blank'>" + 
                            "<img src='" + json.data[i].url + "' alt='icon' class='pr-5'>" + json.data[i].item + "</a>",
                        quantity: number_format(json.data[i].quantity, 0, '.', ',' ),
                        profit: number_format(json.data[i].profit, 2, '.', ',' ),
                        iph: number_format(json.data[i].iph, 2, '.', ',' ),
                    });
                }
                return return_data;
            }
        },
        columns: [
            { data: "item" },
            { data: "quantity" },
            { data: "profit" },
            { data: "iph" },
        ],
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

    let bestcus = $('#bestcus').DataTable({
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4 text-right'f>>tp",
        deferRender: true,
        autoWidth: false,
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
        autoWidth: false,
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

    let blunders = $('#blunders').DataTable({
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
        deferRender: true,
        autoWidth: false
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
        deferRender: true,
        autoWidth: false
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
        deferRender: true,
        autoWidth: false
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
                    FusionCharts.ready(() => {
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
                    FusionCharts.ready(() => {
                        let chart = new FusionCharts({
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