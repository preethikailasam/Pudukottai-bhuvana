( function( $ ) {

	// Allow real-time updating of the theme customizer

	// ----------------------------- All transport postMessage -------
	
	// Site Name
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).html( to );
		} );
	} );
	
	// Site Desc
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).html( to );
		} );
	} );
	
	// Posts Nav Next
	wp.customize( 'misc_txt_np', function( value ) {
		value.bind( function( to ) {
			$(".nav-next > a").contents().filter(function(){
				return this.nodeType == 3; 
				})[0].nodeValue = to;
		} );
	} );
	
	// Posts Nav Prev
	wp.customize( 'misc_txt_op', function( value ) {
		value.bind( function( to ) {
			$(".nav-previous > a").contents().filter(function(){
				return this.nodeType == 3; 
				})[0].nodeValue = to;
		} );
	} );
	
	// Nav Next
	wp.customize( 'misc_txt_next', function( value ) {
		value.bind( function( to ) {
			$(".next-image > a").contents().filter(function(){
				return this.nodeType == 3; 
				})[0].nodeValue = to;
		} );
	} );
	
	// Nav Prev
	wp.customize( 'misc_txt_prev', function( value ) {
		value.bind( function( to ) {
			$(".previous-image > a").contents().filter(function(){
				return this.nodeType == 3; 
				})[0].nodeValue = to;
		} );
	} );

	// Related Posts Note
	wp.customize( 'misc_txt_rp', function( value ) {
		value.bind( function( to ) {
			$(".rp-heading").contents().filter(function(){
				return this.nodeType == 3; 
				})[0].nodeValue = to;
		} );
	} );
	
	// Site Header Area Color
	wp.customize( 'site_header_color', function( value ) {
		value.bind( function( to ) {
			$('.site-header').css('background-color', to );
			// Adapt for Background border ( in Logo + Title MOde )
            $('.nav-before-header .logo-title-mode, .nav-after-header .logo-title-mode').css('border-color', to );
		} );
	} );
	
	// Site Title Color
	wp.customize( 'site_title_color', function( value ) {
		value.bind( function( to ) {
			$('h1.site-title').css('color', to );
		} );
	} );
	
	// Site Description Color
	wp.customize( 'site_desc_color', function( value ) {
		value.bind( function( to ) {
			$('h2.site-description').css('color', to );
		} );
	} );
	
	// Home Button Label
	wp.customize( 'menu_home_btn_txt', function( value ) {
		value.bind( function( to ) {
			var menuMain = $("#zoomnav > .menu > ul li.custom-home-button > a span");
			var menuMobile = $(".custom-home-button > a span");
			
			menuMain.contents().filter(function(){
				return this.nodeType == 3; 
				})[0].nodeValue = to;
				
			menuMobile.contents().filter(function(){
				return this.nodeType == 3; 
				})[0].nodeValue = to;
				
		} );
	} );
	
	// Body / Content Text Color
    wp.customize( 'content_text_color', function( value ) {
        value.bind( function( to ) {
            $('#content, .zoom-pag-note').css('color', to );
        });
    });
	
	// Entry Meta Text Color
    wp.customize( 'post_meta_col', function( value ) {
        value.bind( function( to ) {
            $('.entry-meta').css('color', to );
        });
    });	

	// Content Background Color
	wp.customize( 'content_bg_color', function( value ) {
		value.bind( function( to ) {
			$('#primary, .zoom-blog-default, .commentlist .comment-item:not(.is-author)').css('background-color', to );
		} );
	} );

	// Body Link Color
    wp.customize( 'link_color', function( value ) {
        value.bind( function( to ) {
            $('a:not(#zoomnav a, #secondary a, #secondary ul li a, .zoom-btn)').css('color', to );
        });
    });
	
	// Accents Color
    wp.customize( 'content_accent_col', function( value ) {
        value.bind( function( to ) {
            $('.zoom-blog-entry-content .entry-meta, #content .entry-meta, #content .site-navigation, .comment-navigation, .single .comments-title, #primary .zoom-blog-default, #authorbox, .share-buttons-cont, .zoom-page-pag, .commentlist .comment-item, .commentlist .perma-reply-edit').css('border-color', to );
			 $('.comment-meta hr').css('background-color', to );
        });
    });
	
	// Navigation Text
    wp.customize( 'site_nav_col', function( value ) {
        value.bind( function( to ) {
            $('.blue.zoom-btn, .turq.zoom-btn, .green.zoom-btn, .red.zoom-btn, .grey.zoom-btn, .purple.zoom-btn, .orange.zoom-btn, .pink.zoom-btn').css('color', to );
        });
    });
	
	 // Main Menu Text Color
    wp.customize( 'main_menu_txt', function( value ) {
        value.bind( function( to ) {
            $('#zoomnav a, #nav-toggle span, #zoom-mobile-nav a').css('color', to );
        });
    });
	
	// Main Menu
    wp.customize( 'main_menu_bg', function( value ) { // Main Menu Background Color
        value.bind( function( to ) {
			
            $('.zoom-menu-nav, .nav-holder, .menu-box, .menu-box-mobile, #zoomnav, #zoom-mobile-nav, #nav-toggle, .nav-holder .menu, .nav-holder .menu > ul > li:not(:only-child), .menu-mobile, .menu-mobile > ul > li:not(:only-child)').css('background-color', to );
			
			$('#zoomnav ul > li.current_page_item , #zoomnav ul > li.current-menu-item, #zoomnav ul > li.current_page_ancestor, #zoomnav ul > li.current-menu-ancestor, #zoom-mobile-nav ul > li.current_page_item , #zoom-mobile-nav ul > li.current-menu-item, #zoom-mobile-nav ul > li.current_page_ancestor, #zoom-mobile-nav ul > li.current-menu-ancestor').css('background-color',ColorLuminance(to, 0.1));
			
			$('.navborberonscroll').css('border-color',ColorLuminance(to, 0.2));
			
			
			// Update Submenu Background color dynamically on Hover
			
			var subMenuCol = wp.customize.value('sub_menu_bg')();
			
			 $('.nav-holder .menu > ul > li:not(:only-child), .menu-mobile > ul > li:not(:only-child)').hover(
			
				function() {
					
					$(this).css('background-color', subMenuCol);
					
				},
				function() {
					
					if ($(this).attr('class').indexOf('current')!== -1) {  	
						
						$(this).css('background-color', ColorLuminance(to, 0.1));
					
					} else {
						
						$(this).css('background-color', to);
						
					}
					
				}
			
			); // End hover

        });
    });
	
	// Sub Menu on Hover
    wp.customize( 'sub_menu_bg', function( value ) { // Submenu Background Color
        value.bind( function( to ) {
			
			$('.nav-holder .sub-menu li').css('background-color', to);
			
			// Update Submenu Background color dynamically on Hover
			$('.nav-holder .sub-menu li').hover(
			
				function() {
					
					$(this).css('background-color', ColorLuminance(to, 0.2));
					
				},
				function() {
					
					$(this).css('background-color', to);
					
				}
			
			);
			
			// Update Main menu Background color dynamically on Hover
			
			var defBgCol =  wp.customize.value('main_menu_bg')();
			
			$('.menu > ul > li:not(:only-child), .menu-mobile > ul > li:not(:only-child)').hover(
			
				function() {
					
					$(this).css('background-color', to);
					
				},
				function() {

					if ($(this).attr('class').indexOf('current')!== -1) {  	
						
						$(this).css('background-color', ColorLuminance(defBgCol, 0.1));
					
					} else {
						
						$(this).css('background-color', defBgCol);
						
					}
					
				}
				
			);
			
			
        });
    });

    wp.customize( 'sub_menu_txt', function( value ) { // Submenu Text Color
        value.bind( function( to ) {
            $('#zoomnav ul li:hover, #zoomnav ul ul li a, #zoom-mobile-nav ul li:hover, #zoom-mobile-nav ul ul li a').css('color', to );
        });
    });
	
	// Sidebar Background Color
    wp.customize( 'sidebar_bg', function( value ) {
        value.bind( function( to ) {
            $('#zoom-theme-main').css('background', to );
        });
    });
	
	// Sidebar Title Color
    wp.customize( 'sidebar_ttl_col', function( value ) {
        value.bind( function( to ) {
            $('.widget-title').css('color', to );
        });
    });	
	
	// Sidebar Link and Highlight Color
    /* wp.customize( 'sidebar_link_txt_col', function( value ) {
        value.bind( function( to ) {
            $('#secondary a, #tertiary a, .widget-area aside ul li a:visited, #secondary ul li, #tertiary ul li').css('color', to );
        });
    });	 */
	
	// Sidebar Text Color
    wp.customize( 'sidebar_txt_col', function( value ) {
        value.bind( function( to ) {
            $('.textwidget').css('color', to );
        });
    });
		
	// Sidebar Border Side Color
    wp.customize( 'sidebar_bor_col', function( value ) {
        value.bind( function( to ) {
            $('#primary').css('border-color', to );
        });
    });
	
	// Sidebar Border Side Color
    wp.customize( 'sidebar_bor_col', function( value ) {
        value.bind( function( to ) {
            $('.widget').css('border-color', ColorLuminance(to, 0.2) );
        });
    });

	// Sticky Background Color
    wp.customize( 'sticky_bg_col', function( value ) {
        value.bind( function( to ) {
            $('#primary .sticky').css('background', to );
        });
    });
	  
	// Sticky Border Color
    wp.customize( 'sticky_bor_col', function( value ) {
        value.bind( function( to ) {
            $('#primary .sticky').css('border-color', to );
        });
    });
	
	// Footer Background Color
    wp.customize( 'footer_bg', function( value ) {
        value.bind( function( to ) {
            $('#footer-container, .site-footer').css({'background':to, 'border-color': ColorLuminance(to, 0.2)});
        });
    });
	
	// Footer Title Color
    wp.customize( 'footer_ttl_col', function( value ) {
        value.bind( function( to ) {
            $('#footer-container .widget-title').css('color', ColorLuminance(to, 0.2) );
        });
    });

	// Footer Text Color
    wp.customize( 'footer_txt_col', function( value ) {
        value.bind( function( to ) {
            $('#footer-container, #footer-container .textwidget').css('color', to );
        });
    });
	  
	// Footer Link Color
    wp.customize( 'footer_link_txt_col', function( value ) {
        value.bind( function( to ) {
            $('#footer-container a, #footer-container ul li, #footer-container ul li').css('color', to );
        });
    });
	
	// Top Bar Background Color
    wp.customize( 'top_bar_bg', function( value ) {
        value.bind( function( to ) {
            $('#top-bar').css({'background':to, 'border-color': ColorLuminance(to, 0.2)});
        });
    });

	// Top Bar Text Color
    wp.customize( 'top_bar_txt_col', function( value ) {
        value.bind( function( to ) {
            $('#top-bar').css('color', to );
        });
    });
	  
	// Top Bar Link Color
    wp.customize( 'top_bar_link_txt_col', function( value ) {
        value.bind( function( to ) {
            $('#top-bar a, #top-bar ul li').css('color', to );
        });
    });
	
	// Bottom Bar Background Color
    wp.customize( 'bottom_bar_bg', function( value ) {
        value.bind( function( to ) {
            $('#bottom-bar').css({'background':to, 'border-color': ColorLuminance(to, 0.2)});
        });
    });

	// Bottom Bar Text Color
    wp.customize( 'bottom_bar_txt_col', function( value ) {
        value.bind( function( to ) {
            $('#bottom-bar').css('color', to );
        });
    });
	  
	// Bottom Bar Link Color
    wp.customize( 'bottom_bar_link_txt_col', function( value ) {
        value.bind( function( to ) {
            $('#bottom-bar a, #bottom-bar ul li, #bottom-bar ul li').css('color', to );
        });
    });
	
	// Bottom Copyright
    wp.customize( 'bottom_bar_copyright', function( value ) {
        value.bind( function( to ) {
            $('.zoom-bottom-copyright').html( to );
        });
    });	
	
	// Author Box Background Color
    wp.customize( 'author_box_bg', function( value ) {
        value.bind( function( to ) {
            $('#authorbox').css('background', to );
        });
    });
	
	// Author Box Text Color
    wp.customize( 'author_box_txt_col', function( value ) {
        value.bind( function( to ) {
            $('#authorbox h4, #authorbox p').css('color', to );
        });
    });
	
	// Comment Author Badge Color
	wp.customize( 'post_author_badge_color', function( value ) {
		value.bind( function( to ) {
			$('.bypostauthor .ribbon-blue').css('background-color', to );
		} );
	} );
	
	/* Elements */
	
	// #button
    wp.customize( 'button_readmore', function( value ) {
        value.bind( function( to ) {
			$('.more-link-p a.zoom-btn').attr('class', ''+to+' zoom-btn');
        });
    });
	
	// #button Read More Position
    wp.customize( 'button_readmore_pos', function( value ) {
        value.bind( function( to ) {
			$('.more-link-p').removeClass('btn-align-left btn-align-center btn-align-right').addClass('btn-align-'+to);
        });
    });
	
    wp.customize( 'button_nav', function( value ) {
        value.bind( function( to ) {
			
			$('.nav-previous a.zoom-btn, .nav-next a.zoom-btn, .next-image a.zoom-btn, .previous-image a.zoom-btn').attr('class', ''+to+' zoom-btn');
			$('.zoom-page-pag').attr('class', ''+to+' zoom-page-pag');

        });
    });
	
	// Slider Height
    wp.customize( 'header_slider_is_max_height', function( value ) {
		
        value.bind( function( to ) {
			
			var sliderMaxHeight = (undefined !==  wp.customize('header_slider_max_height')()) ?  parseInt(wp.customize('header_slider_max_height')())+'px' : '350px';
			
			if ( to ) {
				
				$('.zoom-slider-wrapper, .theme-default .nivoSlider img').css('max-height', sliderMaxHeight );
				
			} else {
				
				$('.zoom-slider-wrapper, .theme-default .nivoSlider img').css('max-height', '100%' );
				
			}

        });
    });


} )( jQuery );


// Set color brightness
function ColorLuminance(hex, lum) {
	
	// validate hex string
	hex = String(hex).replace(/[^0-9a-f]/gi, '');
	if (hex.length < 6) {
		hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
	}
	lum = lum || 0;
	
	// convert to decimal and change luminosity
	var rgb = "#", c, i;
	for (i = 0; i < 3; i++) {
		c = parseInt(hex.substr(i*2,2), 16);
		c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
		rgb += ("00"+c).substr(c.length);
	}
	
	return rgb;
	
}