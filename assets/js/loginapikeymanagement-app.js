$(function() {
	$(".api-insert-2").hide();
    let apikey;
    let vcode;

	$(".submit-add").on('click', function(e) {
		e.preventDefault();
        apikey = $("#keyid").val();
        vcode = $("#vcode").val();
        let url = base + "Apikeymanagement/addCharacters/";
        let data = $(".add-apikey").serialize();

        $.ajax({
            dataType: "json",
            url: url,
            data: data,
            type: "POST",
            success: (result) => {
                if (typeof(result.notice) != "undefined") {
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
                        const $element = "<tr><td class='char'><img src='" + url + "'alt='icon'></img>" + " " + name + "</td>"+
                                       "<td><input type='checkbox' class='" + cl + "' data-id='" + id + "'></td></tr>";
                        count++;
                        $(".table-character-selection tbody").append($element);
                    }); 
                }  
            }
        });
    });

    $(".submit-add-2").on('click', (e) => {
        e.preventDefault();

        var selected = [];
        $(".character1").is(':checked') ? selected.push($(".character1").attr('data-id')) : "";
        $(".character2").is(':checked') ? selected.push($(".character2").attr('data-id')) : "";
        $(".character3").is(':checked') ? selected.push($(".character3").attr('data-id')) : "";
        
        var args = selected.join('/');
        var url = base + "Apikeymanagement/addCharactersStep/" + apikey + "/" + vcode + "/" + args;

        $.ajax({ 
            dataType: "json",
            url: url,
            success: function(result) {
                toastr[result.notice](result.message);
                if(result.notice === 'success') {
                    window.location.href = 'main/login/logout';
                }
            }
        });
    });
});