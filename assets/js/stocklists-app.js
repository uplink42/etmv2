$(function() {
    let table,
        listID;

    // get all lists
    function populateDropdown() {
        const url = base + "Stocklists/populateList/";

        $.ajax({
            dataType: "json",
            url: url,
            global: false,
            success: (result) => {
                $(".dropdown-list option.value").remove();
                $.each(result, (k, v) => {
                    const idlist = result[k].iditemlist;
                    const name = result[k].name;
                    const $element = "<option class='value'" + "id=" + idlist + ">" + name + "</option>";
                    $(".dropdown-list").append($element);
                });
            }
        });
    }
    
    populateDropdown();

    function updateTotal() {
        setTimeout(function() {
            $('#stocklist-items_filter input').trigger('keyup');
        });
    }

    // get items from a list
    function getItems(id) {
        if (table) {
            table.destroy();
        }

        listID = id;
        table = $('#stocklist-items').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            deferRender: true,
            autoWidth: false,
            ajax : {
                type: 'GET',
                url : base + 'Stocklists/getItems/' + listID,
                dataSrc: function (json) {
                    let return_data = [];
                    for (let i = 0; i < json.data.length; i++) {
                        return_data.push({
                            item: '<img src="' + json.data[i].url + '">' + ' <a class="item-name" style="color:#fff">' + 
                                json.data[i].name + '</a>',
                            volume: number_format(json.data[i].vol,3, '.', ',' ),
                            estimate: number_format(json.data[i].price,2, '.', ','),
                            delete: "<a href=" + base + "Stocklists/removeItem/" + json.data[i].item_id+"/" + 
                                id + "><button class='btn btn-danger btn-remove-item'>Remove</button></a>" 
                        });
                    }
                    updateTotal();
                    return return_data;
                }   
            },
            columns: [
                { data: "item" },
                { data: "volume" },
                { data: "estimate" },
                { data: "delete" },
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
            aoColumnDefs: [{
                bSearchable: false,
                aTargets: [3]
            }],
            order: []
        });
    }

    // filters
    $("body").on('keyup', '#stocklist-items_filter input', () => {
        $(".stocklist-panel p.yellow").html("You have " + table.rows().count() + " items");
    });

    // submit new list
    $(".submit-list").on('click', (e) => {
        e.preventDefault();
        const url  = base + "Stocklists/newList/",
              data = $(".form-horizontal").serialize();
        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: function(result) {
                toastr[result.notice](result.message);
                listID = result.id;
                var list_name = $("#list-name").val();

                console.log(listID);
                populateDropdown();
                $('#list-name').val('').change();
                $('.dropdown-list').val(listID);
                $(".add-list-item").show();
                $(".stocklist-content").show();
                $(".yellow.contents").text(list_name);
                $("#list-id").val(listID);
                getItems(listID);
            }
        });
    });

    // select list from dropdown
    $(".dropdown-list").change(function(e) {
        let $el = $(this).find('option:selected').text();
        $(".yellow.contents").text($el);
        let id = $(this).find('option:selected').attr('id');
        if (id) {
            listID = id;
            getItems(listID);
        }
        
        if ($(this).find('option:selected').hasClass('value')) {
            $(".add-list-item").show();
            $("#list-id").val(id);
            $(".stocklist-content").show();
        } else {
            $(".add-list-item").hide();
            $(".stocklist-content").hide();
        }
    });

    // item name autocomplete
    $("#item-name").autocomplete({
        global: false,
        source: base + 'Stocklists/searchItems/',
        minLength: 2,
        messages: {
            noResults: '',
        },
        select: (event, ui) => {
            $("#item-name").val(ui.item.value);
        }
    });

    // add item
    $(".btn-add-item").on('click', (e) => {
        e.preventDefault();
            listID =  $("#list-id").val();
            var url = base + "Stocklists/addItem/",
            data = $(".add-item").serialize();

        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            global: false,
            success: function(result) {
                toastr[result.notice](result.message);
                if(result.notice == "success") {
                    $('#item-name').val('');
                    table.ajax.reload();
                }
                updateTotal();
            }
        });
    });

    // remove item
    $(".table-items").on('click', 'a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            dataType: "json",
            url: url,
            global: false,
            success: function(result) {
                toastr[result.notice](result.message);
                if (result.notice == "success") {
                    url = 'Stocklists/getItems/' + listID;
                    table.ajax.reload();
                    updateTotal();
                }
            }
        });

    });

    // remove List
    $(".btn-delete-list-confirm").on('click', function(e) {
        e.preventDefault();
        var url = base + "Stocklists/removeList/" + listID;

        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                toastr[result.notice](result.message);
                if (result.notice == "success") {
                    $(".modal-close").trigger('click');
                    $(".add-list-item").hide();
                    $(".stocklist-content").hide();
                    $(".dropdown-list").val('0');
                    populateDropdown();
                }
            }
        });
    });
});