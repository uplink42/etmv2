$(function(){
    $(window).load(function(){
        var $container = $('.popup-portfolio'); 
        
        var isotopePortfolio = function(filter){
            $container.isotope({ 
                filter: filter, 
                animationOptions: { 
                    duration: 750, 
                    easing: 'linear', 
                    queue: false
                },
                masonry: {
                    isFitWidth: true,
                    isResizable: true,
                    gutter: 15
                }
            }); 
        };
        
        isotopePortfolio('*');
        
        $('.filter-portfolio').find('a').click(function(e){ 
            e.preventDefault();
            
            $('.filter-portfolio').find('li').removeClass('active');
            
            $('.filter-portfolio').find('a').removeClass('disabled');
            
            $(this).parent('li').addClass('active');
            
            $(this).addClass('disabled');
            
            var selector = $(this).attr('data-filter'); 
            
            isotopePortfolio(selector);
        }); 
    });
    
    $('.popup-portfolio').magnificPopup({
        delegate: 'a',
        type: 'image',
        fixedContentPos: false,
        gallery: {
            enabled: true,
            preload: [0,2],
            navigateByImgClick: false,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            tPrev: 'Previous (Left arrow key)',
            tNext: 'Next (Right arrow key)'
        }
    });
});