wp.customize.controlConstructor['checkbox_multiple'] = wp.customize.Control.extend({

    ready: function() {
		
        var control = this;

        control.container.on( 'change', 'input[type="checkbox"]', function() {

           checkbox_values = control.container.find( 'input[type="checkbox"]:checked' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            control.container.find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
			
			control.setting.set( checkbox_values );

        });
	
    },
	
});