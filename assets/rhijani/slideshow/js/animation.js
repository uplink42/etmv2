$(function(){
    
    var ua = navigator.userAgent.toLowerCase(),
    isAndroid = ua.indexOf("android") > -1;
    
    // Only animate elements when using non-mobile devices    
    if (jQuery.browser.mobile === false && !isAndroid) 
    {
        /*---------------------------------------*/
        /*  INTRO SECTION
        /*---------------------------------------*/
        /*$('#intro').find('.intro-content').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated bounceIn').css('opacity', 1);}
        });*/
        
        
        /*---------------------------------------*/
        /*  WHO WE ARE SECTION
        /*---------------------------------------*/
        $('#carousel-who-we-are').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated fadeInUp delayp1').css('opacity', 1);}
        });
        
        $('#who-we-are').find('.who-we-are-text').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated fadeInUp delayp3').css('opacity', 1);}
        });
        
        
        /*---------------------------------------*/
        /*  OUR MAIN SKILLS SECTION
        /*---------------------------------------*/
        $('#our-main-skills').find('.bar-chart-text').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated flipInX delayp1').css('opacity', 1);}
        });
        
        
        /*---------------------------------------*/
        /*  QUOTE SECTION
        /*---------------------------------------*/
        $('#quote .quote-text').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated fadeInUp delayp1').css('opacity', 1);}
        });
        
        
        /*---------------------------------------*/
        /*  KEEP IN TOUCH SECTION
        /*---------------------------------------*/
        /*$('#keep-in-touch').find('.social-icon-item').each(function(i){            
            var element = $(this),
            itemsDelay   = ( isNaN($(this).data('animation-delay')) ? 50 : $(this).data('animation-delay'));
            element.css('opacity', 0).one('inview', function(isInView) {
                if (isInView){
                    setTimeout(function(){
                        element.addClass('animated bounceIn').css('opacity', 1);
                    } , itemsDelay * (i * 2));
                }
            });
        });*/
        
        
        /*---------------------------------------*/
        /*  MEET THE TEAM SECTION
        /*---------------------------------------*/
        $('#meet-the-team').find('.team-element').each(function(i){            
            var element = $(this),
            itemsDelay   = ( isNaN($(this).data('animation-delay')) ? 50 : $(this).data('animation-delay'));
            element.css('opacity', 0).one('inview', function(isInView) {
                if (isInView){
                    setTimeout(function(){
                        element.addClass('animated fadeInUp').css('opacity', 1);
                    } , itemsDelay * (i * 2));
                }
            });
        });
        
        
        /*---------------------------------------*/
        /*  TESTIMONIALS SECTION
        /*---------------------------------------*/
        $('#testimonials').find('.testimonial-item').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated flipInY').css('opacity', 1);}
        })
        
       
        /*---------------------------------------*/
        /*  COMING SOON SECTION
        /*---------------------------------------*/        
        /*$('#coming-soon').find('.countdown-form').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated fadeInUp delayp3').css('opacity', 1);}
        });
        
        $('#coming-soon').find('.countdown-text').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated fadeInUp delayp5').css('opacity', 1);}
        });*/
        
       
        /*---------------------------------------*/
        /*  CONTACT US SECTION
        /*---------------------------------------*/
        /*$('#contact-us').find('.contact-us-form').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated fadeInUp delayp1').css('opacity', 1);}
        });
        
        $('#contact-us').find('.social-icon-text').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated fadeInUp delayp3').css('opacity', 1);}
        });*/
        
        
        /*---------------------------------------*/
        /*  MAP
        /*---------------------------------------*/        
        /*$('#cd-google-map').css('opacity', 0).one('inview', function(isInView){
            if (isInView) {$(this).addClass('animated flipInX').css('opacity', 1);}
        });*/
        
       
        /*---------------------------------------*/
        /*  FOOTER
        /*---------------------------------------*/
        /*$('#footer').find('.footer-column').each(function(i){            
            var element = $(this),
            itemsDelay   = ( isNaN($(this).data('animation-delay')) ? 50 : $(this).data('animation-delay'));
            element.css('opacity', 0).one('inview', function(isInView) {
                if (isInView){
                    setTimeout(function(){
                        element.addClass('animated fadeInUp').css('opacity', 1);
                    } , itemsDelay * (i * 2));
                }
            });
        });*/
    }
});