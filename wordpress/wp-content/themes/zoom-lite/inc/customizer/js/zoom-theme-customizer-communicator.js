/**
 * Customizer Previewer Communicator
 * Copyright 2017 GhozyLab
 * Author: Ghozy
 */
( function ( wp, $ ) {
	"use strict";

	// Bail if the customizer isn't initialized
	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize, OldPreview;

	// Custom Customizer Preview class (attached to the Customize API)
	api.myCustomizerPreview = {
		// Init
		init: function () {
			var self = this, parentID, $document = $( document ), is_home = zoom_comm.is_home, isMeta = zoom_comm.meta_sett, multiMenu = zoom_comm.multiplemenu, editLang = zoom_comm.edit, is_rtl = zoom_comm.is_rtl, is_ie8 = ( $.browser.msie && parseFloat($.browser.version) < 10 ? 'not-ready-edit' : 'ready-edit' );

			this.preview.bind( 'active', function() {

				// Edit Button click event
				$document.on( 'click', '.goto, .previewer-edit, .previewer-each-action', function(e) {

					var onClick = $(this);
					
					if ( onClick.hasClass('click-event-only')) {
						
						var popUp;
						parentID = onClick.data('unique-parent');
						
						if ( onClick.hasClass('previewer-edit')) {
							
							popUp = $('.submenu-parent-'+parentID);
							
							popUp.fadeIn(400);
							rePosition($('.submenu-parent-'+parentID), null, null);
						
						} else {
							
							popUp = $('.submenu-parent-'+onClick.data('unique-self'));
							
							$('.child-only').not(popUp).slideUp('fast', function() {
								refreshIcon(null);
							});
							
							popUp.slideToggle('slow', function() {
								if ($(this).is(':visible')) refreshIcon(popUp);
								rePosition(popUp, null, null);
							});
						}
						
					return false;	

					} else {
	
						// Only for Widget  
						var widgetSection = $(this).closest('aside');
		
						// Get information if the element is widget
						if ($(this).attr('data-type') == 'widget' && ! widgetSection.attr('data-section')) {
							// If not our default widget
							self.preview.send('focus-widget-control', widgetSection.prop('id'));
						}
						else if ($(this).attr('data-type') == 'widget' && widgetSection.attr('data-section')) {
							// if widget empty ( only has our default widget )
							$(this).data({'type':'section', 'target': widgetSection.attr('data-section')});
							self.preview.send('zoom-preview-event', $(this).data());
						}
						else {
							// Another control / section
							self.preview.send('zoom-preview-event', $(this).data());
							}
		
						e.stopPropagation();
					
					}
						
				});
				
				self.preview.send('zoom-preview-event', 'isReady');

			}); // Active Function END
			
			self.preview.bind( 'zoom-lite-previewer-event', function( data ) {
				
				if (data == 'isReady') zoom_start_render_button();
				
			});

			//-------------------------------------------------------All Functions-----------------------------------------------------------------
			
			function zoom_start_render_button() {
				
				// Clean up all to prevent duplicate
				$('.previewer-edit-cont', $document).remove();
				$('.ready-edit', $document).removeClass('ready-edit');
				
				// Show Edit button on element that has .ready-edit class
				$document.on( 'mouseenter mouseleave', '.ready-edit', function(e) {
					if (e.type == 'mouseenter'){
						$(this).find('.previewer-edit').fadeIn(300).css('display', 'inline-block');
						$(this).attr('data-curcol', $(this).css('backgroundColor')).css('background-color','rgba(0, 166, 0, 0.1)');
					}
					if (e.type == 'mouseleave'){
						$(this).css('background-color',$(this).attr('data-curcol'));
						refreshIcon(null);
					}
				});
				
				$document.on( 'mouseenter', '.previewer-edit', function(e) {
					$('.previewer-edit-submenu-cont, .previewer-edit').not($(this).parent().children()).fadeOut(300);
				});
				
				$(document).on("click",function() {
					$('.previewer-edit-submenu-cont, .previewer-edit').fadeOut(300);
				});

				//-------------------------------------------------------Render Button-----------------------------------------------------------------
				
				// Header Area
				zoom_render_button($('.image-title, #slider, .image-header-container, .logo-title-mode, .rev_slider_wrapper'), 'control', 'header_type_control', 'cvr');
				
				// ==Title & Tagline
				zoom_render_button($('.site-title'), 'control', 'blogname');
				zoom_render_button($('.site-description'), 'control', 'blogdescription');
	
				// Menu
				zoom_render_button($('.zoom-main-menu').not(':has(.no-menu)'), 'control', '["menu"]');
				zoom_render_button($('.menu-search-cont'), 'control', '["menusearch"]', 'cvr');
				
				// Multiple Target
				zoom_render_button($('.custom-home-button'), 'control', '["homebutton"]', 'tl');
				
				// Entry Meta
				try {
					var isAvMeta = false,
					metaItems = $.parseJSON(isMeta),
					postMeta = ['meta_author', 'meta_date', 'meta_cat', 'meta_tags', 'meta_comments'];
					
					// remove meta_author from frontpage (blog posts type)
					if ( zoom_opt.is_home ) postMeta = postMeta.filter(function(item) {
						return item !== 'meta_author'
					});
						
					$.each(postMeta, function (key, val) {
						if ($.inArray(val, metaItems) >= 0 ) isAvMeta = true;
					});

					if (isAvMeta) zoom_render_button($('.entry-meta'), 'control', '["postmeta"]');

				} catch(err) {}
					
				// Breadcrumb
				zoom_render_button($('.breadcrumb'), 'control', '["breadcrumb"]', 'cvr');
				
				// Entry Content
				zoom_render_button($('article:not(.sticky) .entry-content'), 'control', 'content_bg_color_control');
				zoom_render_button($('.related-post-cont'), 'control', '["relatedpost"]');
				zoom_render_button($('h4.rp-heading'), 'control', 'misc_txt_rp_control', 'tl');
								
				// Sticky Post
				zoom_render_button($('.sticky .entry-content'), 'section', 'site_post_sticky_section');
				zoom_render_button($('.ribbon-par'), 'control', 'sticky_ribbon_control', 'tr');
				
				// Author Box
				zoom_render_button($('#authorbox'), 'control', '["authorbox"]');
				
				// All widgets ( sidebar & footer )
				zoom_render_button($('aside.widget'), 'widget', 'widget');
				
				// ==Sidebar Search
				zoom_render_button($('.before-sidebar-holder'), 'section', 'zoom_theme_site_sidebar');	
				
				// Footer Area
				zoom_render_button($('#footer-container'), 'section', 'zoom_site_footer_layout', 'tl');
				
				// Top Bar Area
				zoom_render_button($('.top-bar-holder'), 'control', '["topbar"]', 'tl');
				zoom_render_button($('.top-bar-email'), 'control', 'top_bar_email_control');
				zoom_render_button($('.top-bar-works'), 'control', 'top_bar_w_hours_control');
				zoom_render_button($('.sosmed-wrap'), 'control', 'top_bar_sos_facebook_control');
				
				// Bottom Bar Area
				zoom_render_button($('.bottom-bar-content'), 'control', '["bottombar"]');
				
				// Logo
				zoom_render_button($('.site-logo, .menu-logo'), 'control', 'custom_logo');
				zoom_render_button($('.bottom-bar-logo'), 'control', 'bottom_logo_control');
				
				// Read More Button
				zoom_render_button($('.more-link-p'), 'control', '["readmore"]', 'tl');
				
				// Navigation Button
				zoom_render_button($('.paging-navigation, #image-navigation, .comment-navigation'), 'control', 'button_nav_control', 'tch');
				
				// Single Post
				zoom_render_button($('.post-navigation'), 'control', '["nextpost"]');
				
				// Comments
				zoom_render_button($('.comment-form-comment'), 'control', 'post_disable_all_comment_form_control');
				zoom_render_button($('p.nocomments'), 'control', 'post_hide_disable_comments_note_control');
				zoom_render_button($('.ribbon-wrapper-blue'), 'control', 'post_author_badge_color_control');				
				
				// Media
				$('article:not(.sticky) .entry-header, .sticky .entry-header-cont').each(function(){
					
					var optType;
					// Don't excecute if no image
					if ($(this).find('img').hasClass('wp-post-image')) {

						if( $(this).closest('.post-mode-list, .post-mode-grid').length > 0 ) {
							
							optType = '["featureimg"]';
							
							if ($(this).find('img').hasClass('no-featured-image')) {
								optType = '["featureimgthumb"]';
							}
							
						}
						if( ! $(this).closest('.post-mode-list, .post-mode-grid').length > 0 ) {
							optType = '["featureimgsingle"]';
						}
						
						zoom_render_button($(this), 'control', optType, 'cvh');
					
					}
					
				});

				//-------------------------------------------------------Render Button End-------------------------------------------------------------		
				
			}
			
			// Check if Absolute element overflow in Height
			function isOverflowHeight(el) {
				
				var siteOffset = $('.zoom-site').outerHeight(true);
				var elHeight = el.outerHeight(true);
				var elementOffset = el.offset().top;
				var finalOffset = parseInt(elementOffset + elHeight);
				
				if ( finalOffset > siteOffset ) return parseInt(finalOffset - siteOffset) + 30;
					
			}
			
			// Check if Absolute element overflow in Width
			function isOverflowWidth() {
				
				var firstElOffset = parseInt($('.submenu-parent-'+parentID).offset().left);
				var firstElOuterW = parseInt($('.submenu-parent-'+parentID).outerWidth(true));
				var siteWidth = $('.zoom-site').outerWidth(true);
				var rtlMode = parseInt((siteWidth - (firstElOffset + firstElOuterW)));
				var maxWidth = 0;
				
				if (is_rtl) firstElOffset = rtlMode;
	
				$('.previewer-edit-submenu-cont:visible').each(function() {
					maxWidth += $(this).width();
				});
				
				var finalWidth = parseInt(maxWidth + firstElOffset);
				
				if ( finalWidth > siteWidth ) return parseInt(finalWidth - siteWidth);
				
			}
			
			// Reposition an Absolute Position
			function rePosition(el, parent, pos) {
				
				var parent = parent, pos = pos, btn, el = el;
				
				if (pos != null) {

					btn = el.children('.previewer-edit');

					switch(pos) {
						// Center Vertical Horizontal
						case 'cvh':
							el.css("top", ((parent.outerHeight() - btn.outerHeight()) / 2) + "px");
							el.css("left", ((parent.outerWidth() - btn.outerWidth()) / 2) + "px");
						break;
						// The Left position for Top Center Horizontal & Bottom Center Horizontal || We use CSS for the Top or Bottom position
						case 'tch':
						case 'bch':
							el.css("left", ((parent.outerWidth() - btn.outerWidth()) / 2) + "px");
						break;
						// The Top position for Center vertical in left & right position || We use CSS for the Right or Left position
						case 'cvl':
						case 'cvr':
							el.css("top", ((parent.outerHeight() - btn.outerHeight()) / 2) + "px");
						break;
						
						default:
					
					}
					// Repositioning submenu (if available)
					childReposition();

				} else {
					
					var sender = el;
					// isOverflowWidth & isOverflowHeight functions used to move element if overflow the viewport
					var oflwWidth = isOverflowWidth();
					var oflwBottom = isOverflowHeight(el);
					var animDirection = (is_rtl ? [{right: 'auto'},{left : '100%'}] : [{left: 'auto'},{right : '100%'}]);
					
					// Left/Right Position Correction
					if (oflwWidth) sender.css(animDirection[0]).animate(animDirection[1], 300 );
						
					// Top Position Correction
					if ($.isNumeric(oflwBottom)) sender.animate({top: '-'+oflwBottom+'px'}, 300 );
					
					return;
				
				}
				
				// Repositioning Child Menu
				function childReposition() {
					
					if(el.hasClass('has-child')) {
						
						el.next().css({"top": el.css('top'), "marginTop": el.outerHeight()});
						el.next().css("left", el.css('left'));
						
					}
					
				}
				
			}
			
			// Change Icon State
			function refreshIcon(el) {
				
				var $theArrow = $('.submenu-arrow'),
				arrowIs = (is_rtl ? 'left' : 'right'),
				arrDown = 'dashicons dashicons-arrow-down',
				arrRight = 'dashicons dashicons-arrow-'+arrowIs+'',
				defaulClass = 'dashicons dashicons-arrow-down dashicons-arrow-'+arrowIs+'';
					
				if (el != null) return el.prev().removeClass(defaulClass).addClass(arrDown);
				
				else return $theArrow.removeClass(defaulClass).addClass(arrRight);
					
			}
			
			// Generate unique ID
			function makeid() {
				
				var text = "";
				var possible = "abcdefghijklmnopqrstuvwxyz0123456789";
				
				for (var i = 0; i < 7; i++)
				text += possible.charAt(Math.floor(Math.random() * possible.length));
				
				return text;
				
			}
			
			// Append EDIT button to target
			function zoom_render_button(elm, type, target, pos, markup, cclass) {
				
				elm.each(function() {
					
					var el = $(this);
					
					// Add class to target
					el.addClass(is_ie8);
	
					// Button Position, default is tr ( TOP RIGHT )
					if (! pos) pos = 'tr';
					
					// Append markup only for absolute position target
					if (markup) {
						el.wrap('<div class="'+markup+'"></div>');
						el = $('.'+markup);
					}
					
					// Create EDIT Button parent container including submenu
					var menuCont = $('<div/>').addClass('previewer-edit-cont '+pos+'').appendTo(el);
					
					// Custom Class for parent container
					if (cclass) menuCont.addClass(cclass);
					
					// Create & Insert GREEN Edit button and append to main container
					var editButton = $('<div/>')
					.attr({
						'data-type': type,
						'data-target': target,
						'class': 'previewer-edit'
					})
					.append('<span class="dashicons dashicons-edit"></span><span>'+editLang+'</span>').appendTo(menuCont);
					
					// Checking if there are submenu in Green Edit button on clicked
					try {
						
						// Get submenu in JSON format
						// The JSON data generated from /inc/customizer/customizer-functions.php ( zoom_customize_multiple_menu_generator() )
						var storedArray = $.parseJSON(target);
						var eachAction = $.parseJSON(multiMenu);
						var arrow = (is_rtl ? 'left' : 'right');
						
						// Generate unique ID for parent container
						var uniqueID = makeid();
						
						// Create submenu container
						// Let's store every single element in variable so it will makes easy to hook each element in the future
						var subMenuCont = $('<div/>').addClass('previewer-edit-submenu-cont');
						var subMenu = $('<div/>').addClass('previewer-edit-submenu').appendTo(subMenuCont);
				
						// Add additional data if GREEN Edit button has submenu
						editButton.addClass('has-child click-event-only').attr('data-unique-parent',uniqueID);
						subMenuCont.addClass('submenu-parent-'+uniqueID+'');
						
						// Generate submenu item list
						$.each(eachAction[storedArray], function (key, val) {
							
							// remove meta_author from frontpage (blog posts type)
							if ( storedArray == 'postmeta' && zoom_opt.is_home && key == 'meta_author') return true;
								
							var valueClue = val.value, iconClue = val.icon;
								
							// Creating Submenu Items Markup and append it to subMenu element
							var listItem = $('<div/>')
								.html('<span class="mn-title">'+val.title+'</span>')
								.attr({
									'data-type': val.type,
									'data-target': val.target,
									'class': 'previewer-each-action'
									})
								.appendTo(subMenu);
								
							// Add attribute if auto change option is enable. This means no child submenu under this list item
							if ( valueClue ) listItem.attr('data-value', val.value);
							// Add icon ( Dashicons ) if set
							if ( iconClue ) listItem.prepend($('<span/>').addClass('has-left-icon edit-submenu-icon dashicons '+val.icon+''));
							
							// Now let's check if each list have the submenu
							if ( val.is_submenu ) { // Submenu Available
			
								// Generate unique ID for submenu
								var selfID = makeid();
								
								// Adding additional data to the item when this item has submenu
								listItem
								.addClass('child-has-child click-event-only')
								.attr({'data-unique-parent': uniqueID, 'data-unique-self': selfID})
								.append('<span class="submenu-arrow dashicons dashicons-arrow-'+arrow+'"></span>');
								
								// Now let's create the list submenu Container
								var anotherParentSub = $('<div/>').addClass('child-only previewer-edit-submenu-cont submenu-parent-'+selfID+'').attr('data-unique-parent', uniqueID).appendTo(listItem);
								var anotherSubMenu = $('<div/>').addClass('previewer-edit-submenu').appendTo(anotherParentSub);
								
								// Now let's create the submenu list
								$.each(val.submenu_items, function (ky, vl) {
									
									var subMenuItems = $('<div/>')
										.text(vl.title)
										.attr({
											'data-type': vl.type,
											'data-target': vl.target,
											'class': 'previewer-each-action'
										})
										.appendTo(anotherSubMenu);
											
									// Add attribute if auto change option is enable
									if ( vl.value ) subMenuItems.attr('data-value', vl.value);
									// Add icon ( Dashicons ) if set
									if ( vl.icon ) subMenuItems.prepend($('<span/>').addClass('edit-submenu-icon dashicons '+val.icon+''));
										
								});
	
							}
									
						});
						
						// Add submenu markup to main container
						menuCont.append(subMenuCont);
	
					} catch(err) {} // No submenu found!
					
					// Reposition Button relative to target after created
					if (pos) rePosition(menuCont, el, pos);
	
					return el;
				
				});
				
			} // Button Generator END
			

		} // Init - END
		
	}; // api.myCustomizerPreview END


	/**
	 * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
	 *
	 * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
	 */
	OldPreview = api.Preview;
	api.Preview = OldPreview.extend( {
		initialize: function( params, options ) {
			// Store a reference to the Preview
			api.myCustomizerPreview.preview = this;

			// Call the old Preview's initialize function
			OldPreview.prototype.initialize.call( this, params, options );
		}
	} );

	// Document ready
	$( function () {
		// Initialize our Preview
		api.myCustomizerPreview.init();
	} );
} )( window.wp, jQuery );