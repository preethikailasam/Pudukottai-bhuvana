<?php

/**
 * Multiple checkbox customize control class.
 *
 * @since  1.0.0
 * @access public
 */
class Zoom_Multiple_Checkbox_Control extends WP_Customize_Control {

    /**
     * The type of customize control being rendered.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $type = 'checkbox_multiple';

    /**
     * Enqueue scripts/styles.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function enqueue() {
		
        wp_enqueue_script( 'zoom-cb-customize-controls', get_template_directory_uri() . '/inc/customizer/controls/multiple-checkbox/js/customize-controls.js', array( 'jquery', 'customize-controls' ) );
		
    }

    /**
     * Displays the control content.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function render_content() {}
	
	protected function content_template() {
		$data = $this->json();
		?>
        <#
		_.defaults( data, <?php echo wp_json_encode( $data ) ?> );
		#>
		<# if ( ! data.choices ) {
			return;
		} #>

		<# if ( data.label ) { #>
			<span class="title customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>  
        <ul>
		<# for ( key in data.choices ) { #>
                <li>	
                    <label>
                        <input id="{{ key }}" type="checkbox" value="{{ key }}" <# if ( _.contains(data.value, key) ) { #> checked="checked" <# } #> /> 
                        {{ data.choices[ key ]['label'] }}
                    </label>
                </li>
		<# } #>
        </ul>
        <input type="hidden" {{{ data.link }}} value="{{ data.value.join(', ') }}" />
		<?php
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
		$this->json['link']    = $this->get_link();
		$this->json['value']   = $this->value();
		$this->json['id']      = $this->id;
		
	}
	
	
}