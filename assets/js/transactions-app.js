"use strict";
$(document).ready(function() {
    $('.table-interval').text(interval);
    var postData = {
        transID: searchToObject().transID || 0,
        new    : searchToObject().new     || 0,
    };

    var totalValue = 0;
    var table = $('#transactions-table').DataTable({
        order: [],
        bSortClasses: false,
        autoWidth: false,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        deferRender: true,
        processing: true,
        serverSide: true,
        ajax : {
            type : 'POST',
            url  : base + 'Transactions/getTransactionList/' + charID + '/' + interval + '/' + aggr,
            data : postData,
            dataSrc: function (json) {
                // not allowed
                if (json.message) {
                    toastr[json.notice](json.message);
                    throw new Error("Don't try to sneak up on others");
                } else {
                    totalValue = json.recordsSum;
                    var return_data = [];
                    for (let i = 0; i < json.data.length; i++) {
                        let button = json.data[i].type === 'Sell' ? 'btn-success' : 'btn-danger',
                            unlink = json.data[i].remaining === 0 || json.data[i].type == 'Sell' ? '-' : 1;
                        if (unlink === 1) {
                            unlink = "<button type='button' class='btn btn-default btn-unlink' data-toggle='modal' data-target='#unlink' data-transaction=" + 
                                json.data[i].transaction_id + ">" + "Unlink</button>";
                        }

                        return_data.push({
                            time: json.data[i].time,
                            item_name: '<img src="' + json.data[i].url + '">' + '<a class="item-name" style="color:#fff">' + json.data[i].item_name + '</a>',
                            quantity: json.data[i].quantity,
                            price_unit: number_format(json.data[i].price_unit, 2, '.', ',' ),
                            price_total: number_format(json.data[i].price_total, 2, '.', ',' ),
                            type: '<span class="btn btn-xs ' + button + '">' + json.data[i].type + '</span>',
                            client: json.data[i].client,
                            station_name: json.data[i].station_name,
                            character_name: json.data[i].character_name,
                            remaining: unlink
                        });
                    }
                    updateTableTotals();
                    return return_data;
                }
                
            }   
        },
        initComplete: function(settings, json) { 
            let info = this.api().page.info();
        },
        columns: [
            { data: "time" },
            { data: "item_name" },
            { data: "quantity" },
            { data: "price_unit" },
            { data: "price_total" },
            { data: "type" },
            { data: "client" },
            { data: "station_name" },
            { data: "character_name" },
            { data: "remaining" },
        ],
        lengthMenu: [
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
            orientation: 'landscape',
            className: 'btn-sm'
        }, {
            extend: 'print',
            className: 'btn-sm'
        }],
        aoColumnDefs: [
            { bSearchable: false, aTargets: [ 6 ] }
        ]
    });

    function updateTableTotals() {
        setTimeout(function() {
            $('.transactions-body .input-sm').trigger('keyup');
        });
    }

    // reload
    $('.dropdown-interval a').on('click', function(e) {
        e.preventDefault();
        if ($(this).attr('data-id') != interval) {
            var interval = $(this).attr('data-id'),
                url      = base + 'Transactions/getTransactionList/' + charID + '/' + interval + '/' + aggr;
            table.ajax.url(url).load();
            $('.table-interval').text(interval);
        }
    });

    // filter by item name
    $("table").on('click', 'a', function() {
        var name = $(this).text();
        $(".transactions-body input.form-control").val(name);
        $(".transactions-body input.form-control").trigger("keyup");
    });

    $("#transactions-table_filter input").keyup(function() {
        let info = table.page.info();
        $(".transactions-body p.yellow").html("There are " + info.recordsTotal + " results for a total value of " + 
            number_format(totalValue, 2, '.', ',' ) + " ISK");
    });
    
    // unlink prompt
    $("table").on('click', 'button', function() {
        var id = $(this).data('transaction');
        $(".btn-unlink-confirm").attr('data-id', id);
    });

    // unlink request
    $(".btn-unlink-confirm").on('click', function() {
        var id  = $(".btn-unlink-confirm").attr('data-id'),
            url = base + 'Transactions/unlink/' + id;
        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                var notice = result.type;
                $('#unlink').modal('toggle');
                toastr[notice](result.msg);
                table.ajax.reload();
            }
        });
    });
});