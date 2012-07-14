<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section 
 *
 * @package WordPress
 * @subpackage Simple_Catch
 * @since Simple Catch 1.0
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(''); ?></title>
 
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>
<body <?php body_class(); ?>>
<div id="header">
	<div class="top-bg"></div>
  		<div class="layout-978">
        	<div class="logo-wrap">
            	<h1 id="site-title">
                	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
						<?php
							//Displays the header logo 	
							if( function_exists( 'simplecatch_headerlogo' ) ) :
								simplecatch_headerlogo();
							endif; 
				
							echo esc_attr( get_bloginfo( 'name', 'display' ) ); 
						?>
                    </a>
                </h1>
            	<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
        	</div><!-- .logo-wrap -->
        	<div class="social-search">
            		<?php
						// simplecatch_headersocialnetworks displays social links given from theme option in header 
						if ( function_exists( 'simplecatch_headersocialnetworks' ) ) :
							simplecatch_headersocialnetworks(); 
						endif;
						// get search form
						get_search_form();
					?>      
        	</div><!-- .social-search -->
    		<div class="row-end"></div>
            <div id="mainmenu">
            	<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
            </div><!-- #mainmenu-->  
            <div class="row-end"></div>   
        <?php 
        // This function passes the value of slider effect to js file 
        if( function_exists( 'simplecatch_pass_slider_value' ) ) {
        	simplecatch_pass_slider_value();
        }
		// Display slider in home page and breadcrumb in other pages 
		if ( function_exists( 'simplecatch_sliderbreadcrumb' ) ) :
			simplecatch_sliderbreadcrumb(); 
		endif;
		?> 
	</div><!-- .layout-978 -->
</div><!-- #header -->