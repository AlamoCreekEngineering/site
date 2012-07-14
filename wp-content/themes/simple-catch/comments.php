<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. 
 *
 * @package WordPress
 * @subpackage Simple_Catch
 * @since Simple Catch 1.0
 */
 ?>
	
    <?php 
	// Do not delete these lines 
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
			
	// Standard WordPress comments security
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'simplecatch' ); ?></p>
		<?php return;
	} ?>
	
	<?php // You can start editing here -- including this comment! 
	if ( have_comments() ): ?>
		<div id="comments">	
            <h2 id="comments-title">
                <?php comments_number('No Comments', 'One Comment', '% Comments' );?>
            </h2><!-- #comments -->
    
            <div class="navigation clearfix">
                <div class="alignleft">
                    <?php previous_comments_link();?>
                </div>
                <div class="alignright">
                    <?php next_comments_link(); ?>
                </div>
            </div> <!-- .navigation -->
    
            <ul class="commentlist">
                <?php wp_list_comments();?>
            </ul>
            
             <div class="navigation clearfix">
                <div class="alignleft">
                    <?php previous_comments_link();?>
                </div>
                <div class="alignright">
                    <?php next_comments_link(); ?>
                </div>
            </div> <!-- .navigation -->
            
     	</div><!-- .comment-wrap -->            
	<?php else: // this is displayed if there are no comments so far

		if (comments_open()): // If comments are open, but there are no comments.
          
		else: // comments are closed ?>
           <p class="nocomments"><?php _e( 'Comments are closed.', 'simplecatch' );?></p>
    	<?php endif; 
		
	endif;?>
	
 	<?php 
	if (comments_open()): // The comment form 

		$req = get_option( 'require_name_email' );
	    $aria_req = ( $req ? " aria-required='true'" : '' );
		$fields =  array(
			'author'	=>	'<label>Name</label><input type="text" class="text" placeholder="'.esc_attr( 'Name ( required )' ).'" name="author"'. $aria_req .' />',
			'email' 	=>  '<label>Email </label><input type="text" class="text" placeholder="'.esc_attr( 'Email ( required )' ).'" name="email"'. $aria_req .' />',
			'url'    	=>	 '<label>Website </label><input type="text" class="text" placeholder="'.esc_attr( 'Website' ).'" name="subject"'. $aria_req .' />' 
		);
			
		$args = array(
			'title_reply'          => 	__( 'Leave a Comment', 'simplecatch' ),
			'comment_notes_before' =>	 '',
			'comment_field'        => 	'<label>Comment</label><textarea name="comment" id="comment" rows="10" tabindex="4"></textarea>',
			'label_submit'         =>	 __( 'Submit','Submit' ),
			'comment_notes_after'  => 	'',
			'fields'               => 	apply_filters( 'comment_form_default_fields', $fields )
			 );
		
		comment_form($args);  
	endif; // if you delete this the sky will fall on your head ?>