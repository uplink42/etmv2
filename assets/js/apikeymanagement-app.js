$(document).ready(function() {
    
    var base = $(".navbar").data('url');
    list();

    function list() {
        var url = base + "ApiKeyManagement/getCharacters/";
        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $("table tbody tr").empty();
                if (result.length == 0) {
                    $row = "<tr><td colspan='3' class='text-center'>No characters found</td></tr>";
                    $("table").prepend($row);
                } else {
                    $.each(result, function(k, v) {
                        var id = result[k].charid;
                        var img = "<img src='https://image.eveonline.com/Character/" + id + "_32.jpg'></img>"; 
                        $row = "<tr><td>" + img + " " + result[k].name + "</td><td>" + result[k].api + 
                            "</td><td><button class='btn btn-danger btn-delete' data-iddel=" + id + 
                            " data-toggle='modal' data-target='#delete'>Delete</button></tr></tr>";
                        $("table").prepend($row);
                    });
                }
            }
        });
    }

    $("table").on('click', 'button', function() {
        var id = $(this).data('iddel');
        var url = $("#delete").data('url');
        var full_url = url + "/" + id;

        $(".btn-delete-confirm").attr('data-url', full_url);

        console.log(full_url);
    });

    $(".btn-delete-confirm").on('click', function() {

        var url = $(this).attr('data-url');
           console.log(url);
           //console.log($(".btn-delete-confirm"));
        $.ajax({
            dataType: "json",
            url: url,
            success: function(result) {
                $('#delete').modal('toggle');
                toastr[result.notice](result.message);
                list();
                window.location.href = window.location.hostname;
            }
        });
    });
    


});