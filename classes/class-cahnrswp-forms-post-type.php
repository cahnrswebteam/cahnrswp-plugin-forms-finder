<?php
/**
 * Adds properties and methods associated with post type
 * @author Danial Bleile
 * @version 0.0.1
 */
class CAHNRSWP_Forms_Post_Type extends CAHNRSWP_Forms_Save {
	
	// @var string $name Name used prepended to input names
	protected $name = '_cahnrswp_forms';
	
	// @var string $slug ID of the post type
	protected $slug;
	
	// @var string $url Slug to use of url rewrite
	protected $url;
	
	// @var string $label Label to use for the post type
	protected $label;
	
	// @var string $desc Desctipiton of the post type
	protected $desc;
	
	protected $fields = array(
		'_cahnrswp_forms_redirect' => 'text',
		);
		
	/**
	 * Get (input) name
	 * @return string (input) name
	 */
	public function get_name(){ return $this->name; } 
	
	/**
	 * Get post type slug
	 * @return string post type slug
	 */
	public function get_slug(){ return $this->slug; } 
	
	/**
	 * Get label for post type
	 * @return string post type label
	 */
	public function get_label(){ return $this->label; } 
	
	/**
	 * Get description for post type
	 * @return string post type description
	 */
	public function get_desc(){ return $this->desc; }
	
	/**
	 * Get label for post type
	 * @return string post type label
	 */
	public function get_url(){ return $this->url; }
	
	/**
	 * Get fields for post type
	 * @return string post type fields
	 */
	public function get_fields(){ return $this->fields; }
	
	/**
	 * Init the post type
	 */
	public function init(){
		
		add_action( 'init' , array( $this , 'add_post_type' ), 20 );
		
		add_action( 'edit_form_after_title' , array( $this , 'get_settings_form' ), 1 );
		
		add_action( 'save_post_' . $this->get_slug(), array( $this , 'save' ), 10, 2 );
		
		add_action( 'template_redirect', array( $this , 'redirect' ), 1 );
		
	} // end init()
	
	/**
	 * Adds custom post type
	 */
	public function add_post_type(){
		
		$args = array(
			'label'              => $this->get_label(),
			'description'        => $this->get_desc(),
			'public'             => true,
			'rewrite'            => array( 'slug' => $this->get_url() ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail'),
			'taxonomies'         => array('post_tag','admin_topics','category'),
		);
	
		register_post_type( $this->get_slug(), $args );
		
	} // end add post type
	
	/**
	 * Gets html for settings metabox
	 * @param object $post WP Post obejct
	 * @return string HTML for the redirect field
	 */
	public function get_settings_form( $post ){
		
		if ( $post->post_type == $this->get_slug() ){
			
			$settings = $this->get_meta_settings( $post->ID , $this->get_fields() );
		
			$html = '<fieldset id="cahnrswp-forms-settings">';
			
				$html .= '<div class="cahnrswp-forms-field cahnrswp-forms-source">';
					
					$html .= '<label>Document/Resource URL (Optional)</label>';
					
					$html .= '<input type="text" name="_cahnrswp_forms_redirect" value="' . $settings['_cahnrswp_forms_redirect'] . '" placeholder="Document or webpage address" />';
					
					$html .= '<span class="cahnrswp-forms-helper-text">If the document or webpage is not located on this site, use this fieldto redirect visitors to the resource.</span>';
			
				$html .= '</div>';
				
				$html .= '<div class="cahnrswp-forms-field cahnrswp-forms-get-documents">';
					
					$html .= '<label>Load Document</label>';
					
					$documents = $this->get_document_revisions( $post );
					
					$disable = ( $documents )? '' : 'disabled';
					
					$html .= '<select ' . $disable . '>';
					
						$html .= '<option value="">Select</option>';
						
						if ( $documents ){
							
							foreach( $documents as $did => $document ){
								
								$html .= '<option value="' . $document['url'] . '">' . $document['title'] . '</option>';
								
							} // end foreach
							
						} // end if
					
					$html .= '</select>';
			
				$html .= '</div>';
				
				$html .= '<div class="cahnrswp-forms-field">';
					
					$html .= '<label>Summary/Usage Guide (Optional but Recommended)</label>';
					
					$html .= '<textarea name="excerpt">' . $post->post_excerpt . '</textarea>';
					
					$html .= '<span class="cahnrswp-forms-helper-text">Brief explanation (one to two sentances) about how, where or when this resource should be used.</span>';
			
				$html .= '</div>';
			
			$html .= '</fieldset>';
			
			echo $html;
		
		} // end if
		
	} //end get_settings_form
	
	/**
	 * Save post_type settings
	 * @param int $post_id ID of saved post
	 */
	public function save( $post_id, $post ){
		
		if ( ! $this->check_can_save() ) return;
		
		$settings = $this->get_post_settings( $this->get_fields(), $defaults = false  );
		
		$this->update_meta( $post_id , $settings );
		
	} // end save
	
	/**
	 * Gets documents post type
	 * @param object $post WP Post Obejct
	 * @return array post_id => array('title' => '','url'=>'' ) 
	 */
	private function get_document_revisions( $c_post ){
		
		$documents = array();
		
		$posts = get_posts( array( 'post_type' => 'document' , 'posts_per_page' => -1 ) );
		
		foreach( $posts as $post ){
			
			$documents[ $post->ID ] = array( 'title' => $post->post_title , 'url' => get_permalink( $post ) );
			
		}
		
		return $documents;
		
	} // end get_document_revisions
	
	/**
	 * Redirects page based on field
	 */ 
	public function redirect(){
		
		global $wp_query;
		
		if( is_single() ){
			
			if ( $wp_query->post->post_type == $this->get_slug() ){
				
				$redirect = get_post_meta( $wp_query->post->ID , '_cahnrswp_forms_redirect' , true );
				
				if ( $redirect ) {
					
					wp_redirect( $redirect );
					
            		header( 'Status: 302' );
					
            		exit;
					
				} // end if
				
			} // end if
			
		} // end if
		
	}
	
} // end CAHNRSWP_Forms_Post_Type