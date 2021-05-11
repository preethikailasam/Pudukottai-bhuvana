wp.customize.controlConstructor['radio'] = wp.customize.Control.extend({
		
        /**
         * When the control's DOM structure is ready,
         * set up internal event bindings.
         */
        ready: function() {
			
            var control = this;
            // Shortcut so that we don't have to use _.bind every time we add a callback.
            _.bindAll( control, 'radioOnSelect', 'headerTypeInit', 'colorScheme' );

			var value = wp.customize.instance('header_type').get();
			
			setTimeout(function(){
				control.headerTypeInit( value );
			
			}, 500);
			
            // Bind events.
            control.container.on( "click keydown", "input:radio[name='_customize-radio-header_type_control']", control.radioOnSelect );
			control.container.on( "change", "input:radio[name='_customize-radio-color_scheme_control']", control.colorScheme );
			
			// Replace Customizer Help Content
			jQuery('.customize-panel-description').html(zoomExtendData.content);
			
			// Hide Customizer Help Content when user click on any customizer panels
			jQuery('.customize-pane-parent li').click(function(){
				
				helpContent = jQuery('#customize-info');
				
				if ( helpContent.find('.customize-help-toggle').attr('aria-expanded') == 'true' ) {
					
					helpContent.removeClass( 'open' );
					helpContent.find('.customize-panel-description').slideToggle();
					helpContent.find('.customize-help-toggle').attr('aria-expanded', 'false');
				
				}
				
			});
	
        },

        headerTypeInit: function( el ) {
			
			var group = ['image', 'image_title', 'image_title_logo'], defimg = '_control';
			
			jQuery( '#customize-control-header_image_control, #customize-control-header_slider_control, #customize-control-header_slider_p_time_control, #customize-control-header_image, #customize-control-header_slider_max_height_control, #customize-control-header_slider_is_max_height_control, #customize-control-header_rev_slider_control, #customize-control-header_rev_slider_homepage_control, #customize-control-header_slider_hp_only_control, #customize-control-header_slider_effect_control' ).hide();
				
			if ( el || el != 'none' )
				if(jQuery.inArray(el, group) !== -1) {
					el = 'image';
					defimg = '';
				}
			else {
				el = el;
				defimg = defimg;
			}

			jQuery( '#customize-control-header_'+el+''+defimg+'' ).fadeIn(500);
			
			if ( el == 'slider' ) {
				jQuery( '#customize-control-header_slider_p_time_control, #customize-control-header_slider_is_max_height_control, #customize-control-header_slider_hp_only_control, #customize-control-header_slider_effect_control' ).fadeIn(500);
				
				if ( wp.customize.value('header_slider_is_max_height')() ) {
					jQuery( '#customize-control-header_slider_max_height_control' ).fadeIn(500);
				}
			}
			
			if ( el == 'rev_slider' ) {
				jQuery( '#customize-control-header_rev_slider_homepage_control' ).fadeIn(500);
			}
			
        },
		
        radioOnSelect: function( event ) {
			
			var control = this;
			
			var isVal = event.target.defaultValue;
			
			if (isVal)
			control.headerTypeInit(isVal);
			
        },
		
		// Color Scheme
        colorScheme: function( event ) {
			
			var control = this;
			var el = event.target.defaultValue;
			var scheme = ColSchemesPreset(el);
			
			jQuery.each( scheme, function( key, value ) {
				
				// Apply the Colors
				wp.customize(key).set(value);
				
			});
			
			
			// Color Schemes Preset
			function ColSchemesPreset(schm) {
				
				// Default Color Scheme
				
				var schemes = {
					
					default_scheme : {
						
						'background_color': '#3f3f3f',
						'site_header_color': '#228ed6',
						'site_title_color': '#1e73be',
						'site_desc_color': '#e7e7e7',
						'content_bg_color': '#f5f5f5',
						'content_text_color': '#333',
						'link_color': '#1e73be',
						'post_meta_col': '#aaaaaa',
						'content_accent_col': '#d3d3d3',
						'site_nav_col': '#ffffff',
						'main_menu_bg': '#228ed6',
						'main_menu_txt': '#ffffff',
						'sub_menu_bg': '#54af1c',
						'sub_menu_txt': '#ffffff',
						'sidebar_bg': '#228ed6',
						'sidebar_ttl_col': '#ffffff',
						'sidebar_txt_col': '#f7f7f7',
						'sidebar_link_txt_col': '#e0e0e0',
						'sidebar_bor_type': 'solid',
						'sidebar_bor_col': '#e2e2e2',
						'footer_bg': '#1a7dc0',
						'footer_ttl_col': '#fff',
						'footer_txt_col': '#f7f7f7',
						'footer_link_txt_col': '#e0e0e0',
						'top_bar_bg': '#228ed6',
						'top_bar_txt_col': '#f7f7f7',
						'top_bar_link_txt_col': '#f7f7f7',
						'bottom_bar_bg': '#166cad',
						'bottom_bar_txt_col': '#f7f7f7',
						'bottom_bar_link_txt_col': '#e0e0e0',
						'author_box_bg': '#efefef',
						'author_box_txt_col': '#333',
						'button_nav': 'green',
						'button_readmore': 'blue',
					},
					
					dark_red : {
						
						'background_color': '#191919',
						'site_header_color': '#000000',
						'site_title_color': '#dd3333',	
						'content_bg_color': '#000000',
						'content_text_color': '#ffffff',
						'link_color': '#ff2828',	
						'content_accent_col': '#3d0900',
						'post_meta_col': '#aaaaaa',
						'author_box_bg': '#111111',
						'author_box_txt_col': '#bfbfbf',
						'main_menu_bg': '#b71b1b',
						'sub_menu_bg': '#e22424',
						'main_menu_txt': '#ffffff',
						'sub_menu_txt': '#ffffff',
						'sidebar_bg': '#000000',
						'sidebar_bor_col': '#1c0000',
						'sidebar_ttl_col': '#ff2828',
						'top_bar_bg': '#d83131',
						'footer_bg': '#890808',
						'bottom_bar_bg': '#750000',
						'bottom_bar_txt_col': '#e0e0e0',
						'button_nav': 'red',
						'button_readmore': 'red',
					},
						
					dark_blue : {
						
						'background_color': '#161616',
						'site_header_color': '#12578c',
						'site_title_color': '#004f84',	
						'content_bg_color': '#0b141f',
						'content_text_color': '#bcbcbc',
						'link_color': '#007baf',	
						'content_accent_col': '#003d51',
						'post_meta_col': '#aaaaaa',
						'author_box_bg': '#111111',
						'author_box_txt_col': '#bfbfbf',
						'main_menu_bg': '#00243a',
						'sub_menu_bg': '#004982',
						'main_menu_txt': '#cccccc',
						'sub_menu_txt': '#cccccc',
						'sidebar_bg': '#0b141f',
						'sidebar_bor_col': '#00293d',
						'sidebar_ttl_col': '#a3a3a3',
						'sidebar_txt_col': '#999999',
						'top_bar_bg': '#003d5b',
						'footer_bg': '#101823',
						'footer_txt_col': '#999999',
						'footer_ttl_col': '#a3a3a3',
						'bottom_bar_bg': '#0b141f',
						'bottom_bar_txt_col': '#e0e0e0',
						'button_nav': 'green',
						'button_readmore': 'green',	
						}
						
				};
				
				// Compare and modify the default array with the new scheme
				Object.assign(schemes.default_scheme, schemes[schm]);
				return schemes.default_scheme;
				
			};
			
        },
	
});


