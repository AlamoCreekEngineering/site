<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Simple_Catch
 * @since Simple Catch 1.0
 */
 
get_header(); ?>
 
<div id="main" class="layout-978">
	<div id="content" class="col8 no-margin-left">
            
        <?php while ( have_posts() ) : the_post(); ?>
        
            <div <?php post_class(); ?>>
            
            	<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
                <ul class="post-by">
                	<li class="no-padding-left"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>" 
                        title="<?php echo esc_attr( get_the_author_meta( 'display_name' ) ); ?>">By &nbsp;<?php the_author_meta( 'display_name' ); ?></a></li>
                    <li><?php the_time( 'j F, Y' ); ?></li>
                    <li><?php comments_popup_link( 'No Comments ', '1 Comment ', '% Comments ' ); ?></li>
                </ul>
                <?php the_content();
					// copy this <!--nextpage--> and paste at the post content where you want to break the page
					 wp_link_pages(array( 
						'before'			=> '<div class="pagination">Pages: ',
						'after' 			=> '</div>',
						'link_before' 		=> '<span>',
						'link_after'       	=> '</span>',
						'pagelink' 			=> '%',
						'echo' 				=> 1 
					) );
					
						
					$tag = get_the_tags();
					if (! $tag) { ?>
					 <div class='tags'><?php _e( 'Categories: ', 'simplecatch' ); ?> <?php the_category(', '); ?> </div>
					<?php } else { 
							 the_tags( '<div class="tags"> Tags: ', ', ', '</div>'); 
					 } ?>
                     <?php comments_template(); ?> 
                <div class="row-end"></div>
            </div> <!-- .post --> 
                 
        <?php endwhile; ?>
                                           
        	<div class="row-end"></div>

    </div><!-- #content-->
        
	<?php get_sidebar(); ?>  
             
</div><!-- #main-->
    
<?php get_footer(); ?>