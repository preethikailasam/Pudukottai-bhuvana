<?php


class Zoom_Slider_Option_Control extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @access public
	 * @since  1.1
	 * @var    string
	 */
	public $type = 'slider_option';

	/**
	 * Add our JavaScript and CSS to the Customizer.
	 *
	 * @access public
	 * @since  1.1
	 * @return void
	 */
	public function enqueue() {
		
		wp_enqueue_script( 'zoom-slider-control-js', get_template_directory_uri() . '/inc/customizer/controls/slider/js/slider-control.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider' ), false, true );
		
		wp_enqueue_style( 'zoom-slider-control-css', get_template_directory_uri() . '/inc/customizer/controls/slider/css/slider-control.css' );
		
	}

	/**
	 * Add custom JSON parameters to use in the JS template.
	 *
	 * @access public
	 * @since  1.1
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['choices'] = $this->choices;
		$this->json['value']   = $this->value();
		$this->json['id']      = $this->id;
		$this->json['link']    = $this->get_link();

	}

	/**
	 * An Underscore (JS) template for this control's content.
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see    WP_Customize_Control::print_template()
	 *
	 * @access protected
	 * @since  1.1
	 * @return void
	 */
	protected function content_template() {
		$data = $this->json();
		?>
        <#
		_.defaults( data, <?php echo wp_json_encode( $data ) ?> );
		#>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
			<label for="{{ data.id }}-input">
				<span class="screen-reader-text">{{ data.label }}</span>
                <span class="slider-container"><span id="slider-{{ data.id }}" ></span><span id="slider-{{ data.id }}-indicator" class="pixoprval">{{ data.value }}</span><span id="pixopr">{{ data.choices.conver }}</span></span>
                <input id="slider-{{ data.id }}-value" {{{ data.link }}} type="hidden" value="{{ data.value }}" />
			</label>
            <div class="customize-control-notifications"></div>
            <# if ( data.choices.note ) { #>
            <span class="note-in-slider" style="display:block;"><p>{{ data.choices.note }}</p></span>
            <# } #>
            <# if ( data.choices.separator ) { #>
            <span style="display:block; margin-top:{{ data.choices.separator }}px;"></span>
            <# } #>
            
		<?php
	}
	
}