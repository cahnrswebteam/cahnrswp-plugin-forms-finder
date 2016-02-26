<?php
/**
 * Base class for forms finder term list
 */
class CAHNRSWP_Forms_Finder_Terms {
	
	/**
	 * Gets array of terms
	 */
	public function get_terms_array( $taxonomy_slug , $settings = array() ){
		
		$terms = get_terms( $taxonomy_slug , array( 'parent' => 0 ) );
		
		foreach( $terms as $key => $term ){
			
			$terms[ $key ]->children = get_terms( $taxonomy_slug , array( 'parent' => $term->term_id ) );
			
		} // end foreach
		
		return $terms;
		
	} // end get_topics
	
	public function get_term_query( $taxonomy , $term_id ){
		
		$term_posts = array();
		
		$query_args = array(
			'post_type' => 'any',
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'id',
					'terms'    => $term_id,
				),
			),
		);
		
		$query = new WP_Query( $query_args );
		
		// The Loop
		if ( $query->have_posts() ) {
			
			while ( $query->have_posts() ) {
				
				$query->the_post();
				
				$term_post = array();
				
				$term_post['title'] = get_the_title();
				
				$term_post['content'] = get_the_content();
				
				$term_post['excerpt'] = wp_trim_words( strip_tags( strip_shortcodes( get_the_excerpt() ) ), 15 ,  '...' );
				
				$term_post['link'] = get_post_permalink();
				
				$term_posts[] = $term_post;
			}
			
		} // end if
		
		/* Restore original Post Data */
		wp_reset_postdata();
		
		return $term_posts;
		
	}
	
} // end CAHNRSWP_Forms_Finder_Topics
