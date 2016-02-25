<?php
/**
 * Class for rendering archive
 * @author Danial Bleile
 * @version 0.0.1
 */
class CAHNRSWP_Forms_Display {
	
	public function __construct(){
	} // end __construct
	
	public function get_archive_display(){
		
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$post_items = $this->get_sorted_posts();
		
		$html = '<div class="cahnrs-forms-finder">';
		
		foreach( $post_items as $post_type => $type ){
			
			if ( count( $type['posts'] ) ){
			
			$html .= '<div class="cahnrs-forms-finder-section">';
			
				$html .= '<h2>' . $term->name . ': ' . $type['title'] . '</h2>';
				
				$html .= '<ul>';
				
				foreach( $type['posts'] as $post ){
						
						$html .= '<li>';
							
							$html .= '<h5><a href="' . $post['link'] . '">' . $post['title'] . '</a></h5>';
							
							$html .= '<div class="excerpt">' . $post['excerpt'] . '</div>';
						
						$html .= '</li>';
					
				} // end foreach
				
				$html .= '</ul>';
			
			$html .= '</div>';
			
			} // end if
			
		} // end foreach;
		
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_archive_display
	
	public function get_sorted_posts(){
		
		$post_items = array(
			'how-to'   => array( 'title' => 'How-To Guides' , 'posts' => array() ),
			'form'     => array( 'title' => 'Forms & Documents' , 'posts' => array()),
			'policy'   => array( 'title' => 'Policies & Procedures' , 'posts' => array()),
			'resource' => array( 'title' => 'Additional Resources' , 'posts' => array())
		);
		
		if ( have_posts() ){
			
			while ( have_posts() ){
				
				$post_item = array();
				
				the_post();
				
				$post_item['title'] = get_the_title();
				
				$post_item['content'] = get_the_content();
				
				$post_item['excerpt'] = wp_trim_words( strip_tags( strip_shortcodes( get_the_excerpt() ) ), 15 ,  '...' );
				
				$post_item['link'] = get_post_permalink();
				
				switch( get_post_type() ){
					
					case 'how_to':
						$post_items['how-to']['posts'][] = $post_item;
						break;
					case 'policy':
						$post_items['policy']['posts'][] = $post_item;
						break;
					case 'forms':
						$post_items['forms']['posts'][] = $post_item;
						break;
					case 'resource':
						$post_items['resource']['posts'][] = $post_item;
						break;
					
				} // end switch
				
			} // end while
			
		} // end if
		
		return $post_items;
		
	}// end get_sorted_posts
	
	public function get_display(){
		
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
	}
	
} // end CAHNRSWP_Forms_Display