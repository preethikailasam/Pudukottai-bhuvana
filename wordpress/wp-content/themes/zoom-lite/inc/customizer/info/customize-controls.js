( function( api ) {

	// Extends our custom "zoom-info" section.
	api.sectionConstructor['zoom-lite'] = api.Section.extend( {
		
		ready: function() {
	
			var control = this;
			control.changeClass();
		
		},
		
		changeClass: function() {
	
			var control = this;
	
			control.container.on({
					
					mouseenter: function () {
						
						jQuery(this).find(".towriterev").removeClass('button-secondary').addClass('button-primary');
						
						},
					mouseleave: function () {
						jQuery(this).find(".towriterev").removeClass('button-primary').addClass('button-secondary');
						}
				
				});	
			
		},

		// No events for this type of section.
		attachEvents: function () {},
	
		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	
	} );

} )( wp.customize );
