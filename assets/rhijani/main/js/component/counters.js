$(function(){
    
    var ua = navigator.userAgent.toLowerCase(),
    isAndroid = ua.indexOf("android") > -1;
    
    if (jQuery.browser.mobile === false && !isAndroid){
        $('.counter-item').one('inview', function(isInView){
            if (isInView){
                $('.number').countTo();
            }
        });
    }
});