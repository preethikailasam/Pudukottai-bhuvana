<?php


class Zoom_Switch_Option_Control extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @access public
	 * @since  1.1
	 * @var    string
	 */
	public $type = 'switch_option';

	/**
	 * Add our JavaScript and CSS to the Customizer.
	 *
	 * @access public
	 * @since  1.1
	 * @return void
	 */
	public function enqueue() {
		
		wp_enqueue_script( 'zoom-switch-control-js', get_template_directory_uri() . '/inc/customizer/controls/switch/js/iosCheckbox.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider' ), false, true );
		
		wp_enqueue_style( 'zoom-switch-control-css', get_template_directory_uri() . '/inc/customizer/controls/switch/css/iosCheckbox.css' );
		
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
                <input <# if ( data.choices.actionid ) { #> data-action-id="{{ data.choices.actionid }}" <# } #> <# if ( data.choices.action ) { #> data-action="{{ data.choices.action }}" <# } #> <# if ( data.value ) { #> checked="checked" <# } #> value="{{ data.value }}" {{{ data.link }}} <# if ( data.value ) { #> checked="checked" <# } #> class="ios" type="checkbox" id="{{ data.id }}"/>
            <div class="customize-control-notifications"></div>
            </label>
            
		<?php
	}
	
}