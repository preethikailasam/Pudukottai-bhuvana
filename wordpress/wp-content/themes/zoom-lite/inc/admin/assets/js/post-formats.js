/*
	post-formats.js

	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html

	Copyright: (c) 2013 Jermaine Maree, http://jermainemaree.com
*/

function zoom_isGutenbergActive() {
	return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
}

jQuery(document).ready(function($) {

	// Hide post format sections
	function hide_statuses() {
		$('#format-audio,#format-aside,#format-chat,#format-gallery,#format-image,#format-link,#format-quote,#format-status,#format-video').hide();
	}

	// Post Formats
	if(zomm_lclz.pst_type == 'post') {
		// Hide post format sections
		hide_statuses();

		// Supported post formats
		var post_formats = ['audio','aside','chat','gallery','image','link','quote','status','video'];

		// Get selected post format
		var selected_post_format = ( zoom_isGutenbergActive() ? $('select[id*="post-format"]').val() : $("input[name='post_format']:checked").val() );

		// Show post format meta box
		if(jQuery.inArray(selected_post_format, post_formats) != '-1') {
			$('#format-'+selected_post_format).show();
		}
		
		if (zoom_isGutenbergActive()) {
			// Hide/show post format meta box when option changed
			$(document).on('change', 'select[id*="post-format"]',function(){
				// Hide post format sections
				hide_statuses();
				// Shoe selected section
				if(jQuery.inArray(this.value,post_formats) != '-1') {
					$('#format-'+this.value).show();
				}
			});
		}
		else {
			// Hide/show post format meta box when option changed
			$("input[name='post_format']:radio").change(function() {
				// Hide post format sections
				hide_statuses();
				// Shoe selected section
				if(jQuery.inArray($(this).val(),post_formats) != '-1') {
					$('#format-'+$(this).val()).show();
				}
			});
		
		}
	}
	
	// Trigger change
	if (zoom_isGutenbergActive()) {
		
		setTimeout(function(){
			$('select[id*="post-format"]').trigger('change');
		}, 1000);
		
	}
	
	// WP pointer
	setTimeout(function(){
	
		if (typeof(jQuery().pointer) != 'undefined' && ( $('#formatdiv').is(':visible') || $('.editor-post-format').is(':visible'))) {
				
			jQuery('#formatdiv h2.ui-sortable-handle, .editor-post-format').pointer({
				content: '<h3>'+zomm_lclz.title+'</h3><p>'+zomm_lclz.content+'</p>',
				position: {
					edge: (zomm_lclz.is_rtl ? 'left': 'right'),
					align: 'left',
				},
				open: function() {
					$( ".wp-pointer" ).hide().fadeIn(1000);
				},
				close: function() {
					jQuery.post( ajaxurl, {
						pointer: 'zoom_post_format_pointer',
						action: 'dismiss-wp-pointer'
					});
				}
			}).pointer('open');
			
		}
	
	}, 5000); // display wp pointer after 5 seconds

});