/**
 * Customizer Communicator
 */
( function ( exports, $ ) {
	"use strict";

	var api = wp.customize, OldPreviewer;

	// Custom Customizer Previewer class (attached to the Customize API)
	api.myCustomizerPreviewer = {
		// Init
		init: function () {

			var elements, sidePanel, mainOnePage, previewerBtn, clpsClue, onRefresh, updateFromBuilder, slctr;

			// Listen to the "zoom-preview-event" event has been triggered from the Previewer
			this.preview.bind( 'zoom-preview-event', function( data ) {
				
				if ( data == 'isReady' ) {
					
					var sts = 'isReady';
					// Check for our plugins compatibility
					try {
						// WP Composer plugin
						if ( wpc.ui.frontendEditor.onCustomizer !== undefined && wpc.ui.frontendEditor.onCustomizer ) sts = 'notReady';
					} catch(err) {}
					
					wp.customize.previewer.send('zoom-lite-previewer-event', sts);
					
					return false;
						
				}
				
				if ( data.type != 'change' ) {
					var sidePanel = $('.collapse-sidebar').attr('aria-expanded');
					
					if ( sidePanel == 'false' ) {
						$('.collapse-sidebar').trigger('click');
					}
				}
				
				switch(data.type) {
					
					case 'section':
					
						elements = wp.customize.section( data.target );
						slctr = '#sub-accordion-section-'+elements.id;

					break;
						
					case 'control':
					
						elements = wp.customize.control( data.target );
						slctr = elements.selector;

					break;
						
					case 'change':
					
						elements = wp.customize.control( data.target );
						
						// Makes switch custom control adapt with changes
						if (elements.params.type == 'switch_option') {
							var switchControl = elements.container.find('.ios-ui-select');
							switchControl.trigger('click');		
						} 
						// Makes multiple checkbox custom control adapt with changes
						else if (elements.params.type == 'checkbox_multiple') {
							var multiCBX = elements.container.find('ul input#'+data.value);
							multiCBX.trigger('click');		
						} 
						
						// General Options
						else {
							elements.setting.set( data.value );	
						}
						
						return;

					break;
	
					default:
				}
					
					setTimeout(function(){
						
						// Focus section or control
						elements.focus({
							completeCallback: function() {
								
								var stickyOffsetTop = parseInt($('.customize-section-title').offset().top);
								stickyOffsetTop = (stickyOffsetTop < 0 ? 85: 0);
								
								$(elements.container).ScrollTo({
									duration: 1500,
									easing: 'linear',
									offsetLeft: 12,
									offsetTop: stickyOffsetTop,
									durationMode: 'all',
									callback: function() {
										// highlight selected section / control
										setTimeout(function(){
											// Show arrow to target
											$(slctr).prepend('<span class="dashicons dashicons-arrow-'+( zoomExtendData.is_rtl ? 'right-alt2 show-arrow-rtl' : 'left-alt2 show-arrow' )+' '+( data.type == 'section' ? ' sectiononly' : '' )+'"></span>');
											
											$(slctr).effect('highlight', {color:"rgba(247, 223, 190, 0.8)"}, 2000, function(){
													// Remove arrow from target
													$('.show-arrow, .show-arrow-rtl').remove();
													
											});
											
										}, 100);
										
									}
								});
								
							}
						});
				
					}, 100);
				
			} );

		},
		
		
	};

	/**
	 * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
	 *
	 * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
	 */
	OldPreviewer = api.Previewer;
	api.Previewer = OldPreviewer.extend( {
		initialize: function( params, options ) {
			// Store a reference to the Previewer
			api.myCustomizerPreviewer.preview = this;

			// Call the old Previewer's initialize function
			OldPreviewer.prototype.initialize.call( this, params, options );
		}
	} );

	// Document Ready
	$( function() {
		// Initialize our Previewer
		api.myCustomizerPreviewer.init();
	} );
	
} )( wp, jQuery );