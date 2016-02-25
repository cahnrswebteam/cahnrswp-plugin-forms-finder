<?php
/**
 * Adds custom shortcodes for the forms finder
 * @author Danial Bleile
 * @version 0.0.1
 */
class CAHNRSWP_Forms_Shortcodes { 
	
	/**
	 * Add custom shortcodes
	 */
	public function add_shortcodes(){
		
		add_shortcode( 'post_type_gallery' , array( $this , 'post_type_gallery' ) );
		
		add_shortcode( 'form_finder' , array( $this , 'get_form_finder' ) );
		
		add_shortcode( 'form_finder_library' , array( $this , 'get_form_finder_library' ) );
		
	} // end add add_taxonomy
	
	/**
	 * Adds Topic Gallery Shortcode
	 * @param array $att Shortcode attributes
	 * @return string HTML for shortcode
	 */
	public function get_topics_gallery( $atts ){
		
		$defaults = array(
			'sections' => 'Guides=how_to,Policies & Procedures=policy,Resources=resource',
			);
		
		$settings = shortcode_atts( $defaults , $atts );
		
		
	} // end get_topics_gallery
	
	/**
	 * Adds form_finder shortcodes
	 * @param array $att Shortcode attributes
	 * @return string HTML for shortcode
	 */
	public function get_form_finder( $atts ){
		
		$html = '<form class="cahnrswp-form-finder">';
		
			$html .= '<nav>';
				
				$html .= '<a href="#" class="selected">Search</a>';
				
				$html .= '<a href="#">By Topic</a>';
				
				$html .= '<a href="#">A-Z Index</a>';
			
			$html .= '</nav>';
			
			$html .= '<fieldset class="cahnrswp-form-finder-section" data-type="search">';
			
				$html .= '<div class="cahnrswp-field">';
				
					$html .= '<input type="text" value="" name="" placeholder="Search Forms, Policies & Resources" />';
				
				$html .= '</div>';
			
			$html .= '</fieldset>';
			
			$html .= '<fieldset class="cahnrswp-form-finder-section" data-type="By Topic">';
			
			$html .= '</fieldset>';
			
			$html .= '<fieldset class="cahnrswp-form-finder-section" data-type="a-z-index">';
			
			$html .= '</fieldset>';
		
		$html .= '</form>';
		
		return $html;
		
	} // end get_form_finder
	
	/**
	 * Gets page view for library
	 * @param array $att Shortcode attributes
	 * @return string HTML for shortcode
	 */
	public function get_form_finder_library( $atts ){
		
		$defaults = array( 
			'term'    => false,
			'display' => 'basic',
		);
		
		$settings = shortcode_atts( $defaults , $atts );
		
		$children= $this->get_sections( $settings['term'] );
		
		$html = '<div class="cahnrswp-form-finder-library fs-library-' . $settings['display'] . '">';
		
		switch( $settings['display'] ){
			
			default:
				$html .= $this->get_basic_display( $children );
				break;
			
		} // end switch
		
		$html .= '</div>';
		
		return $html;
		
	}
	
	public function get_sections( $term_slug ){
		
		$term = get_term_by( 'slug' , $term_slug , 'findertopic');
		
		$children = get_term_children( $term->term_id, 'findertopic' );
		
		$term_children = array();
		
		foreach( $children as $child_id ){
			
			$term_children[] = $this->get_child( $child_id );
			
		} // end foreach
		
		return $term_children;
		
	}
	
	public function get_child( $id ){
		
		$term = get_term( $id , 'findertopic' );
		
		$fs_query_args = array(
			'post_type' => 'any',
			'tax_query' => array(
				array(
					'taxonomy' => 'findertopic',
					'field'    => 'id',
					'terms'    => $id,
				),
			),
		);
		
		$child = array(
			'title' => $term->name,
			'posts' => array(),
		);
		
		
		$fs_query = new WP_Query( $fs_query_args );
		
		// The Loop
		if ( $fs_query->have_posts() ) {
			
			while ( $fs_query->have_posts() ) {
				
				$child_post = array();
				
				$fs_query->the_post();
				
				$child_post['title'] = get_the_title();
				
				$child_post['content'] = get_the_content();
				
				$child_post['excerpt'] = wp_trim_words( strip_tags( strip_shortcodes( get_the_excerpt() ) ), 15 ,  '...' );
				
				$child_post['link'] = get_post_permalink();
				
				$child['posts'][] = $child_post;
			}
			
		} // end if
		
		/* Restore original Post Data */
		wp_reset_postdata();

		
		return $child;
		
	} // end get_child
	
	public function get_basic_display( $children ){
		
		$html = '';
		
		foreach( $children as $child ){
			
			$html .= '<div class="fs-library-section">';
		
				$html .= '<h5>' . $child['title'] . '</h5>';
				
				$html .= '<ul>';
				
				foreach( $child['posts'] as $index => $post ){
					
					//if ( $index >= ( count( $child['posts'] ) * 0.5 ) ) $html .= '</ul><ul>';
					
					$html .= '<li>';
					
						$html .= '<div class="fs-title"><a href="' . $post['link'] . '">' . $post['title'] . '</a></div>';
						
						//$html .= '<div class="fs-excerpt">' . $post['excerpt'] . '</div>';
					
					$html .= '</li>';
					
				} // end foreach
				
				$html .= '</ul>';
			
			$html .= '</div>';
			
		} // end foreach
		
		return $html;
		
	} // end get_basic_display
	
	
} // end CAHNRSWP_Forms_Topics