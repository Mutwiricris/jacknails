/*-----------------------------------------------------------------------------------
    Template Name: Elena - Multipurpose Ecommerce HTML Template
    Template URI: https://webtend.net/demo/html/elena/
    Author: WebTend
    Author URI:  https://webtend.net/
    Version: 1.0

    Note: This is Main JS File.
-----------------------------------------------------------------------------------
	JS INDEX
	===================
    ## Header Style
    ## Dropdown menu
    ## Submenu Dropdown
    ## Category List Submenu
    ## Search Box
    ## Video Popup
    ## Main Slider
    ## Slider style two
    ## Slider style Three
    ## Product Tab Slider
    ## Best Seller Product Slider
    ## Deal Of Day Slider
    ## New Brand Slider
    ## Popular Brand Slider
    ## Related Product Slider
    ## Related Product Two Slider
    ## Month Product Tab Slider
    ## Top Selling Product Slider
    ## Recent View Slider
    ## Client Logo
    ## Client Logo Two
    ## Product Preview Images
    ## Product thumb Images
    ## Testimonials Slider
    ## Feedback Slider
    ## Most Popular Products Slider
    ## Best Seller Products Slider
    ## Top Deal Products Slider
    ## Shop Category Slider
    ## Quantity Number js
    ## Product Details
    ## Products Filtering
    ## Price Range Fliter jQuery UI
    ## Count Down
    ## Scroll to Top
    ## Nice Select
    ## WOW Animation
    ## Preloader
-----------------------------------------------------------------------------------*/

