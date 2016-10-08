(function ($j) {
    switch_style = {
        onReady: function () {      
            this.switch_style_click();
        },

        switch_style_click: function(){
            $(".color").click(function(){
                var id = $(this).attr("id");

                $("#switch_style").attr("href", "assets/css/colors/" + id + ".css");
                $("#switch_style_component").attr("href", "../assets/css/component/colors/" + id + ".css");
                
                return false;
            });
        }
    };

    $j().ready(function () {
        switch_style.onReady();
    });
})(jQuery);


// EXPAND COLLAPSE

$(document).ready(function(){
    $('#toggle-switcher').click(function(){
        if($(this).hasClass('open')){
            $(this).removeClass('open');
            
            if($('#switch-style').hasClass('right')){
                $('#switch-style').animate({'right': '-210px'});
            }else{
                $('#switch-style').animate({'left': '-210px'});
            }
        }else{
            $(this).addClass('open');
            
            if($('#switch-style').hasClass('right')){
                $('#switch-style').animate({'right': '0'});
            }else{
                $('#switch-style').animate({'left': '0'});
            }
        }
    });
});