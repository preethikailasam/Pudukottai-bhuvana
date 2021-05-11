(function ($) {

	var matched, browser;

	$.uaMatch = function (ua) {
		ua = ua.toLowerCase();

		var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
			/(webkit)[ \/]([\w.]+)/.exec(ua) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
			/(msie)[\s?]([\w.]+)/.exec(ua) ||
			/(trident)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
			ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) || [];

		return {
			browser: match[1] || "",
			version: match[2] || "0"
		};
	};

	matched = $.uaMatch(navigator.userAgent);
	//IE 11+ fix (Trident) 
	matched.browser = matched.browser == 'trident' ? 'msie' : matched.browser;
	browser = {};

	if (matched.browser) {
		browser[matched.browser] = true;
		browser.version = matched.version;
	}

	// Chrome is Webkit, but Webkit is also Safari.
	if (browser.chrome) {
		browser.webkit = true;
	} else if (browser.webkit) {
		browser.safari = true;
	}

	$.browser = browser;

})(jQuery);

jQuery(document).ready(function ($) {

	/* For IE */
	var iever = ($.browser.msie ? parseFloat($.browser.version) : -1),
		sideBarW = zoom_opt.sidebar_width,
		winW = $(window).width(),
		isMenu = $('#zoomnav').find('#zoom_nav').length,
		ovrflwStatus = false;

	/* Standard menu touch support for tablets */
	var custom_event = ('ontouchstart' in window) ? 'touchstart' : 'click'; // check touch support 
	var ios = /iPhone|iPad|iPod/i.test(navigator.userAgent);
	var nav, navHomeY, boxN = $('.menu-box'),
		boxM = $('.menu-box-mobile');

	$(document).on('click', '#zoomnav .menu > ul > li a, #zoom-mobile-nav .menu-mobile > ul > li a', function () {

		var $link_id = $(this).attr('href');

		if ($(this).parent().data('clicked') == $link_id) { // second touch 

			$(this).parent().data('clicked', null);
		} else { // first touch 
			if (custom_event != 'click' && !ios && ($(this).parent().children('.sub-menu').length > 0)) {
				e.preventDefault();
			}
			$(this).parent().data('clicked', $link_id);
		}

	});


	/**-------------------------------------------------------------------------------------
	* Main Menu
	---------------------------------------------------------------------------------------*/

	$("#zoomnav ul ul, #zoom-mobile-nav ul ul").css({
		display: "none"
	}); /* Opera Fix */
	$("#zoomnav > .menu ul li > a:not(:only-child), #zoom-mobile-nav  > .menu-mobile ul li > a:not(:only-child)").attr("aria-haspopup", "true"); /* IE10 mobile Fix */

	$("#zoomnav li, #zoom-mobile-nav li").hover(function () {

			$(this).find('ul:first').stop();

			$(this).find('ul:first').css({
				opacity: "0",
				marginLeft: "50px"
			}).css({
				visibility: "visible",
				display: "block",
				overflow: "visible"
			}).animate({
				"opacity": "1",
				marginLeft: "-=50"
			}, {
				complete: function () {
					// Check if menu overflow
					isMenuOverflow();
				},
				queue: false
			});

		},
		function () {
			$(this).find('ul:first').css({
				visibility: "visible",
				display: "block",
				overflow: "visible"
			}).animate({
				marginLeft: "-=50"
			}, {
				complete: function () {
					$('#zoomnav li').find('ul.sub-menu:visible:first').parent().removeClass('menu-overflow');
				},
				queue: false
			}).fadeOut();


		});

	/**-------------------------------------------------------------------------------------
	* Check if menu overflow (BETA)
	---------------------------------------------------------------------------------------*/
	function isMenuOverflow() {

		var siteWidth = $('.zoom-site').outerWidth(true),
		firstSubMenu = $('#zoomnav li').find('ul.sub-menu:visible:first'),
		firstElOffset = firstSubMenu.offset() != undefined ? parseInt(firstSubMenu.offset().left) : 0,
		firstElOuterW = parseInt($('.sub-menu:visible:first').width()),
		maxWidth = 0;
		// RTL mode
		var rtlMode = parseInt((siteWidth - (firstElOffset + firstElOuterW)));
		if (zoom_opt.is_rtl) firstElOffset = rtlMode;
		// RTL - end

		// Get all visible sub-menu
		$('.sub-menu:visible').each(function () {
			maxWidth += $(this).width();
		});

		// Total width of all visible menus 
		var totalWidth = parseInt(maxWidth + firstElOffset);

		// Compare with site width and it's overflow
		if (totalWidth > siteWidth) {
			// Add overflow class
			firstSubMenu.parent().addClass('menu-overflow');
		} else {
			// remove overflow class to normal menu class
			firstSubMenu.parent().removeClass('menu-overflow');
		}

	}

	/**-------------------------------------------------------------------------------------
	* Mobile Navigation Menu
	---------------------------------------------------------------------------------------*/

	function zoom_reset_var() {

		if ($(window).width() <= 920) {
			nav = boxM;
			boxM.css('display', 'block');
			boxN.css('display', 'none');
		} else {
			nav = boxN;
			boxM.css('display', 'none');
			boxN.css('display', 'table');
		}

		if (nav.length == 0) {
			return;
		}

		navHomeY = $('#zoom-theme-main').offset().top;

	}

	zoom_reset_var();


	function zoom_mobile_menu_init() {

		var state = false;

		$("#nav-toggle").click(function () {
			$("#zoom-mobile-nav").slideToggle(
				function () {
					if (state) {
						$(this).removeAttr('style');
					};
					state = !state;
				}

			);
		});

	}

	// Mobile Navigation
	zoom_mobile_menu_init();

	// Stick the Main menu to the top of the window ( Desktop only )
	if (!zoom_opt.zoom_is_mobile && zoom_opt.floating_nav && (iever > 8 || iever == -1) && isMenu) {

		$(function () {

			// zoom_opt.zoom_is_mobile : We need to disable Floating Menu in real mobile view
			var $w = $(window);
			var isFixed = false;

			$w.scroll(function () {
				var scrollTop = $w.scrollTop();
				navHomeY = $('#zoom-theme-main').offset().top;
				var shouldBeFixed = scrollTop > navHomeY;

				if (shouldBeFixed && !isFixed) {
					nav.hide().fadeIn(1000).css({
						position: 'fixed',
						top: (zoom_opt.in_customizer == false && zoom_opt.zoom_is_adminbar == true && $w.width() > 480 ? 32 : 0),
						width: '100%'
					}).addClass('navborberonscroll');
					isFixed = true;
				} else if (!shouldBeFixed && isFixed) {
					nav.fadeOut(100).promise().done(function () {
						nav.css({
							position: 'relative',
							top: 0,
						}).removeClass('navborberonscroll');
						isFixed = false;
						this.fadeIn(1000);

					});
				}
			});

		});

	}

	$(window).resize(function () {

		zoom_reset_var();

	});


	/**-------------------------------------------------------------------------------------
	* Scroll to Top effect
	---------------------------------------------------------------------------------------*/

	if (zoom_opt.zoom_effect_stt == true) {

		$(function () {
			$.scrollUp({
				scrollName: 'scrollUp', // Element ID
				scrollSpeed: parseInt(zoom_opt.zoom_effect_stt_speed), // Speed back to top (ms)
				easingType: 'linear', // Scroll to top easing (see http://easings.net/)
				animation: 'fade', // Fade, slide, none
				animationSpeed: 200, // Animation speed (ms)
				scrollImg: true, // Set true to use image
				zIndex: 2147483645 // Z-Index for the overlay
			});
		});

	}


	/**-------------------------------------------------------------------------------------
	* Header Image Slider
	---------------------------------------------------------------------------------------*/

	if (zoom_opt.header_type == 'slider' && zoom_opt.slider_script) {

		$('.zoom-image-slider').nivoSlider({
			pauseTime: parseInt(zoom_opt.slider_intv),
			effect: zoom_opt.slider_effect,
			controlNav: false,
			afterLoad: function () {
				rePosNav();
			},
			beforeChange: function () {
				rePosNav();
			},
			afterChange: function () {
				rePosNav();
			},

		});

		// Re-position slider Nav
		function rePosNav() {

			if (zoom_opt.slider_max_h == true) {

				var parentHeight = $('.zoom-slider-wrapper').outerHeight();
				parentHeight = (parentHeight / 2) - 30;

				$('.nivo-directionNav a').css('top', parentHeight + 'px');

			} else {

				return;

			}

		}

	}


	/**-------------------------------------------------------------------------------------
	* CSS hack for IE < 9
	* This method because we cannot change it using inline CSS or media query :(
	---------------------------------------------------------------------------------------*/
	function zoom_fix_ie_display() {

		if (winW < 640 && iever < 9) {

			$('#primary, #secondary, #tertiary, .entry-header-cont, .entry-header').css({
				'display': 'table',
				'min-width': '100%',
				'width': '100%',
				'border': 'none',
			});

		}

	}

	// Fire on lte IE 10
	if ($.browser.msie) {
		zoom_fix_ie_display()
	}

	// Masonry
	if ($("#zoom-masonry-mode").length) {

		if ($('.zoom-blog-default').hasClass('sticky')) {

			$('.zoom-blog-default.sticky').each(function () {

				$(this).insertBefore($(this).parent());

			})

		}


		// JetPack Infinite Scroll
		if (zoom_opt.is_infinite_scroll == true) {

			// Triggers re-layout on infinite scroll
			$(document.body).on('post-load', function () {

				zoom_masonry();

			});

		} else {

			zoom_masonry();

		}

	}


	/**-------------------------------------------------------------------------------------
	* Blog Masonry Mode
	---------------------------------------------------------------------------------------*/
	function zoom_masonry() {

		var zoomMasonry = new Masonry('#zoom-masonry-mode', {
			columnWidth: '.zoom-blog-default:not(.sticky)',
			itemSelector: '.zoom-blog-default:not(.sticky)',
		});

		var imLoad = jQuery('#zoom-masonry-mode').imagesLoaded().always(function (instance) {

			setTimeout(function () {

				zoomMasonry.layout();

			}, 500);

		});

	}


	/**-------------------------------------------------------------------------------------
	* HASH ScrolltoUp
	---------------------------------------------------------------------------------------*/
	$(function () {
		// get hash value
		var hash = window.location.hash;

		if (hash && $(hash).offset() != undefined)
			// now scroll to element with that id to destination
			$('html,body').animate({
				scrollTop: $(hash).offset().top - 50
			}, 2000);

	});


}); // End Doc Ready

jQuery(window).on('load', function () {

	// Screen Preload
	if (zoom_opt.zoom_effect_preload == true) {

		jQuery("#zoom-preloader").css('background-color', '' + zoom_opt.zoom_effect_preload_bg + '');
		// will first fade out the loading animation	
		jQuery("#status").fadeOut(2000);
		// will fade out the whole DIV that covers the website.
		jQuery("#zoom-preloader").delay(1000).fadeOut(1000, function () {
			jQuery("#page").css('visibility', 'visible');
		});
	}

});