jQuery(document).ready(function($) {
	
	setTimeout(function(){
	
		if (typeof(jQuery().pointer) != 'undefined') {
				
			jQuery('.collapse-sidebar-label').pointer({
				content: '<h3>'+zoomPointer.title+'</h3><p>'+zoomPointer.msg_before+'<span class="collapse-sidebar-arrow-sc"></span>'+zoomPointer.msg_after+'</p>',
				position: {
					edge: 'bottom',
				},
				open: function() {
					$( ".wp-pointer" ).hide().fadeIn(1000);
				},
				close: function() {
					jQuery.post( ajaxurl, {
						pointer: 'zoom_customize_pointer',
						action: 'dismiss-wp-pointer'
					});
				}
			}).pointer('open');
			
		}
	
	}, 5000); // display wp pointer after 5 seconds
	
	// Permanent dismiss WP Pointer when collapse button or label are clicked
	$(".collapse-sidebar, .collapse-sidebar-label").click(function(){
		
		if (typeof(jQuery().pointer) != 'undefined') {
			
			if ( $( ".wp-pointer" ).length ) {
				$('.wp-pointer .wp-pointer-buttons a.close').click();
			}
			
		}
		
	});

});