<?php if ( ! defined( 'OT_VERSION' ) ) exit( 'No direct script access allowed' );
/**
 * Functions used to build each option type.
 *
 * @package   OptionTree
 * @author    Derek Herman <derek@valendesigns.com>
 * @copyright Copyright (c) 2013, Derek Herman
 * @since     2.0
 */

/**
 * Builds the HTML for each of the available option types by calling those
 * function with call_user_func and passing the arguments to the second param.
 *
 * All fields are required!
 *
 * @param     array       $args The array of arguments are as follows:
 * @param     string      $type Type of option.
 * @param     string      $field_id The field ID.
 * @param     string      $field_name The field Name.
 * @param     mixed       $field_value The field value is a string or an array of values.
 * @param     string      $field_desc The field description.
 * @param     string      $field_std The standard value.
 * @param     string      $field_class Extra CSS classes.
 * @param     array       $field_choices The array of option choices.
 * @param     array       $field_settings The array of settings for a list item.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_display_by_type' ) ) {

  function ot_display_by_type( $args = array() ) {
    
    /* allow filters to be executed on the array */
    $args = apply_filters( 'ot_display_by_type', $args );
    
    /* build the function name */
    $function_name_by_type = str_replace( '-', '_', 'ot_type_' . $args['type'] );
    
    /* call the function & pass in arguments array */
    if ( function_exists( $function_name_by_type ) ) {
      call_user_func( $function_name_by_type, $args );
    } else {
      echo '<p>' . __( 'Sorry, this function does not exist', 'zoom-lite' ) . '</p>';
    }
    
  }
  
}


/**
 * Gallery option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     The options arguments
 * @return    string    The gallery metabox markup.
 *
 * @access    public
 * @since     2.2.0
 */
if ( ! function_exists( 'ot_type_gallery' ) ) {

  function ot_type_gallery( $args = array() ) {
  
    // Turns arguments array into variables
    extract( $args );
  
    // Verify a description
    $has_desc = $field_desc ? true : false;
  
    // Format setting outer wrapper
    echo '<div class="format-setting type-gallery ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
  
      // Description
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
  
      // Format setting inner wrapper
      echo '<div class="format-setting-inner">';
  
        // Setup the post type
        $post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
        
        $field_value = trim( $field_value );
        
        // Saved values
        echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="ot-gallery-value ' . esc_attr( $field_class ) . '" />';
        
        // Search the string for the IDs
        preg_match( '/ids=\'(.*?)\'/', $field_value, $matches );
        
        // Turn the field value into an array of IDs
        if ( isset( $matches[1] ) ) {
          
          // Found the IDs in the shortcode
          $ids = explode( ',', $matches[1] );
          
        } else {
          
          // The string is only IDs
          $ids = ! empty( $field_value ) && $field_value != '' ? explode( ',', $field_value ) : array();
          
        }

        // Has attachment IDs
        if ( ! empty( $ids ) ) {
          
          echo '<ul class="ot-gallery-list">';
          
          foreach( $ids as $id ) {
            
            if ( $id == '' )
              continue;
              
            $thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );
        
            echo '<li><img  src="' . $thumbnail[0] . '" width="75" height="75" /></li>';
        
          }
        
          echo '</ul>';
          
          echo '
          <div class="ot-gallery-buttons">
            <a href="#" class="option-tree-ui-button button button-secondary hug-left ot-gallery-delete">' . __( 'Delete Gallery', 'zoom-lite' ) . '</a>
            <a href="#" class="option-tree-ui-button button button-primary right hug-right ot-gallery-edit">' . __( 'Edit Gallery', 'zoom-lite' ) . '</a>
          </div>';
        
        } else {
        
          echo '
          <div class="ot-gallery-buttons">
            <a href="#" class="option-tree-ui-button button button-primary right hug-right ot-gallery-edit">' . __( 'Create Gallery', 'zoom-lite' ) . '</a>
          </div>';
        
        }
      
      echo '</div>';
      
    echo '</div>';
    
  }

}


/**
 * On/Off option type
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     The options arguments
 * @return    string    The gallery metabox markup.
 *
 * @access    public
 * @since     2.2.0
 */
