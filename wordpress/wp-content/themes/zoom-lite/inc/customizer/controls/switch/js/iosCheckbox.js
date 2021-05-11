/**
 * iosCheckbox.js
 * Version: 1.0.0
 * Author: Ron Masas
 * URL : https://github.com/masasron/iosCheckbox.js
 * License : MIT ( http://www.jqueryscript.net/form/iOS-Style-Checkbox-Plugin-with-jQuery-CSS3-iosCheckbox-js.html )
 */

wp.customize.controlConstructor['switch_option'] = wp.customize.Control.extend({

    ready: function() {
		
        var control = this;
		
		control.iosCheckbox();
		control.switchCurrStatus();
	
    },
	
       iosCheckbox: function() {
		   
		var control = this;  

			control.container.find("input[type=checkbox].ios").each(function() {
				
                /**
                 * Original checkbox element
                 */
                var org_checkbox = jQuery(this);
                /**
                 * iOS checkbox markup
                 */
                var ios_checkbox = jQuery("<div>On", {
                    class: 'ios-ui-select'
                }).append(jQuery("<div>", {
                    class: 'inner'
                }));

                // If the original checkbox is checked, add checked class to the ios checkbox.
                if (org_checkbox.is(":checked")) {
                    ios_checkbox.addClass("checked");
                }
                // Hide the original checkbox and print the new one.
                org_checkbox.hide().after(ios_checkbox);
                // Add click event listener to the ios checkbox
                ios_checkbox.click(function() {
                    // Toggel the check state
                    ios_checkbox.toggleClass("checked");
                    // Check if the ios checkbox is checked
                    if (ios_checkbox.hasClass("checked")) {
                        // Update state
                        org_checkbox.prop('checked', true);
						
                    } else {
                        // Update state
                        org_checkbox.prop('checked', false);
                    }
					
					// Only used to control another controls
					if ( org_checkbox.attr('data-action') ) {
						
						var dataAct = org_checkbox.attr('data-action-id');
						
						try {
							
							var storedArray = jQuery.parseJSON(dataAct);
							
							jQuery.each(storedArray, function (i, val) {
								
								elShowHide(org_checkbox[0].checked, val);
							
							});
							
						}
						catch(err) {
							
							elShowHide(org_checkbox[0].checked, dataAct);
						
						}
							
					}
					
					// Update Settings
					control.setting.set(org_checkbox[0].checked);
					
                });
            });
			
			function elShowHide(el, elid) {
	
				if ( el ) {
					
					jQuery( '#customize-control-'+elid+'' ).fadeIn(1000);
					
				} else {
					
					jQuery( '#customize-control-'+elid+'' ).fadeOut(500);
					
				}
			
			}
			
            return this;
			
        },
	
		switchCurrStatus: function() {
			
			var rtSts = [
				{ key : 'featured_image_on_post_list', value : 'featured_image_placeholder_control' },
				{ key : 'featured_image_on_post_list', value : 'post_blog_thumb_size_control' },
				{ key : 'header_slider_is_max_height', value : 'header_slider_max_height_control' }
			];
			
			
			jQuery.each(rtSts, function (ky, val) {
				
				var curSett = wp.customize.instance(val['key']).get();
				
				if ( curSett ) {
					
					jQuery( '#customize-control-'+val['value']+'' ).show();
					
				} else {
					
					jQuery( '#customize-control-'+val['value']+'' ).hide();
					
				}
				
			});
			
		}

});