(function ($) {

    "use strict";

    $(document).ready(function () {

        // ## Header Style and Scroll to Top
        function headerStyle() {
            if ($('.main-header').length) {
                var windowpos = $(window).scrollTop();
                var siteHeader = $('.main-header');
                var scrollLink = $('.scroll-top');
                if (windowpos >= 250) {
                    siteHeader.addClass('fixed-header');
                    scrollLink.fadeIn(300);
                } else {
                    siteHeader.removeClass('fixed-header');
                    scrollLink.fadeOut(300);
                }
            }
        }
        headerStyle();
        
        
        // ## Dropdown menu
        var mobileWidth = 992;
        var navcollapse = $('.navigation li.dropdown');

        navcollapse.hover(function () {
            if ($(window).innerWidth() >= mobileWidth) {
                $(this).children('ul').stop(true, false, true).slideToggle(300);
                $(this).children('.megamenu').stop(true, false, true).slideToggle(300);
            }
        });
        
        // ## Submenu Dropdown Toggle
        if ($('.main-header .navigation li.dropdown ul').length) {
            $('.main-header .navigation li.dropdown').append('<div class="dropdown-btn"><span class="fas fa-chevron-down"></span></div>');

            //Dropdown Button
            $('.main-header .navigation li.dropdown .dropdown-btn').on('click', function () {
                $(this).prev('ul').slideToggle(500);
                $(this).prev('.megamenu').slideToggle(800);
            });
            
            //Disable dropdown parent link
            $('.navigation li.dropdown > a').on('click', function (e) {
                e.preventDefault();
            });
        }
        
        //Submenu Dropdown Toggle
        if ($('.main-header .main-menu').length) {
            $('.main-header .main-menu .navbar-toggle').click(function () {
                $(this).prev().prev().next().next().children('li.dropdown').hide();
            });
        }
        
        
        // ## Category List Submenu
        if ($('.category-list li.dropdown ul').length) {
            $('.category-list li.dropdown').append('<div class="dropdown-btn"><span class="fas fa-chevron-down"></span></div>');

            //Dropdown Button
            $('.category-list li.dropdown .dropdown-btn').on('click', function () {
                $(this).prev('ul').slideToggle(500);
                $(this).toggleClass('active');
            });
            
            //Disable dropdown parent link
            $('.category-list li.dropdown > a').on('click', function (e) {
                e.preventDefault();
                $(this).next('ul').slideToggle(500);
                $(this).siblings('.dropdown-btn').toggleClass('active');
            });
        }
        
        
        // ## Search Box
		$('.nav-search > button').on('click', function () {
			$('.nav-search form').toggleClass('hide');
		});
        
        
        //  Hide Box Search WHEN CLICK OUTSIDE
		if ($(window).width() > 767){
			$('body').on('click', function (event) {
				if ($('.nav-search > button').has(event.target).length == 0 && !$('.nav-search > button').is(event.target)
					&& $('.nav-search form').has(event.target).length == 0 && !$('.nav-search form').is(event.target)) {
					if ($('.nav-search form').hasClass('hide') == false) {
						$('.nav-search form').toggleClass('hide');
					};
				}
			});
		}
        
        
        // ## Video Popup
        if ($('.video-play').length) {
            $('.video-play').magnificPopup({
                type: 'video',
                removalDelay: 260,
                mainClass: 'mfp-zoom-in',
            });
        } 
        
        
        // ## Main Slider
        if ($('.main-slider-active').length) {
            $('.main-slider-active').slick({
                infinite: true,
                arrows: true,
                dots: false,
                fade: true,
                autoplay: true,
                autoplaySpeed: 5000,
                pauseOnHover: false,
                slidesToScroll: 1,
                slidesToShow: 1,
                prevArrow: '<button class="slider-prev"><i class="fal fa-chevron-left"></i></button>',
                nextArrow: '<button class="slider-next"><i class="fal fa-chevron-right"></i></button>',
            });
        }
          
        
        // ## Slider style two
        if ($('.slider-slide-part').length) {
            $('.slider-slide-part').slick({
                infinite: true,
                arrows: false,
                dots: true,
                fade: true,
                autoplay: true,
                autoplaySpeed: 5000,
                pauseOnHover: false,
                slidesToScroll: 1,
                slidesToShow: 1,
            });
        }
        
        
        // ## Slider style Three
        if ($('.slider-three-active').length) {
            $('.slider-three-active').slick({
                infinite: true,
                arrows: false,
                dots: true,
                fade: true,
                autoplay: true,
                autoplaySpeed: 5000,
                pauseOnHover: false,
                slidesToScroll: 1,
                slidesToShow: 1,
            });
        }
        
        
        // ## Product Tab Slider
        if ($('.product-tab-slider.one').length) {
            $('.product-tab-slider.one').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 2,
                prevArrow: $('.products-tab-prev.one'),
                nextArrow: $('.products-tab-next.one'),
                focusOnSelect: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        if ($('.product-tab-slider.two').length) {
            $('.product-tab-slider.two').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 2,
                prevArrow: $('.products-tab-prev.two'),
                nextArrow: $('.products-tab-next.two'),
                focusOnSelect: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        if ($('.product-tab-slider.three').length) {
            $('.product-tab-slider.three').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 2,
                prevArrow: $('.products-tab-prev.three'),
                nextArrow: $('.products-tab-next.three'),
                focusOnSelect: false,
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
          $('.slick-slider').slick('setPosition');
        })
        
        
        // ## Best Seller Product Slider
        if ($('.best-seller-product-slider').length) {
            $('.best-seller-product-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                prevArrow: $('.best-seller-prev'),
                nextArrow: $('.best-seller-next'),
                focusOnSelect: false,
                slidesToShow: 1,
                slidesToScroll: 1,
            });
        }
          
         
        // ## Deal Of Day Slider
        if ($('.deal-of-day-slider').length) {
            $('.deal-of-day-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                prevArrow: $('.deal-of-day-prev'),
                nextArrow: $('.deal-of-day-next'),
                focusOnSelect: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
          

        // ## New Brand Slider
        if ($('.new-brand-slider').length) {
            $('.new-brand-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 3,
                prevArrow: $('.new-brand-prev'),
                nextArrow: $('.new-brand-next'),
                focusOnSelect: false,
                slidesToShow: 2,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
          
        
        // ## Popular Brand Slider
        if ($('.popular-brand-slider').length) {
            $('.popular-brand-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 3,
                prevArrow: $('.popular-brand-prev'),
                nextArrow: $('.popular-brand-next'),
                focusOnSelect: false,
                slidesToShow: 2,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
          
        
        
        // ## Related Product Slider
        if ($('.related-product-slider').length) {
            $('.related-product-slider').slick({
                dots: true,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: false,
                speed: 1000,
                focusOnSelect: false,
                slidesToShow: 4,
                slidesToScroll: 2,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }
                ]
            });
        }
        
        
        
        // ## Related Product Two Slider
        if ($('.related-product-two-slider').length) {
            $('.related-product-two-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                focusOnSelect: false,
                prevArrow: $('.related-product2-prev'),
                nextArrow: $('.related-product2-next'),
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Month Product Tab Slider
        if ($('.month-product-tab-slider.one').length) {
            $('.month-product-tab-slider.one').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 2,
                prevArrow: $('.month-product-tab-prev.one'),
                nextArrow: $('.month-product-tab-next.one'),
                focusOnSelect: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        if ($('.month-product-tab-slider.two').length) {
            $('.month-product-tab-slider.two').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 2,
                prevArrow: $('.month-product-tab-prev.two'),
                nextArrow: $('.month-product-tab-next.two'),
                focusOnSelect: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        if ($('.month-product-tab-slider.three').length) {
            $('.month-product-tab-slider.three').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 2,
                prevArrow: $('.month-product-tab-prev.three'),
                nextArrow: $('.month-product-tab-next.three'),
                focusOnSelect: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        if ($('.month-product-tab-slider.four').length) {
            $('.month-product-tab-slider.four').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 2,
                prevArrow: $('.month-product-tab-prev.four'),
                nextArrow: $('.month-product-tab-next.four'),
                focusOnSelect: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        if ($('.month-product-tab-slider.five').length) {
            $('.month-product-tab-slider.five').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 2,
                prevArrow: $('.month-product-tab-prev.five'),
                nextArrow: $('.month-product-tab-next.five'),
                focusOnSelect: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Top Selling Product Slider
        if ($('.top-selling-product-slider').length) {
            $('.top-selling-product-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                prevArrow: $('.top-selling-product-prev'),
                nextArrow: $('.top-selling-product-next'),
                focusOnSelect: false,
                slidesToShow: 6,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 5,
                        }
                    },
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Recent View Slider
        if ($('.recent-view-slider').length) {
            $('.recent-view-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                prevArrow: $('.recent-view-prev'),
                nextArrow: $('.recent-view-next'),
                focusOnSelect: false,
                slidesToShow: 7,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 6,
                        }
                    },
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 5,
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Client Logo
        if ($('.client-logo-wrap').length) {
            $('.client-logo-wrap').slick({
                dots: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 5000,
                arrows: false,
                centerMode: false,
                speed: 1000,
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 375,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Client Logo Two
        if ($('.client-logo-two-wrap').length) {
            $('.client-logo-two-wrap').slick({
                dots: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 5000,
                arrows: false,
                centerMode: false,
                speed: 1000,
                slidesToShow: 6,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 5,
                        }
                    },
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 375,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Product Preview Images
        if ($('.preview-images').length) {
            $('.preview-images').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: false,
                speed: 1000,
                focusOnSelect: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: '.thumb-images',
            });
        }
        
        
        // ## Product thumb Images
        if ($('.thumb-images').length) {
            $('.thumb-images').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                asNavFor: '.preview-images',
                prevArrow: '<button class="product-thumb-prev"><i class="far fa-chevron-left"></i></button>',
                nextArrow: '<button class="product-thumb-next"><i class="far fa-chevron-right"></i></button>',
                focusOnSelect: true,
                slidesToShow: 2,
                slidesToScroll: 1,
            });
        }
        
         
        // ## Testimonials Slider
        if ($('.testimonials-active').length) {
            $('.testimonials-active').slick({
                dots: true,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: false,
                speed: 1000,
                focusOnSelect: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Feedback Slider
        if ($('.feedback-active').length) {
            $('.feedback-active').slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: true,
                speed: 400,
                autoplay: false,
                autoplaySpeed: 5000,
                prevArrow: '<button class="feedback-prev"><i class="fal fa-chevron-double-left"></i></button>',
                nextArrow: '<button class="feedback-next"><i class="fal fa-chevron-double-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Most Popular Products Slider
        if ($('.most-popular-products-slider').length) {
            $('.most-popular-products-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 4,
                prevArrow: $('.most-popular-prev'),
                nextArrow: $('.most-popular-next'),
                focusOnSelect: false,
                slidesToShow: 1,
                slidesToScroll: 1,
            });
        }
        
        
        // ## Best Seller Products Slider
        if ($('.best-seller-products-slider').length) {
            $('.best-seller-products-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 4,
                prevArrow: $('.best-seller-prev'),
                nextArrow: $('.best-seller-next'),
                focusOnSelect: false,
                slidesToShow: 1,
                slidesToScroll: 1,
            });
        }
        
        
        
        // ## Top Deal Products Slider
        if ($('.top-deal-products-slider').length) {
            $('.top-deal-products-slider').slick({
                dots: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 2000,
                arrows: true,
                speed: 1000,
                rows: 4,
                prevArrow: $('.top-deal-prev'),
                nextArrow: $('.top-deal-next'),
                focusOnSelect: false,
                slidesToShow: 1,
                slidesToScroll: 1,
            });
        }
        
        
        
        // ## Shop Category Slider
        if ($('.shop-category-active').length) {
            $('.shop-category-active').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: true,
                speed: 400,
                autoplay: false,
                autoplaySpeed: 5000,
                prevArrow: '<button class="shop-category-prev"><i class="far fa-chevron-left"></i></button>',
                nextArrow: '<button class="shop-category-next"><i class="far fa-chevron-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            });
        }
        
        
        // ## Quantity Number js
        $('.quantity-down').on('click', function(){
            var numProduct = Number($(this).next().val());
            if(numProduct > 1) $(this).next().val(numProduct - 1);
        });
        $('.quantity-up').on('click', function(){
            var numProduct = Number($(this).prev().val());
            $(this).prev().val(numProduct + 1);
        });
        
        
        // ## Product Details
        $('.add-wishlist.popup').magnificPopup({
            type: 'image',
            mainClass: 'mfp-with-zoom', 
            gallery: {
                enabled:true
            },
        });
        
        
        // ## Products Filtering
        $(".products-filter li").on('click', function () {
            $(".products-filter li").removeClass("current");
            $(this).addClass("current");

            var selector = $(this).attr('data-filter');
            $('.product-filter-active').imagesLoaded(function () {
                $(".product-filter-active").isotope({
                    itemSelector: '.item',
                    filter: selector,
                });
            });

        });
        
        
        // ## Price Range Fliter jQuery UI
        if ($('.price-slider-range').length) {
            $(".price-slider-range").slider({
                range: true,
                min: 10,
                max: 3500,
                values: [500, 3000],
                slide: function (event, ui) {
                    $("#price").val("$ " + ui.values[0] + " - $ " + ui.values[1]);
                }
            });
            $("#price").val("$ " + $(".price-slider-range").slider("values", 0) +
                " - $ " + $(".price-slider-range").slider("values", 1));
        }
        
        
        // ## Count Down
        if ($('.count-down').length !== 0) {
            var $date = $('.count-down').attr('data-date');
            const second = 1000,
                minute = second * 60,
                hour = minute * 60,
                day = hour * 24;
            let countDown = new Date($date).getTime(),
                x = setInterval(function () {
                    let now = new Date().getTime(),
                        distance = countDown - now;
                        $(this).find('.days').innerText = Math.floor(distance / (day)),
                        $(this).find('.hours').innerText = Math.floor((distance % (day)) / (hour)),
                        $(this).find('.minutes').innerText = Math.floor((distance % (hour)) / (minute)),
                        $(this).find('.seconds').innerText = Math.floor((distance % (minute)) / second);
                    //do something later when date is reached
                    //if (distance < 0) {
                    //  clearInterval(x);
                    //  'IT'S MY BIRTHDAY!;
                    //}
                }, second)
        };

        
        // ## Scroll to Top
        if ($('.scroll-to-target').length) {
            $(".scroll-to-target").on('click', function () {
                var target = $(this).attr('data-target');
                // animate
                $('html, body').animate({
                    scrollTop: $(target).offset().top
                }, 1000);

            });
        }
        
        
        // ## Nice Select
        $('.select').niceSelect();
        
        
        // ## WOW Animation
        if ($('.wow').length) {
            var wow = new WOW({
                boxClass: 'wow', // animated element css class (default is wow)
                animateClass: 'animated', // animation css class (default is animated)
                offset: 0, // distance to the element when triggering the animation (default is 0)
                mobile: false, // trigger animations on mobile devices (default is true)
                live: true // act on asynchronously loaded content (default is true)
            });
            wow.init();
        }
        
        
 
    });
    
    
    /* ==========================================================================
       When document is resize, do
       ========================================================================== */

    $(window).on('resize', function () {
        var mobileWidth = 992;
        var navcollapse = $('.navigation li.dropdown');
        navcollapse.children('ul').hide();
        navcollapse.children('.megamenu').hide();

    });


    /* ==========================================================================
       When document is scroll, do
       ========================================================================== */

    $(window).on('scroll', function () {

        // Header Style and Scroll to Top
        function headerStyle() {
            if ($('.main-header').length) {
                var windowpos = $(window).scrollTop();
                var siteHeader = $('.main-header');
                var scrollLink = $('.scroll-top');
                if (windowpos >= 100) {
                    siteHeader.addClass('fixed-header');
                    scrollLink.fadeIn(300);
                } else {
                    siteHeader.removeClass('fixed-header');
                    scrollLink.fadeOut(300);
                }
            }
        }

        headerStyle();

    });

    /* ==========================================================================
       When document is loaded, do
       ========================================================================== */

    $(window).on('load', function () {

        // ## Preloader
        function handlePreloader() {
            if ($('.preloader').length) {
                $('.preloader').delay(200).fadeOut(500);
            }
        }
        handlePreloader();
        
        
        // ## Products Filtering
        if ($('.product-filter-active').length) {
            $(this).imagesLoaded(function () {
                $('.product-filter-active').isotope({
                    // options
                    itemSelector: '.item',
                });
            });
        }
        
        
    });

})(window.jQuery);
