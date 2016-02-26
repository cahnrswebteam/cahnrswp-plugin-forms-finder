<?php
/**
 * Adds properties and methods associated with the topic taxonomy
 * @author Danial Bleile
 * @version 0.0.1
 */
class CAHNRSWP_Forms_Topics {
	
	private $slug = 'admin_topics';
	
	/**
	 * Get post type slug
	 * @return string taxonomy slug
	 */
	public function get_slug(){ return $this->slug; } 
	
	/**
	 * Init the post type
	 */
	public function init(){
		
		add_action( 'init' , array( $this , 'add_taxonomy' ), 30 );
		
	} // end init()
	
	/**
	 * Adds custom taxonomy for topics
	 */
	public function add_taxonomy(){
		
		$args = array(
			'label'              => 'Admin Topics',
			'description'        => 'Topics for forms ,policies, and resources.',
			'rewrite'            => array( 'slug' => 'admin-topics' ),
			'hierarchical'       => true,
		);
	
		register_taxonomy( $this->get_slug(), array('how_to','policy','resource' ), $args );
		
	} // end add add_taxonomy
	
} // end CAHNRSWP_Forms_Topics