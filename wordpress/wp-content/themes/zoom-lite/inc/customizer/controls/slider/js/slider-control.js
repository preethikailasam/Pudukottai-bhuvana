wp.customize.controlConstructor['slider_option'] = wp.customize.Control.extend({

    ready: function() {
		
        var control = this;

		control.slider();
	
    },
	
	slider: function() {

		var control = this;
		var opt = control.params.choices;

		jQuery( '#slider-'+control.id ).slider({
            range: 'min',
            min: parseInt(opt.min),
            max: parseInt(opt.max),
			step: parseInt(opt.step),
            value: parseInt(control.setting.get()),
            slide: function( event, ui ) {

                jQuery( "#slider-"+control.id+"-indicator" ).text( ui.value );
				jQuery( "#slider-"+control.id+"-value" ).val( ui.value );
				
            },
            stop: function( event, ui ) {
				
				control.setting.set( ui.value );
				wp.customize.previewer.refresh();
				
            }
			
        });
		
	}

});