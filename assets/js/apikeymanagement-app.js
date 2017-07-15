$(function() {
    $(".api-insert-2").hide();
    let apikey,
        vcode;
    list();

    function list() {
        const url = base + "Apikeymanagement/getCharacters/";
        $.ajax({
            dataType: "json",
            url: url,
            success: (result) => {
                $(".table-character-list tbody tr").empty();
                if (result.length === 0) {
                    const $row = "<tr><td colspan='3' class='text-center'>No characters found</td></tr>";
                    $(".table-character-list tbody tr").prepend($row);
                } else {
                    $.each(result, function(k, v) {
                        const id = result[k].charid;
                        const img = "<img src='https://image.eveonline.com/Character/" + id + "_32.jpg'></img>"; 
                        const $row = "<tr><td>" + img + " " + result[k].name + "</td><td>" + result[k].api + 
                               "</td><td><button class='btn btn-danger btn-delete' data-iddel=" + id + 
                               " data-toggle='modal' data-target='#delete'>Remove</button></tr></tr>";
                        $(".table-character-list").prepend($row);
                    });
                }
            }
        });
    }

    // start delete
    $("table").on('click', 'button', function() {
        const id = $(this).data('iddel');
        const url = base + 'Apikeymanagement/removeCharacter/' + id;
        $(".btn-delete-confirm").attr('data-url', url);
    });

    // confirm delete
    $(".btn-delete-confirm").on('click', function() {
        const url = $(this).attr('data-url');
        $.ajax({
            dataType: "json",
            url: url,
            success: (result) => {
                $('#delete').modal('toggle');
                toastr[result.notice](result.message);
                list();
            }
        });
    });

    //submit a new key
    $(".submit-add").on('click', function(e) {
        apikey = $("#keyid").val();
        vcode = $("#vcode").val();
        e.preventDefault();

        const url = base + "Apikeymanagement/addCharacters/";
        const data = $(".add-apikey").serialize();

        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: (result) => {
                if (typeof result.notice != 'undefined') {
                    toastr[result.notice](result.message);
                } else {
                    $(".api-insert-1").toggle();
                    $(".api-insert-2").toggle();
                        
                    let count = 1;
                    $.each(result, (k, v) => {
                        const id = result[k][1].id;
                        const name = result[k][0].name;
                        const url = "https://image.eveonline.com/Character/" + id + "_32.jpg";
                        const cl = "character" + count;
                        const $element = "<tr><td><img src='" + url + "'alt='icon'></img>" + " " + name + "</td>"+
                                       "<td><input type='checkbox' class='" + cl + "' data-id='" + id + "'></td></tr>";
                        count++;
                        $(".table-character-selection tbody").append($element);
                    }); 
                }  
            }
        });
    });

    // submit characters
    $(".submit-add-2").on('click', (e) => {
        e.preventDefault();

        let selected = [];
        $(".character1").is(':checked') ? selected.push($(".character1").attr('data-id')) : "";
        $(".character2").is(':checked') ? selected.push($(".character2").attr('data-id')) : "";
        $(".character3").is(':checked') ? selected.push($(".character3").attr('data-id')) : "";
        
        const args = selected.join('/');
        const url = base + "Apikeymanagement/addCharactersStep/" + apikey + "/" + vcode + "/" + args;

        $.ajax({ 
            dataType: "json",
            url: url,
            success: function(result) {
                toastr[result.notice](result.message);
                $('.api-insert-2').hide();
                $('.api-insert-1').show();
                $('#keyid').val('');
                $('#vcode').val('');
                list();
            }
        });
    });
});