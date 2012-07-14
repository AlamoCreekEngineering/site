<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Simple_Catch
 * @since Simple Catch 1.0
 */
 
 if( is_page_template('sidebar-left.php') ) {?>
	<div id="sidebar" class="col4 no-margin-left">
 <?php } else {?>
 	<div id="sidebar" class="col4">
 	
 <?php } ?>
    
		<?php 
			if ( function_exists( 'dynamic_sidebar' ) ) {
				//displays 'sidebar' for all pages
				dynamic_sidebar( 'sidebar' ); 
            }
        ?>
   	</div><!-- #sidebar -->
   
    <?php if( !is_page_template('sidebar-left.php') ): ?>
		<div class="row-end"></div>
   	<?php endif;?>

    