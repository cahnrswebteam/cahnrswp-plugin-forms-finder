<?php
/**
 * Handles Ajax Calls
 * @author Danial Bleile
 * @version 0.0.1
 */
class CAHNRSWP_Forms_Ajax{
	
	public function __construct(){
		
		add_action( 'wp_ajax_nopriv_do_search', array( $this , 'do_search' ) );
		
		add_action( 'wp_ajax_do_search', array( $this , 'do_search' ) );
		
	} // end __construct
	
	public function do_search(){
		
		$this->search_query( $_POST['fs_search'] );
		
		die();
		
		
	} // end do_search
	
	public function search_query( $search_str ){
		
		$args = array(
			'post_type' => array( 'how_to','policy','forms','resource' ),
			's' => $search_str,
			'posts_per_page' => 40,
		);
		
		$the_query = new WP_Query( $args );
		
		if ( $the_query->have_posts() ) {
			
			while ( $the_query->have_posts() ) {
				
				$the_query->the_post();
				
				echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				
			} // end while
	
		} else {
			
			echo '<a href="#">No Results Found...</a>';
			
		}
		/* Restore original Post Data */
		wp_reset_postdata();
		
	} // end search
	
} // end CAHNRSWP_Forms_Resource