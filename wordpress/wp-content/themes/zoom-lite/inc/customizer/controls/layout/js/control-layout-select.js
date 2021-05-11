wp.customize.controlConstructor['radio_image'] = wp.customize.Control.extend({

    ready: function() {
        var control = this;
        var value = (undefined !== control.setting._value) ? control.setting._value : '';
		
		if ( wp.customize.value('site_layout')() != 'boxed' ) {
			jQuery('#customize-control-site_maxwidth_control').hide();
		}
		
        this.container.on( 'click', '.img-thumb', function() {
			
			var isChecked = jQuery( this ).prev();
			var maxWidthControl = jQuery('#customize-control-site_maxwidth_control');
			
			isChecked.prop('checked', true);
			
			if (jQuery(this).attr('data-value') == 'is-boxed') {
				 maxWidthControl.fadeIn(300);
			}
			if (jQuery(this).attr('data-value') == 'is-wide') {
				maxWidthControl.fadeOut(300);
			}
			
			isChecked.trigger('change');
			
        });

        this.container.on( 'change', 'input:radio', function() {
            value = jQuery( this ).val();
            control.setting.set( value );
            // refresh the preview
           	wp.customize.previewer.refresh();
        });
    }

});