<?php
/**
 * Control.
 *
 * @package Zoom_Customize_Slider_Control
 */


/**
 * Class Control
 *
 * @package CustomizeObjectSelector
 */
class Zoom_Customize_Slider_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'image_slider';

	/**
	 * Slider file type.
	 *
	 * @var string
	 */
	public $file_type = 'image';

	/**
	 * Button labels.
	 *
	 * @var array
	 */
	public $button_labels = array();

	/**
	 * Constructor for Image Slider control.
	 *
	 * @param \WP_Customize_Manager $manager Customizer instance.
	 * @param string                $id      Control ID.
	 * @param array                 $args    Optional. Arguments to override class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$this->button_labels = wp_parse_args( $this->button_labels, array(
			'select'       => esc_html__( 'Select Images', 'zoom-lite' ),
			'change'       => esc_html__( 'Modify Slider Images', 'zoom-lite' ),
			'default'      => esc_html__( 'Default', 'zoom-lite' ),
			'remove'       => esc_html__( 'Remove', 'zoom-lite' ),
			'placeholder'  => esc_html__( 'No images selected', 'zoom-lite' ),
			'frame_title'  => esc_html__( 'Select Slider Images', 'zoom-lite' ),
			'frame_button' => esc_html__( 'Choose Images', 'zoom-lite' ),
		) );
	}
	
	/**
	 * Add our JavaScript and CSS to the Customizer.
	 *
	 * @access public
	 * @since  1.1
	 * @return void
	 */
	public function enqueue() {
		
		wp_enqueue_script( 'zoom-customize-image-slider-control', get_template_directory_uri() . '/inc/customizer/controls/image-slider-control/js/customize-image-slider-control.js', array( 'jquery', 'customize-controls' ), false, true );
		
		wp_enqueue_style( 'zoom-customize-image-slider-control-css', get_template_directory_uri() . '/inc/customizer/controls/image-slider-control/css/customize-image-slider-control.css' );
		
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 */
	protected function content_template() {
		$data = $this->json();
		?>
		<#

		_.defaults( data, <?php echo wp_json_encode( $data ) ?> );
		data.input_id = 'input-' + String( Math.random() );
		#>
			<span class="customize-control-title"><label for="{{ data.input_id }}">{{ data.label }}</label></span>
            <# if ( data.choices.notes && data.attachments.length > 1 ) { #>
            <span class="slider-control-note">{{ data.choices.notes }}</span>
            <# } #>
		<# if ( data.attachments ) { 
        
        var attachment_img; #>
        
        	<div class="slider-list-cont">
                <ul class="image-slider-attachments">
                    <# for (var i = 0, len = data.attachments.length; i < len; i++) {
                    
                        if ( typeof data.attachments[i].sizes.thumbnail == "undefined" ) {
                        
                            if ( typeof data.attachments[i].sizes.medium == "undefined" ) {
                            
                                attachment_img = data.attachments[i].sizes.full.url;
                                
                                } else {
                            
                                    attachment_img = data.attachments[i].sizes.medium.url;
                                    
                                }
                            
                            } else {
                            
                                attachment_img = data.attachments[i].sizes.thumbnail.url;
                            
                            } #>
                        <li data-id="{{ data.attachments[i].id }}" class="image-slider-thumbnail-wrapper">
                            <img class="attachment-thumb" src="{{ attachment_img }}" draggable="false" alt="" />
                        </li>
                    <# } #>
                </ul>
            </div>
			<# } #>
			<div class="zoom-slider-button-cont">
				<button type="button" class="button upload-button button-primary" id="image-slider-modify-slider">{{ data.button_labels.change }}</button>
			</div>
			<div class="customize-control-notifications"></div>

		<?php

	}

	/**
	 * Don't render any content for this control from PHP.
	 * JS template is doing the work.
	 */
	protected function render_content() {}

	/**
	 * Send the parameters to the JavaScript via JSON.
	 *
	 * @see \WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();
		$this->json['label'] = html_entity_decode( $this->label, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$this->json['file_type'] = $this->file_type;
		$this->json['choices'] = $this->choices;
		$this->json['button_labels'] = $this->button_labels;
	}

}
