<?php
/**
 * Register jquery scripts
 *
 * @register jquery cycle and custom-script
 * hooks action wp_enqueue_scripts
 */
function simplecatch_scripts_method() {	
	//registering JQuery circle all and JQuery set up as dependent on Jquery-cycle
	wp_register_script( 'jquery-cycle', get_stylesheet_directory_uri() . '/js/jquery.cycle.all.js', '2.9999' );
	
	// registering custom scrtips
	wp_register_script( 'simplecatch_custom_slider', get_stylesheet_directory_uri() . '/js/simplecatch_custom_scripts.js', array( 'jquery', 'jquery-cycle' ), '1.0', true );
	
	// enqueue JQuery Scripts	
	wp_enqueue_script( 'simplecatch_custom_slider' );	


	//browser specific queuing i.e. for IE 1-6
	$simplecatch_ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(preg_match('/(?i)msie [1-6]/',$simplecatch_ua)) {
		wp_enqueue_script( 'pngfix', get_stylesheet_directory_uri() . '/js/pngfix.js' );	  
	}
	 if(preg_match('/(?i)msie [1-8]/',$simplecatch_ua)) {
	 	wp_enqueue_style( 'iebelow8', get_stylesheet_directory_uri() . '/css/ie.css', true );
	}
	
} // simplecatch_scripts_method
add_action( 'wp_enqueue_scripts', 'simplecatch_scripts_method' );


/**
 * Register script for admin section
 *
 * No scripts should be enqueued within this function.
 * jquery cookie used for remembering admin tabs, and potential future features... so let's register it early
 * @uses wp_register_script
 * @action admin_enqueue_scripts
 */