if ( ! function_exists( 'ot_type_on_off' ) ) {

  function ot_type_on_off( $args = array() ) {

    /* turns arguments array into variables */
    extract( $args );

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="format-setting type-radio ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      /* format setting inner wrapper */
      echo '<div class="format-setting-inner">';

        /* Force only two choices, and allowing filtering on the choices value & label */
        $field_choices = array(
          array(
            /**
             * Filter the value of the On button.
             *
             * @since 2.5.0
             *
             * @param string The On button value. Default 'on'.
             * @param string $field_id The field ID.
             * @param string $filter_id For filtering both on/off value with one function.
             */
            'value'   => apply_filters( 'ot_on_off_switch_on_value', 'on', $field_id, 'on' ),
            /**
             * Filter the label of the On button.
             *
             * @since 2.5.0
             *
             * @param string The On button label. Default 'On'.
             * @param string $field_id The field ID.
             * @param string $filter_id For filtering both on/off label with one function.
             */
            'label'   => apply_filters( 'ot_on_off_switch_on_label', __( 'On', 'zoom-lite' ), $field_id, 'on' )
          ),
          array(
            /**
             * Filter the value of the Off button.
             *
             * @since 2.5.0
             *
             * @param string The Off button value. Default 'off'.
             * @param string $field_id The field ID.
             * @param string $filter_id For filtering both on/off value with one function.
             */
            'value'   => apply_filters( 'ot_on_off_switch_off_value', 'off', $field_id, 'off' ),
            /**
             * Filter the label of the Off button.
             *
             * @since 2.5.0
             *
             * @param string The Off button label. Default 'Off'.
             * @param string $field_id The field ID.
             * @param string $filter_id For filtering both on/off label with one function.
             */
            'label'   => apply_filters( 'ot_on_off_switch_off_label', __( 'Off', 'zoom-lite' ), $field_id, 'off' )
          )
        );

        /**
         * Filter the width of the On/Off switch.
         *
         * @since 2.5.0
         *
         * @param string The switch width. Default '100px'.
         * @param string $field_id The field ID.
         */
        $switch_width = apply_filters( 'ot_on_off_switch_width', '100px', $field_id );

        echo '<div class="on-off-switch"' . ( $switch_width != '100px' ? sprintf( ' style="width:%s"', $switch_width ) : '' ) . '>';

        /* build radio */
        foreach ( (array) $field_choices as $key => $choice ) {
          echo '
            <input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '"' . checked( $field_value, $choice['value'], false ) . ' class="radio option-tree-ui-radio ' . esc_attr( $field_class ) . '" />
            <label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" onclick="">' . esc_attr( $choice['label'] ) . '</label>';
        }

          echo '<span class="slide-button"></span>';

        echo '</div>';

      echo '</div>';

    echo '</div>';

  }

}


/**
 * Upload option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_upload' ) ) {
  
  function ot_type_upload( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* If an attachment ID is stored here fetch its URL and replace the value */
    if ( $field_value && wp_attachment_is_image( $field_value ) ) {
    
      $attachment_data = wp_get_attachment_image_src( $field_value, 'original' );
      
      /* check for attachment data */
      if ( $attachment_data ) {
      
        $field_src = $attachment_data[0];
        
      }
      
    }
    
    /* format setting outer wrapper */
    echo '<div class="format-setting type-upload ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="format-setting-inner">';
      
        /* build upload */
        echo '<div class="option-tree-ui-upload-parent">';
          
          /* input */
          echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-upload-input ' . esc_attr( $field_class ) . '" />';
          
          /* add media button */
          echo '<a href="javascript:void(0);" class="ot_upload_media option-tree-ui-button button button-primary light" rel="' . $post_id . '" title="' . __( 'Add Media', 'zoom-lite' ) . '"><span class="icon ot-icon-plus-circle"></span>' . __( 'Add Media', 'zoom-lite' ) . '</a>';
        
        echo '</div>';
        
        /* media */
        if ( $field_value ) {
            
          echo '<div class="option-tree-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';
            
            /* replace image src */
            if ( isset( $field_src ) )
              $field_value = $field_src;
              
            if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value ) )
              echo '<div class="option-tree-ui-image-wrap"><img src="' . esc_url( $field_value ) . '" alt="" /></div>';
            
            echo '<a href="javascript:(void);" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' . __( 'Remove Media', 'zoom-lite' ) . '"><span class="icon ot-icon-minus-circle"></span>' . __( 'Remove Media', 'zoom-lite' ) . '</a>';
            
          echo '</div>';
          
        }
        
      echo '</div>';
    
    echo '</div>';
    
  }
  
}

/* End of file ot-functions-option-types.php */
/* Location: ./includes/ot-functions-option-types.php */
