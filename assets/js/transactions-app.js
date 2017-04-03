"use strict";
$(document).ready(function() {
    $('.table-interval').text(interval);
    var postData = {
        transID: searchToObject().transID || 0,
        new    : searchToObject().new     || 0,
    };

    var table = $('#transactions-table').DataTable({
        order: [],
        bSortClasses: false,
        autoWidth: false,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        deferRender: true,
        ajax : {
            type : 'POST',
            url  : base + 'Transactions/getTransactionList/' + charID + '/' + interval + '/' + aggr,
            data : postData,
            dataSrc: function (json) {
                var return_data = [];
                for (var i = 0; i < json.data.length; i++) {
                    var button = json.data[i].type === 'Sell' ? 'btn-success' : 'btn-danger',
                        unlink = json.data[i].remaining == 0 || json.data[i].type == 'Sell' ? '-' : 1;
                    if (unlink === 1) {
                        unlink = "<button type='button' class='btn btn-default btn-unlink' data-toggle='modal' data-target='#unlink' data-transaction=" + 
                            json.data[i].transaction_id + ">" + "Unlink</button>";
                    }

                    return_data.push({
                        time: json.data[i].time,
                        item: '<img src="' + json.data[i].url + '">' + '<a class="item-name" style="color:#fff">' + json.data[i].item_name + '</a>',
                        quantity: json.data[i].quantity,
                        isk_unit: number_format(json.data[i].price_unit, 2, '.', ',' ),
                        isk_total: number_format(json.data[i].price_total, 2, '.', ',' ),
                        type: '<span class="btn btn-xs ' + button + '">' + json.data[i].type + '</span>',
                        other_party: json.data[i].client,
                        station: json.data[i].station_name,
                        character: json.data[i].character_name,
                        state: unlink
                    });
                  }
                updateTableTotals();
                return return_data;
            }   
        },
        columns: [
            { data: "time" },
            { data: "item" },
            { data: "quantity" },
            { data: "isk_unit" },
            { data: "isk_total" },
            { data: "type" },
            { data: "other_party" },
            { data: "station" },
            { data: "character" },
            { data: "state" },
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

    // totals display
    $(".transactions-body p.yellow").html("<p>There are "+ table.rows().count() + " results for a total of "
        + number_format(table.column(4).data().sum(),2, '.', ',' ) + " ISK</p>");

    $("#transactions-table_filter input").keyup(function () {
        $(".transactions-body p.yellow").html("There are "+ table.rows({filter: 'applied'}).count() + " results for a total of "
            + number_format(table.column(4, {"filter": "applied"} ).data().sum(),2, '.', ',' ) + " ISK");
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