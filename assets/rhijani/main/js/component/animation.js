$(function(){
    
    var ua = navigator.userAgent.toLowerCase(),
    isAndroid = ua.indexOf("android") > -1;
                
    // Only animate elements when using non-mobile devices    
    if (jQuery.browser.mobile === false && !isAndroid) 
    {
      
        /*---------------------------------------*/
        /*  CONTENT BOXES
        /*---------------------------------------*/
        $('.content-boxes').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated flipInY delayp1').css('opacity', 1);}
        });
        
        
        /*---------------------------------------*/
        /*  COUNTERS
        /*---------------------------------------*/
        $('.counter-item').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated fadeInUp delayp1').css('opacity', 1);}
        });
        
        
        /*---------------------------------------*/
        /*  FUNNY BOXES
        /*---------------------------------------*/
        $('.funny-boxes-text').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated flipInY delayp1').css('opacity', 1);}
        });

        $('.donate').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated flipInY delayp1').css('opacity', 1);}
        });
        
        
        
        /*---------------------------------------*/
        /*  PORTFOLIO
        /*---------------------------------------*/
        /*$('.portfolio-item').find('.inner-content').each(function(i){            
            var element = $(this),
            itemsDelay   = ( isNaN($(this).data('animation-delay')) ? 50 : $(this).data('animation-delay'));
            element.css('opacity', 0).one('inview', function(isInView) {
                if (isInView){
                    setTimeout(function(){
                        element.addClass('animated bounceIn').css('opacity', 1);
                    } , itemsDelay * i);
                }
            });
        });*/
        
    }
});