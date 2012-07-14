<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Simple_Catch
 * @since Simple Catch 1.0
 */

get_header(); ?>

<div id="main" class="layout-978">
	<div id="content" class="col8 no-margin-left">
      
    	<?php if (have_posts()): ?>
       		<h2 class="entry-title"><?php printf( 'Showing results for: <span class="img-title">%s</span>', get_search_query() ); ?></h2>
       		
            <?php while (have_posts()) : the_post(); ?>
            
            	<div <?php post_class();?>>
                	
                	<h3><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h3>
                    <?php the_excerpt(); ?>
                  	<div class="row-end"></div>
                </div> <!-- .post -->
            
            <?php endwhile; 
				// Checking WP Page Numbers plugin exist
				if ( function_exists('wp_pagenavi' ) ) : 
                 	wp_pagenavi();
				
				// Checking WP-PageNaviplugin exist
				elseif ( function_exists('wp_page_numbers' ) ) : 
					wp_page_numbers();
					 
				else: ?>
					<ul class="default-wp-page">
						<li class="previous"><?php next_posts_link( __( 'Previous', 'simplecatch' ) ); ?></li>
						<li class="next"><?php previous_posts_link( __( 'Next', 'simplecatch' ) ); ?></li>
					</ul>         
			
				<?php endif; 
            
		else : ?>
            <h2><?php printf('Your search <span> "%s" </span> did not match any documents', get_search_query() ); ?></h2>
            <div class="post">
            	<h5><?php _e( 'A few suggestions', 'simplecatch' ); ?></h5>
                <ul>
                    <li><?php _e( 'Make sure all words are spelled correctly', 'simplecatch' ); ?></li>
                    <li><?php _e( 'Try different keywords', 'simplecatch' ); ?></li>
                    <li><?php _e( 'Try more general keywords', 'simplecatch' ); ?></li>
                </ul> 
            </div> <!-- .post -->
            
   <?php endif; ?>

    </div> <!-- #content -->
            
 	<?php get_sidebar(); ?>
            
</div> <!-- #main -->

<?php get_footer(); ?> 