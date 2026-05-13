    // scroll to top
    $(window).on('scroll', function () {
        'use strict';
        if ($(this).scrollTop() != 0) {
            $('#toTop').fadeIn();
        } else {
            $('#toTop').fadeOut();
        }
    });
    $('#toTop').on('click', function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
    });

    // swiper
    var swiper = new Swiper('.swiper-container', {
    effect: 'coverflow',
    direction: 'horizontal',
    loop: true,
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',  
    coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 4,
        slideShadows : true,
    },
    pagination: {
        el: '.swiper-pagination', 
    },
    });
    