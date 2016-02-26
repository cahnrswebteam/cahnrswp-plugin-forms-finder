<?php
/**
 * Class for forms finder topic list
 */
class CAHNRSWP_Forms_Finder_Topics extends CAHNRSWP_Forms_Finder_Terms {
	
	/**
	 * Gets html for topics area
	 */
	public function get_topics( $taxonomy , $settings = array() , $class = '' ){
		
		$html = '';
		
		$terms = $this->get_terms_array( 'admin_topics' );
		
		$html .= '<ul class="cahnrs-form-finder-topic-list ' . $class . '">';
		
		foreach( $terms as $term ){
			
			$html .= '<li><a href="#" class="cahnrswp-topic">' . $term->name . '</a>';
			
			$html .= '<ul>';
			
			foreach( $term->children as $child ){
				
				$html .= '<li><h5>' . $child->name . '</h5>';
				
				$html .= '<ul>';
				
				$posts = $this->get_term_query( 'admin_topics' , $child->term_id );
				
				foreach( $posts as $post ){
					
					$html .= '<li><a href="' . $post['link'] . '">' . $post['title'] . '</a></li>';
					
				} // end foreach
				
				$html .= '</ul>';
				
				$html .= '</li>';
				
			} // end foreach
			
			$html .= '</ul>';
			
			$html .= '</li>';
			
		} // end foreach
		
		$html .= '</ul>';
		
		return $html;
 		
	} // end get_topics
	
} // end CAHNRSWP_Forms_Finder_Topics
