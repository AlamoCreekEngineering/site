<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a Simple Catch theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package WordPress
 * @subpackage Simple_Catch
 * @since Simple Catch 1.0
 */

get_header(); ?>

	<div id="main" class="layout-978">
    	<div id="content" class="col8 no-margin-left"> 
			<?php if ( have_posts() ) : 
                while( have_posts() ):the_post(); ?>	
            
                    <div <?php post_class(); ?> >
                        <?php //If category has thumbnail it displays thumbnail and excerpt of content else excerpt only 
                        if ( has_post_thumbnail() ) : ?>
                            <div class="col3 post-img">
                                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" ><?php the_post_thumbnail( 'featured' ); ?></a>
                            </div> <!-- .col3 -->  
                            <div class="col5">
                        <?php else : ?>
                            <div class="col8">
                        <?php endif; ?> 
                                <h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="bookmark" ><?php the_title(); ?></a></h2>
                                <ul class="post-by">
                                    <li class="no-padding-left"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo esc_attr(get_the_author_meta( 'display_name' ) ); ?>">By &nbsp;<?php the_author_meta( 'display_name' );?></a></li>
                                    <li><?php the_time( 'j F, Y' ); ?></li>
                                    <li class="last"><?php comments_popup_link( 'No Comments ', '1 Comment ', '% Comments ' ); ?></li>
                                </ul>
                                <?php the_excerpt(); ?>
                            </div>   
                         
                            <div class="row-end"></div>
                    </div><!-- .post -->
                    <hr />
                    
          			<?php endwhile;
                    
            		// Checking WP Page Numbers plugin exist
					if ( function_exists('wp_pagenavi' ) ) : 
						wp_pagenavi();
					
					// Checking WP-PageNaviplugin exist
					elseif ( function_exists('wp_page_numbers' ) ) : 
						wp_page_numbers();
						   
					else: 
						global $wp_query;
						if ( $wp_query->max_num_pages > 1 ) : 
					?>
							<ul class="default-wp-page">
								<li class="previous"><?php next_posts_link( __( 'Previous', 'simplecatch' ) ); ?></li>
								<li class="next"><?php previous_posts_link( __( 'Next', 'simplecatch' ) ); ?></li>
							</ul>
                        <?php endif;
 					endif; 
                    ?>

                    			
			<?php else : ?>
                    <div class="post">
                        <h2><?php _e( 'Not found', 'simplecatch' ); ?></h2>
                        <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'simplecatch' ); ?></p>
                        <?php get_search_form(); ?>
                    </div><!-- .post -->
			
			<?php endif; ?>
        
		</div><!-- #content -->
            
 		<?php get_sidebar(); ?>
            
	</div><!-- #main --> 
        
<?php get_footer(); ?>