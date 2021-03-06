<footer class="entry-footer">
<div id="writer-box" class="author">
<?php $author = get_userdata( $wp_query->post->post_author ); ?>
<div class="author-avatar"><a href="<?php echo get_avatar_url( $author->user_email, array( 'size'=>1200 ) ); ?>" class="swipebox" data-rel="profile" target="_self" onclick="javascript:void(0)"><?php echo get_avatar( $author->user_email, 600 ); ?></a></div>
<div class="author-social">
<?php if ( $author->facebook == '' ) {} else { echo '<a href="' . $author->facebook . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/fb.svg" alt="Facebook" class="svg" /></a>'; } ?>
<?php if ( $author->twitter == '' ) {} else { echo '<a href="' . $author->twitter . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/tw.svg" alt="Twitter" class="svg" /></a>'; } ?>
<?php if ( $author->instagram == '' ) {} else { echo '<a href="' . $author->instagram . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/ig.svg" alt="Instagram" class="svg" /></a>'; } ?>
<?php if ( $author->pinterest == '' ) {} else { echo '<a href="' . $author->pinterest . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/pn.svg" alt="Pinterest" class="svg" /></a>'; } ?>
<?php if ( $author->twitch == '' ) {} else { echo '<a href="' . $author->twitch . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/tt.svg" alt="Twitch" class="svg" /></a>'; } ?>
<?php if ( $author->youtube == '' ) {} else { echo '<a href="' . $author->youtube . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/yt.svg" alt="YouTube" class="svg" /></a>'; } ?>
</div>
<h3 class="author-name"><?php echo $author->display_name ?></h3>
<p class="author-contact clear"><?php if ( $author->user_url == '' ) {} else { echo '<a href="' . $author->user_url . '" target="_blank">' . parse_url( $author->user_url, PHP_URL_HOST ) . '</a>'; } ?><?php if ( $author->publicemail == '' ) {} else { echo '<a href="mailto:' . $author->publicemail . '" target="_blank">' . $author->publicemail . '</a>'; } ?><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?>">all articles &rarr;</a></p>
<p class="author-description clear"><?php echo $author->user_description ?></p>
</div>
<span class="tag-links"><?php the_tags(); ?></span>
<?php if ( is_singular() ) {
echo '<div class="share">';
echo '<a href="https://www.facebook.com/sharer/sharer.php?t=' . get_the_title() . '&u=' . get_permalink() . '" title="' . esc_attr__( 'Share on Facebook', 'publishers' ) . '" class="facebook" target="_blank"><span class="icon"><svg viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path fill="currentColor" d="m22.676 0h-21.352c-.731 0-1.324.593-1.324 1.324v21.352c0 .732.593 1.324 1.324 1.324h11.494v-9.294h-3.129v-3.621h3.129v-2.675c0-3.099 1.894-4.785 4.659-4.785 1.325 0 2.464.097 2.796.141v3.24h-1.921c-1.5 0-1.792.721-1.792 1.771v2.311h3.584l-.465 3.63h-3.119v9.282h6.115c.733 0 1.325-.592 1.325-1.324v-21.352c0-.731-.592-1.324-1.324-1.324" /></svg></span></a>';
echo '<a href="https://twitter.com/intent/tweet?text=' . get_the_title() . '&url=' . get_permalink() . '" title="' . esc_attr__( 'Share on Twitter', 'publishers' ) . '" class="twitter" target="_blank"><span class="icon"><svg viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path fill="currentColor" d="m23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124-4.09-.193-7.715-2.157-10.141-5.126-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548z" /></svg></span></a>';
echo '<a href="mailto:?subject=' . get_the_title() . '&body=' . get_permalink() . '" title="' . esc_attr__( 'Share over Email', 'publishers' ) . '" class="email" target="_blank"><span class="icon"><svg viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path fill="currentColor" d="M21.386 2.614H2.614A2.345 2.345 0 0 0 .279 4.961l-.01 14.078a2.353 2.353 0 0 0 2.346 2.347h18.771a2.354 2.354 0 0 0 2.347-2.347V4.961a2.356 2.356 0 0 0-2.347-2.347zm0 4.694L12 13.174 2.614 7.308V4.961L12 10.827l9.386-5.866v2.347z" /></svg></span></a>';
echo '<a href="javascript:window.print()" title="' . esc_attr__( 'Print this Article', 'publishers' ) . '" class="print"><span class="icon"><svg viewbox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path fill="currentColor" d="M18,3H6V7H18M19,12A1,1 0 0,1 18,11A1,1 0 0,1 19,10A1,1 0 0,1 20,11A1,1 0 0,1 19,12M16,19H8V14H16M19,8H5A3,3 0 0,0 2,11V17H6V21H18V17H22V11A3,3 0 0,0 19,8Z" /></svg></span></a>';
echo '</div>';
} ?>
<div id="comments"></div>
</footer> 