function simplecatch_register_js() {
	//jQuery Cookie
	wp_register_script( 'jquery-cookie', get_stylesheet_directory_uri() . '/js/jquery.cookie.min.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'simplecatch_register_js' );


/**
 * Register Google Font Style
 *
 * @uses wp_register_style and wp_enqueue_style
 * @action wp_print_styles
 */
function simplecatch_load_google_fonts() {
    wp_register_style('google-fonts', 'http://fonts.googleapis.com/css?family=Lobster');
	wp_enqueue_style( 'google-fonts');
}
add_action('wp_print_styles', 'simplecatch_load_google_fonts');


/**
 * Enqueue Comment Reply Script
 *
 * We add some JavaScript to pages with the comment form
 * to support sites with threaded comments (when in use).
 * @used comment_form_before action hook 
 */	 
function simplecatch_enqueue_comment_reply_script() {
	if ( comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'comment_form_before', 'simplecatch_enqueue_comment_reply_script' );


/**
 * Modifying the Title
 *
 * function tied to the wp_title filter hook.
 * @uses filter wp_title
 */
function simplecatch_filter_wp_title( $title ) {
	global $page, $paged;
	
	// Get the Site Name
    $site_name = get_bloginfo( 'name' );
    

	// For Homepage
    if (  is_home() || is_front_page() ) {		
		$filtered_title = $site_name;		
        // Get the Site Description
        $site_description = get_bloginfo( 'description' );
		if ( !empty( $site_description ) )  {
        	// Append Site Description to title
        	$filtered_title .= ' &#124; '. $site_description;
		}
    }
	else {	
		// Prepend name
		$filtered_title = $title .' &#124; '. $site_name;
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$filtered_title .= ' &#124; ' . sprintf( __( 'Page %s', 'simplecatch' ), max( $paged, $page ) );
	}
	
	// Return the modified title
    return $filtered_title;

}
add_filter( 'wp_title', 'simplecatch_filter_wp_title' );


/**
 * Sets the post excerpt length to 30 words.
 *
 * function tied to the excerpt_length filter hook.
 * @uses filter excerpt_length
 */
function simplecatch_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'simplecatch_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function simplecatch_continue_reading() {
	$options = get_option( 'simplecatch_options' );
	if( !isset( $options[ 'more_tag_text' ] ) ) {
		$options[ 'more_tag_text' ] = "Continue Reading &rarr;";
	}
	$more_tag_text = $options[ 'more_tag_text' ];
	return ' <a class="readmore" href="'. esc_url( get_permalink() ) . '">' . sprintf( __( '%s', 'simplecatch' ), esc_attr( $more_tag_text ) ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with simplecatch_continue_reading().
 *
 */
function simplecatch_excerpt_more( $more ) {
	return ' &hellip;' . simplecatch_continue_reading();
}
add_filter( 'excerpt_more', 'simplecatch_excerpt_more' );


/**
 * Adds Continue Reading link to post excerpts.
 *
 * function tied to the get_the_excerpt filter hook.
 */
function simplecatch_custom_excerpt( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= simplecatch_continue_reading();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'simplecatch_custom_excerpt' );


/** 
 * Allows post queries to sort the results by the order specified in the post__in parameter. 
 * Just set the orderby parameter to post__in
 *
 * uses action filter posts_orderby
 */
if ( !function_exists('simplecatch_sort_query_by_post_in') ) : //simple WordPress 3.0+ version, now across VIP

	add_filter('posts_orderby', 'simplecatch_sort_query_by_post_in', 10, 2);
	
	function simplecatch_sort_query_by_post_in($sortby, $thequery) {
		if ( isset($thequery->query['post__in']) && !empty($thequery->query['post__in']) && isset($thequery->query['orderby']) && $thequery->query['orderby'] == 'post__in' )
			$sortby = "find_in_set(ID, '" . implode( ',', $thequery->query['post__in'] ) . "')";
		return $sortby;
	}

endif;


/**
 * Get the header logo Image from theme options
 *
 * @uses header logo 
 * @get the data value of image from theme options
 * @display Header Image logo
 *
 * @uses default logo if logo field on theme options is empty
 *
 * @uses set_transient and delete_transient 
 */
function simplecatch_headerlogo() {
	//delete_transient( 'simplecatch_headerlogo' );	
		
	if ( !$simplecatch_headerlogo = get_transient( 'simplecatch_headerlogo' ) ) {
		// get data value from simplecatch_options through theme options
		$options = get_option( 'simplecatch_options' );	
		if( !isset( $options[ 'remove_header_logo' ] ) ) {
			$options[ 'remove_header_logo' ] = "0";
		}
		echo '<!-- refreshing cache -->';
		if ( $options[ 'remove_header_logo' ] == "0" ) :
		// if not empty featured_logo_header on theme options
			if ( !empty( $options[ 'featured_logo_header' ] ) ):
				$simplecatch_headerlogo = 
					'<img src="'.esc_url( $options['featured_logo_header'] ).'" alt="'.get_bloginfo( 'name' ).'" />';
			else:
				// if empty featured_logo_header on theme options, display default logo
				$simplecatch_headerlogo ='<img src="'. get_template_directory_uri().'/images/logo.png" alt="logo" />';
			endif;
		endif;
		
	set_transient( 'simplecatch_headerlogo', $simplecatch_headerlogo, 86940 );
	}
	echo $simplecatch_headerlogo;	
} // simplecatch_headerlogo


/**
 * Get the footer logo Image from theme options
 *
 * @uses footer logo 
 * @get the data value of image from theme options
 * @display footer Image logo
 *
 * @uses default logo if logo field on theme options is empty
 *
 * @uses set_transient and delete_transient 
 */
function simplecatch_footerlogo() {
	//delete_transient('simplecatch_footerlogo');	
	
	if ( !$simplecatch_footerlogo = get_transient( 'simplecatch_footerlogo' ) ) {
		// get data value from catch_options through theme options
		$options = get_option( 'simplecatch_options' );
		if( !isset( $options[ 'remove_footer_logo' ] ) ) {
			$options[ 'remove_footer_logo' ] = "0";
		}
		echo '<!-- refreshing cache -->';
		if ( $options[ 'remove_footer_logo' ] == "0" ) :
		
			// if not empty featured_logo_footer on theme options
			if ( !empty( $options[ 'featured_logo_footer' ] ) ) :
				$simplecatch_footerlogo = 
					'<img src="'.esc_url( $options[ 'featured_logo_footer' ] ).'" alt="'.get_bloginfo( 'name' ).'" />';
			else:
				// if empty featured_logo_footer on theme options, display default fav icon
				$simplecatch_footerlogo ='
					<img src="'. get_template_directory_uri().'/images/logo-foot.png" alt="footerlogo" />';
			endif;
		endif;

		
	set_transient( 'simplecatch_footerlogo', $simplecatch_footerlogo, 86940 );										  
	}
	echo $simplecatch_footerlogo;
} // simplecatch_footerlogo


/**
 * Get the favicon Image from theme options
 *
 * @uses favicon 
 * @get the data value of image from theme options
 * @display favicon
 *
 * @uses default favicon if favicon field on theme options is empty
 *
 * @uses set_transient and delete_transient 
 */
function simplecatch_favicon() {
	//delete_transient( 'simplecatch_favicon' );	
	
	if( ( !$simplecatch_favicon = get_transient( 'simplecatch_favicon' ) ) ) {
		// get data value from simplecatch_options through theme options
		$options = get_option( 'simplecatch_options' );
		if( !isset( $options[ 'remove_favicon' ] ) ) {
			$options[ 'remove_favicon' ] = "0";
		}
		echo '<!-- refreshing cache -->';
		if ( $options[ 'remove_favicon' ] == "0" ) :
			// if not empty fav_icon on theme options
			if ( !empty( $options[ 'fav_icon' ] ) ) :
				$simplecatch_favicon = '<link rel="shortcut icon" href="'.esc_url( $options[ 'fav_icon' ] ).'" type="image/x-icon" />'; 	
			else:
				// if empty fav_icon on theme options, display default fav icon
				$simplecatch_favicon = '<link rel="shortcut icon" href="'. get_template_directory_uri() .'/images/favicon.ico" type="image/x-icon" />';
			endif;
		endif;
		
	set_transient( 'simplecatch_favicon', $simplecatch_favicon, 86940 );	
	}	
	echo $simplecatch_favicon ;	
} // simplecatch_favicon

//Load Favicon in Header Section
add_action('wp_head', 'simplecatch_favicon');

//Load Favicon in Admin Section
add_action( 'admin_head', 'simplecatch_favicon' );


/**
 * This function to display featured posts on homepage header
 *
 * @get the data value from theme options
 * @displays on the homepage header
 *
 * @useage Featured Image, Title and Content of Post
 *
 * @uses set_transient and delete_transient
 */

function simplecatch_sliders() {	
	global $post;
	//delete_transient( 'simplecatch_sliders' );
		
	// get data value from simplecatch_options through theme options
	$options = get_option( 'simplecatch_options' );
	// get slider_qty from theme options
	if( isset( $options[ 'slider_qty' ] ) ) {
		$postperpage = $options[ 'slider_qty' ];
	}
	
	if( ( !$simplecatch_sliders = get_transient( 'simplecatch_sliders' ) ) && !empty( $options[ 'featured_slider' ] ) ) {
		echo '<!-- refreshing cache -->';
		
		$simplecatch_sliders = '
		<div class="featured-slider">';
			$get_featured_posts = new WP_Query( array(
				'posts_per_page' => $postperpage,
				'post__in'		 => $options[ 'featured_slider' ],
				'orderby' 		 => 'post__in',
				'ignore_sticky_posts' => 1 // ignore sticky posts
			));
			while ( $get_featured_posts->have_posts()) : $get_featured_posts->the_post();
				$title_attribute = apply_filters( 'the_title', get_the_title( $post->ID ) );
				$excerpt = get_the_excerpt();
				$simplecatch_sliders .= '
				<div class="slides">
					<div class="featured">
						<div class="slide-image">';
							if( has_post_thumbnail() ) {
								$simplecatch_sliders .= '<a href="' . get_permalink() . '" title="Permalink to '.the_title('','',false).'">';

								if( !isset( $options[ 'remove_noise_effect'] ) ) {
									$options[ 'remove_noise_effect' ] = "0";
								}
								if( $options[ 'remove_noise_effect' ] == "0" ) {
									$simplecatch_sliders .= '<span class="img-effect pngfix"></span>';
								}

								$simplecatch_sliders .= get_the_post_thumbnail( $post->ID, 'slider', array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ), 'class'	=> 'pngfix' ) ).'</a>';
							}
							else {
								$simplecatch_sliders .= '<span class="img-effect pngfix"></span>';	
							}
							$simplecatch_sliders .= '
						</div> <!-- .slide-image -->
					</div> <!-- .featured -->
					<div class="featured-text">';
						if( $excerpt !='') {
							$simplecatch_sliders .= the_title( '<span>','</span>', false ).': '.$excerpt;
						}
						$simplecatch_sliders .= '
					</div><!-- .featured-text -->
				</div> <!-- .slides -->';
			endwhile; wp_reset_query();
		$simplecatch_sliders .= '
		</div> <!-- .featured-slider -->
			<div id="controllers">
			</div><!-- #controllers -->';
			
	set_transient( 'simplecatch_sliders', $simplecatch_sliders, 86940 );
	}
	echo $simplecatch_sliders;	
} // simplecatch_sliders


/**
 * Display slider or breadcrumb on header
 *
 * If the page is home or front page, slider is displayed.
 * In other pages, breadcrumb will display if exist bread
 */
function simplecatch_sliderbreadcrumb() {
	
	// If the page is home or front page  
	if ( is_home() || is_front_page() ) :
		// display featured slider
		if ( function_exists( 'simplecatch_sliders' ) ):
			simplecatch_sliders();
		endif;
	else : 
		// if breadcrumb is not empty, display breadcrumb
		if ( function_exists( 'bcn_display_list' ) ):
			echo '<div class="breadcrumb">
					<ul>';
						bcn_display_list();
			 	echo '</ul>
					<div class="row-end"></div>
				</div> <!-- .breadcrumb -->';			
		endif; 
		
  	endif;
} // simplecatch_sliderbreadcrumb


/**
 * This function for social links display on header
 *
 * @fetch links through Theme Options
 * @use in widget
 * @social links, Facebook, Twitter and RSS
  */
function simplecatch_headersocialnetworks() {
	//delete_transient( 'simplecatch_headersocialnetworks' );
	
	// get the data value from theme options
	$options = get_option( 'simplecatch_options' );
	
	if ( ( !$simplecatch_headersocialnetworks = get_transient( 'simplecatch_headersocialnetworks' ) ) &&  ( !empty( $options[ 'social_facebook' ] ) || !empty( $options[ 'social_twitter' ] ) || !empty( $options[ 'social_googleplus' ] ) || !empty( $options[ 'social_pinterest' ] ) || !empty( $options[ 'social_youtube' ] ) || !empty( $options[ 'social_linkedin' ] ) || !empty( $options[ 'social_slideshare' ] )  || !empty( $options[ 'social_foursquare' ] ) || !empty( $options[ 'social_rss' ] )   || !empty( $options[ 'social_vimeo' ] ) || !empty( $options[ 'social_flickr' ] ) || !empty( $options[ 'social_tumblr' ] ) || !empty( $options[ 'social_deviantart' ] ) || !empty( $options[ 'social_dribbble' ] ) || !empty( $options[ 'social_myspace' ] ) || !empty( $options[ 'social_wordpress' ] ) || !empty( $options[ 'social_delicious' ] ) || !empty( $options[ 'social_lastfm' ] ) ) )  {
	
		echo '<!-- refreshing cache -->';
		
		$simplecatch_headersocialnetworks .='
			<ul class="social-profile">';
		
				//facebook
				if ( !empty( $options[ 'social_facebook' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="facebook"><a href="'.esc_url( $options[ 'social_facebook' ] ).'" title="'.sprintf( esc_attr__( '%s in Facebook', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Facebook </a></li>';
				}
				
				//Twitter
				if ( !empty( $options[ 'social_twitter' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="twitter"><a href="'.esc_url( $options[ 'social_twitter' ] ).'" title="'.sprintf( esc_attr__( '%s in Twitter', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Twitter </a></li>';
				}
				
				//Google+
				if ( !empty( $options[ 'social_googleplus' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="google-plus"><a href="'.esc_url( $options[ 'social_googleplus' ] ).'" title="'.sprintf( esc_attr__( '%s in Google+', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Google+ </a></li>';
				}
				
				//Linkedin
				if ( !empty( $options[ 'social_linkedin' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="linkedin"><a href="'.esc_url( $options[ 'social_linkedin' ] ).'" title="'.sprintf( esc_attr__( '%s in Linkedin', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Linkedin </a></li>';
				}
				
				//Pinterest
				if ( !empty( $options[ 'social_pinterest' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="pinterest"><a href="'.esc_url( $options[ 'social_pinterest' ] ).'" title="'.sprintf( esc_attr__( '%s in Pinterest', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Twitter </a></li>';
				}				
				
				//Youtube
				if ( !empty( $options[ 'social_youtube' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="you-tube"><a href="'.esc_url( $options[ 'social_youtube' ] ).'" title="'.sprintf( esc_attr__( '%s in YouTube', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' YouTube </a></li>';
				}
				
				//Vimeo
				if ( !empty( $options[ 'social_vimeo' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="viemo"><a href="'.esc_url( $options[ 'social_vimeo' ] ).'" title="'.sprintf( esc_attr__( '%s in Vimeo', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Vimeo </a></li>';
				}				
				
				//Slideshare
				if ( !empty( $options[ 'social_slideshare' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="slideshare"><a href="'.esc_url( $options[ 'social_slideshare' ] ).'" title="'.sprintf( esc_attr__( '%s in Slideshare', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Slideshare </a></li>';
				}				
				
				//Foursquare
				if ( !empty( $options[ 'social_foursquare' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="foursquare"><a href="'.esc_url( $options[ 'social_foursquare' ] ).'" title="'.sprintf( esc_attr__( '%s in Foursquare', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' foursquare </a></li>';
				}
				
				//Flickr
				if ( !empty( $options[ 'social_flickr' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="flickr"><a href="'.esc_url( $options[ 'social_flickr' ] ).'" title="'.sprintf( esc_attr__( '%s in Flickr', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Flickr </a></li>';
				}
				//Tumblr
				if ( !empty( $options[ 'social_tumblr' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="tumblr"><a href="'.esc_url( $options[ 'social_tumblr' ] ).'" title="'.sprintf( esc_attr__( '%s in Tumblr', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Tumblr </a></li>';
				}
				//deviantART
				if ( !empty( $options[ 'social_deviantart' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="deviantart"><a href="'.esc_url( $options[ 'social_deviantart' ] ).'" title="'.sprintf( esc_attr__( '%s in deviantART', 'simplecatch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' deviantART </a></li>';
				}
				//Dribbble
				if ( !empty( $options[ 'social_dribbble' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="dribbble"><a href="'.esc_url( $options[ 'social_dribbble' ] ).'" title="'.sprintf( esc_attr__( '%s in Dribbble', 'simplecatch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Dribbble </a></li>';
				}
				//MySpace
				if ( !empty( $options[ 'social_myspace' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="myspace"><a href="'.esc_url( $options[ 'social_myspace' ] ).'" title="'.sprintf( esc_attr__( '%s in MySpace', 'simplecatch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' MySpace </a></li>';
				}
				//WordPress
				if ( !empty( $options[ 'social_wordpress' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="wordpress"><a href="'.esc_url( $options[ 'social_wordpress' ] ).'" title="'.sprintf( esc_attr__( '%s in WordPress', 'simplecatch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' WordPress </a></li>';
				}				
				//RSS
				if ( !empty( $options[ 'social_rss' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="rss"><a href="'.esc_url( $options[ 'social_rss' ] ).'" title="'.sprintf( esc_attr__( '%s in RSS', 'simplecatch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' RSS </a></li>';
				}
				//Delicious
				if ( !empty( $options[ 'social_delicious' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="delicious"><a href="'.esc_url( $options[ 'social_delicious' ] ).'" title="'.sprintf( esc_attr__( '%s in Delicious', 'simplecatch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Delicious </a></li>';
				}				
				//Last.fm
				if ( !empty( $options[ 'social_lastfm' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="lastfm"><a href="'.esc_url( $options[ 'social_lastfm' ] ).'" title="'.sprintf( esc_attr__( '%s in Last.fm', 'simplecatch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Last.fm </a></li>';
				}				
		
				$simplecatch_headersocialnetworks .='
			</ul>
			<div class="row-end"></div>';
		
		set_transient( 'simplecatch_headersocialnetworks', $simplecatch_headersocialnetworks, 86940 );	 
	}
	echo $simplecatch_headersocialnetworks;
} // simplecatch_headersocialnetworks


/**
 * Site Verification  and Webmaster Tools
 *
 * If user sets the code we're going to display meta verification
 * @get the data value from theme options
 * @uses wp_head action to add the code in the header
 * @uses set_transient and delete_transient API for cache
 */
 
function simplecatch_site_verification() {
	//delete_transient( 'simplecatch_site_verification' );
	
	
	
	if ( ( !$simplecatch_site_verification = get_transient( 'simplecatch_site_verification' ) ) )  {
		// get the data value from theme options
		$options = get_option( 'simplecatch_options' );
		echo '<!-- refreshing cache -->';	
	
		//google
		if ( !empty( $options['google_verification'] ) ) {
			$simplecatch_site_verification .= '<meta name="google-site-verification" content="' .  $options['google_verification'] . '" />' . "\n";
		}
	
		//bing
		if ( !empty( $options['bing_verification'] ) ) {
			$simplecatch_site_verification .= '<meta name="msvalidate.01" content="' .  $options['bing_verification']  . '" />' . "\n";
		}
	
		//yahoo
		 if ( !empty( $options['yahoo_verification'] ) ) {
			$simplecatch_site_verification .= '<meta name="y_key" content="' .  $options['yahoo_verification']  . '" />' . "\n";
		}
	
		//site stats, analytics header code
		if ( !empty( $options['analytic_header'] ) ) {
			$simplecatch_site_verification .=  $options[ 'analytic_header' ] ;
		}
	}
	echo $simplecatch_site_verification;
}
add_action('wp_head', 'simplecatch_site_verification');


/**
 * This function loads the Footer Code such as Add this code from the Theme Option
 *
 * @get the data value from theme options
 * @load on the footer ONLY
 * @uses wp_footer action to add the code in the footer
 * @uses set_transient and delete_transient
 */
function simplecatch_footercode() {
	//delete_transient( 'simplecatch_footercode' );	
	
	if ( ( !$simplecatch_footercode = get_transient( 'simplecatch_footercode' ) ) ) {
		// get the data value from theme options
		$options = get_option( 'simplecatch_options' );
		echo '<!-- refreshing cache -->';	
		
		//site stats, analytics header code
		if ( !empty( $options['analytic_footer'] ) ) {
			$simplecatch_footercode =  $options[ 'analytic_footer' ] ;
		}
			
	set_transient( 'simplecatch_footercode', $simplecatch_footercode, 86940 );
	}
	echo $simplecatch_footercode;
}
add_action('wp_footer', 'simplecatch_footercode');


/**
 * Hooks the Custom Inline CSS to head section
 *
 * @since Simple Catch 1.2.3
 */
function simplecatch_inline_css() {
	//delete_transient( 'simplecatch_inline_css' );	
	
	if ( ( !$simplecatch_inline_css = get_transient( 'simplecatch_inline_css' ) ) ) {
		// get the data value from theme options
		$options = get_option( 'simplecatch_options' );
		echo '<!-- refreshing cache -->' . "\n";
		if( !empty( $options[ 'custom_css' ] ) ) {
			$simplecatch_inline_css	= '<!-- '.get_bloginfo('name').' Custom CSS Styles -->' . "\n";
	        $simplecatch_inline_css .= '<style type="text/css" media="screen">' . "\n";
			$simplecatch_inline_css .=  $options['custom_css'] . "\n";
			$simplecatch_inline_css .= '</style>' . "\n";
		}
			
	set_transient( 'simplecatch_inline_css', $simplecatch_inline_css, 86940 );
	}
	echo $simplecatch_inline_css;
}
add_action('wp_head', 'simplecatch_inline_css');


/*
 * Function for showing custom tag cloud
 */
function simplecatch_custom_tag_cloud() {
?>
	<div class="custom-tagcloud"><?php wp_tag_cloud('smallest=12&largest=12px&unit=px'); ?></div>
<?php	
}

/**
 * shows footer credits
 */
function simplecatch_footer() {
?>
	<div class="col5 powered-by"> 
		<?php _e( 'Design by:', 'simplecatch');?> <a href="<?php echo esc_url( __( 'http://catchthemes.com/', 'simplecatch' ) ); ?>" target="_blank" title="<?php esc_attr_e( 'Catch Themes', 'simplecatch' ); ?>"><?php _e( 'Catch Themes', 'simplecatch' ); ?></a> | <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'simplecatch' ) ); ?>" title="<?php esc_attr_e( 'WordPress', 'simplecatch' ); ?>" rel="generator" target="_blank" ><?php printf( __( 'Proudly powered by %s.', 'simplecatch' ), 'WordPress' ); ?></a>
  	</div><!--.col6 powered-by-->

<?php
}
add_filter( 'simplecatch_credits', 'simplecatch_footer' );


/**
 * Function to pass the slider value
 */
function simplecatch_pass_slider_value() {
	$options = get_option( 'simplecatch_options' );
	if( !isset( $options[ 'transition_effect' ] ) ) {
		$options[ 'transition_effect' ] = "fade";
	}
	if( !isset( $options[ 'transition_delay' ] ) ) {
		$options[ 'transition_delay' ] = 4;
	}
	if( !isset( $options[ 'transition_duration' ] ) ) {
		$options[ 'transition_duration' ] = 1;
	}
	$transition_effect = $options[ 'transition_effect' ];
	$transition_delay = $options[ 'transition_delay' ] * 1000;
	$transition_duration = $options[ 'transition_duration' ] * 1000;
	wp_localize_script( 
		'simplecatch_custom_slider',
		'js_value',
		array(
			'transition_effect' => $transition_effect,
			'transition_delay' => $transition_delay,
			'transition_duration' => $transition_duration
		)
	);
}// simplecatch_pass_slider_value

/**
 * Alter the query for the main loop in home page
 * @uses pre_get_posts hook
 */
function simple_catch_alter_home( $query ){
	$options = get_option( 'simplecatch_options' );
	if( !isset( $options[ 'exclude_slider_post' ] ) ) {
 		$options[ 'exclude_slider_post' ] = "0";
 	}
    if ( $options[ 'exclude_slider_post'] != "0" && !empty( $options[ 'featured_slider' ] ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['post__not_in'] = $options[ 'featured_slider' ];

		}
	}
}
add_action( 'pre_get_posts','simple_catch_alter_home' );



/**
 * function that displays frquently asked question in theme option
 */
function simplecatch_faq() {
?>
		<h2>FAQ: Frequently Asked Questions</h2>
		<h3>1. How to change logo on Header and Footer? </h3>
		<ul>
			<li> Click on Theme Options under Appearance. </li>
			<li> Select the Logo Tab. You can see the default logo previews. </li>
			<li> Now click on Change Header Logo and Footer Logo button. </li>
			<li> Browse the Logo image from desired location and insert into the Post. </li>
			<li> Click on Save button. Now you can see the previews.</li>
		</ul>
		
		<h3>2. How to change fav icon? </h3>
		<ul>
			<li> Click on Theme Options under Appearance. </li>
			<li> Select the Fav Icon Tab. You can see the default fav icon preview. </li>
			<li> Now click on Change Fav Icon button. </li>
			<li> Browse the fav icon image from desired location and insert into the Post. </li>
			<li> Click on Save button. Now you can see the preview.</li>
		</ul>
			
		<h3>3. How to insert Social links on the right side of header? </h3>
		<ul>
			<li> Click on Theme Options under Appearance. </li>
			<li> Select the Social Links Tab.</li>
			<li> Here you can see different social links like facebook, twitter etc. </li>
			<li> Give the social links on its respective socal fields. For example http://www.facebook.com. for facebook etc.</li>
			<li> Click on Save button.</li>
		</ul>
		
		<h3>4. How to insert Analytic scripts? </h3>
		<ul>
			<li> Click on Theme Options under Appearance. </li>
			<li> Select the Analytic Option Tab.</li>
			<li> Here you can put different scripts like, google, facebook etc. </li>
			<li> Put the script on upper textarea which you want to load on header. </li>
			<li> Put the script on lower textarea which you want to load on footer. </li>
			<li> Click on Save button.</li>
		</ul>
		
		<h3>5. How to choose featured slider? </h3>
		<ul>
			<li> Click on Featured Slider under Appearance. </li>
			<li> Give the No. of slides and click on Save Button. </li>
			<li> Now there is list of the Featured Col #1, #2 etc.</li>
			<li> To Give the Post ID's, click on "Click Here to Edit" Button which redirect you into the edit posts.</li>
			<li> Now find the post ID's which you want to display and keep that ID's on blank Featured Col #1..... </li>
			<li> Click on Save button.</li>
		</ul>
		
		<h3>6. How to create pagination in single post if the post is too long? </h3>
		<ul>
			<li> Click on the Post. </li>
			<li> Edit the specific post which you want to breakdown into more pages. </li>
			<li> Now Keep the cursor to the exact place of post where you like to break. </li>
			<li> Then copy this shortcode <!--nextpage--> and paste it.</li>
			<li> You can repeat this shortcode many times where you wan to break down.</li>
			<li> Update the post. </li>
			<li> Click on Save button.</li>
		</ul>
                    
<?php
}