<?php
/**
 * Custom customizer section.
 *
 * @since 1.0.0.37
 * @access public
 */
class Zoom_Lite_Customize_Custom_Section extends WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @var    string
	 */
	public $type = 'zoom-lite';

	/**
	 * Priority of the section which informs load order of sections.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @var integer
	 */
	public $priority = 5;

	/**
	 * Custom button text to output.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @var    string
	 */
	public $pro_text = '';

	/**
	 * Custom pro button URL.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @var    string
	 */
	public $pro_url = '';
	public $pro_type = '';
	public $pro_icon = '';
	

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();

		$json['pro_text'] = $this->pro_text;
		$json['pro_url']  = $this->pro_url;
		$json['pro_icon']  = $this->pro_icon;
		$json['pro_type']  = ( $this->pro_type ? $this->pro_type : '' );

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since 1.0.0.37
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>

		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
        
        	<# if ( data.pro_type == 'page_builder' || data.pro_type == 'review' ) { #>

			<h3 class="accordion-section-title"><span class="is-dash-info dashicons {{data.pro_icon}}"></span>
				{{ data.title }}
				<# if ( data.pro_text && data.pro_url ) { #>
					<a href="{{ data.pro_url }}" class="towriterev button button-secondary alignright" target="_blank">{{ data.pro_text }}</a>
				<# } #>
			</h3>
            
           <# } #>
           
        	<# if ( data.pro_type == 'update' ) { #>

			<div class="available-update-message">
            	<h3 class="accordion-section-title">
            		{{ data.title }} <a href="{{ data.pro_url }}" class="button-link" target="_blank">{{ data.pro_text }}</a>
                </h3>
			</div>
            
           <# } #>
            
		</li>
	<?php }
}
