<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }


// GALLERY type
if ( has_post_format( 'gallery' ) ): ?>

  	<div class="post-format">
  		<div class="pf-gallery-container">
  			<?php
			
			$gallery_id = get_post_meta( $post->ID, 'post_gallery', true );
			
			if ( ! empty ( $gallery_id ) ) {

				$images = explode( ',', $gallery_id );

				if ( ! empty( $images ) ) { ?>
                
					<script type="text/javascript">
                        jQuery( function($){
                         var firstImage = $('#flexslider-<?php echo the_ID(); ?>').find('img').filter(':first'),
                            checkforloaded = setInterval(function() {
                                var image = firstImage.get(0);
                                if (image.complete || image.readyState == 'complete' || image.readyState == 4) {
                                    clearInterval(checkforloaded);
                                    $('#flexslider-<?php echo the_ID(); ?>').flexslider({
                                        animation: '<?php echo wp_is_mobile() ? "slide" : "fade"; ?>',
										rtl: <?php echo json_encode( is_rtl() ) ?>,
                                        slideshow: true,
                                        directionNav: true,
                                        controlNav: false,
                                        pauseOnHover: true,
                                        slideshowSpeed: 7000,
                                        animationSpeed: 600,
                                        smoothHeight: true,
										prevText: "",
										nextText: "",  
                                    });
                                }
                            }, 20);
                        });
                    </script>
                    
                    <style>
					.flexslider {
						
					}
					.flex-direction-nav a {
						outline: none !important;
					}
					.flex-direction-nav a:before {
						line-height: 40px;
					}
					
					.pf-image-caption {
						text-align: center;
						color: #676767;
						font-style: italic;
					}
					
					</style>
                
					<div class="flexslider" id="flexslider-<?php echo esc_attr( the_ID() ); ?>">
					<ul class="slides">
					<?php
					foreach( $images as $id ) {
						
						if ( ! empty( $id ) ) {
							
							$full_img_src = wp_get_attachment_image_src( $id, 'full' );
							$thumb_img_src = wp_get_attachment_image_src( $id, 'medium' );
							$image_alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
							$image_caption = '';
							?>
                            <li>
                            <img alt="<?php if ( $image_alt ) { echo esc_attr( $image_alt ); } ?>" src="<?php echo esc_url( $full_img_src[0] ); ?>" />
  							<?php if ( get_post_meta( $post->ID, 'gallery_caption', true ) == 'on' ) {
								$image_caption = get_post_field( 'post_excerpt', $id );
									if ( $image_caption ) { ?>
  								<div class="pf-image-caption"><?php echo $image_caption; ?></div>
  							<?php } } ?>
                            </li>
                            <?php
						}
					}
					?>
					</ul>
					</div>
					<?php
				}
			}
			
			?>
  		</div>
	</div>

<?php endif;


// AUDIO type
if ( has_post_format( 'audio' ) ):

		$audio_url = get_post_meta( $post->ID, 'audio_url', true );
		$autoplay = get_post_meta( $post->ID, 'audio_autoplay', true );
		
		if ( ! empty( $audio_url ) ) { ?>
		
		<style>
		.pf-audio-container {
			padding: 20px 0 20px 0;
		}
		</style>
  		<div class="post-format">
  			<div class="pf-audio-container">
			<?php
			$attr = array(
				'src'      => esc_url( $audio_url ),
				'autoplay' => ( $autoplay == 'on' ? 'true' : 'false' ),
				'preload'  => 'none'
			);
			echo wp_audio_shortcode( $attr );
			?>
            </div>
        </div>
    	<?php
		}
		
endif;


// VIDEO type
if ( has_post_format( 'video' ) ):

		$video_url = get_post_meta( $post->ID, 'video_url', true );
		$autoplay = get_post_meta( $post->ID, 'video_autoplay', true );
		
		if ( ! empty( $video_url ) ) {
			
		if ( $autoplay == 'on' ) { ?>
        <script type="text/javascript">
			jQuery( document ).ready(function() {
					setTimeout(
					function() {
						jQuery('#pf-video-<?php echo esc_attr( the_ID() ); ?>').find('.mejs-overlay-button').click();
					}, 1000);
				});
		</script>
        <?php    
		}	
		?>
		<style>
		.pf-video-container {
			margin: 30px 0 30px 0;
		}
		.pf-video-container .wp-video, .pf-video-container video.wp-video-shortcode, .pf-video-container .mejs-container, .pf-video-container .mejs-overlay.load {
			width: 100% !important;
			height: 100% !important;
		}
		.pf-video-container .mejs-container {
			padding-top: 56.25%;
		}
		.pf-video-container .wp-video, .pf-video-container video.wp-video-shortcode {
			max-width: 100% !important;
		}
		.pf-video-container video.wp-video-shortcode {
			position: relative;
		}
		.pf-video-container .mejs-mediaelement {
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
		}
		.pf-video-container .mejs-controls {
			display: none;
		}
		.pf-video-container .mejs-overlay-play {
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			width: auto !important;
			height: auto !important;
		}
		</style>
  		<div class="post-format">
  			<div id="pf-video-<?php echo esc_attr( the_ID() ); ?>" class="pf-video-container">
			<?php
			$attr = array(
				'src'      => esc_url( $video_url ),
			);
			
			echo wp_video_shortcode( $attr );
			
			?>
            </div>
        </div>
    	<?php
		}
		
endif;