<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Initialize the custom Meta Boxes. 
 */
add_action( 'admin_init', 'zoom_ot_meta_boxes' );

function zoom_ot_meta_boxes() {
	
    $post_format_audio = array(
      'id'          => 'format-audio',
      'title'       => __( 'Format: Audio', 'zoom-lite' ),
      'desc'        => __( 'These settings enable you to embed mp3, ogg, wma, m4a or wav audio file type into your top of this post.', 'zoom-lite' ),
      'pages'       => array( 'post' ),
      'context'     => 'normal',
      'priority'    => 'high',
      'fields'      => array(
			   array(
				 'label'   => __( 'File URL', 'zoom-lite' ),
				 'id'    => 'audio_url',
				 'type'    => 'upload'
			  ),
		      array(
				'id'          => 'audio_autoplay',
				'label'       => __( 'Auto Play', 'zoom-lite' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => '',
				'condition'   => '',
				'operator'    => 'and'
			  ),		
      )
    );
	
    $post_format_video = array(
      'id'          => 'format-video',
      'title'       =>  __( 'Format: Video', 'zoom-lite' ),
      'desc'        =>  __( 'These settings enable you to embed YouTube, Vimeo, mp4, m4v, webm, ogv, wmv, or flv video type into your top of this post.', 'zoom-lite' ),
      'pages'       => array( 'post' ),
      'context'     => 'normal',
      'priority'    => 'high',
      'fields'      => array(
			  array(
			   'label'   => __( 'Video URL', 'zoom-lite' ),
			    'id'    => 'video_url',
			    'type'    => 'upload',
			    'desc'    => ''
			  ),
		      array(
				'id'          => 'video_autoplay',
				'label'       => __( 'Auto Play', 'zoom-lite' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => '',
				'condition'   => '',
				'operator'    => 'and'
			  ),	
      )
    );
	
    $post_format_gallery = array(
		'id'          => 'format-gallery',
		'title'       => __( 'Format: Gallery', 'zoom-lite' ),
		'desc'        => __( 'The gallery you set here will appear on top of this post.', 'zoom-lite' ),
		'pages'       => array( 'post' ),
		'context'     => 'normal',
		'priority'    => 'high',
		'fields'      => array(
			  array(
				'label'       => __( 'Gallery', 'zoom-lite' ),
				'id'          => 'post_gallery',
				'type'        => 'gallery',
			  ),
		      array(
				'id'          => 'gallery_caption',
				'label'       => __( 'Show Image Caption', 'zoom-lite' ),
				'std'         => 'on',
				'type'        => 'on-off',
				'rows'        => '',
				'post_type'   => '',
				'taxonomy'    => '',
				'min_max_step'=> '',
				'class'       => '',
				'condition'   => '',
				'operator'    => 'and'
			  ),
		)
  );


  /**
   * Register our meta boxes using the 
   * ot_register_meta_box() function.
   */
	if ( function_exists( 'ot_register_meta_box' ) )
	
		ot_register_meta_box( $post_format_audio );
		ot_register_meta_box( $post_format_video );
		ot_register_meta_box( $post_format_gallery );
		
}