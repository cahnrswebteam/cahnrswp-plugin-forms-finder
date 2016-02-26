<?php
/*
Plugin Name: CAHNRS Forms Finder
Plugin URI: http://cahnrs.wsu.edu/communications
Description: Orgainize and display forms, policies, & Resources.
Author: cahnrscommunications, Danial Bleile
Author URI: http://cahnrs.wsu.edu/communications
Version: 0.0.1
*/

class CAHNRSWP_Forms_Finder {
	
	/** @var object $instance Current instance */
	private static $instance;
	
	/** @var object $how_to instance of CAHNRSWP_Forms_How_To */
	public $how_to;
	
	/** @var object $policy instance of CAHNRSWP_Forms_Policy */
	public $policy;
	
	/** @var object $resource instance of CAHNRSWP_Forms_Resource */
	public $resource;
	
	/** @var object $resource instance of CAHNRSWP_Forms_Forms */
	public $forms;
	
	/** @var object $display instance of CAHNRSWP_Forms_Display */
	public $display;
	
	/** @var object $topics instance of CAHNRSWP_Forms_Topics */
	public $topics;
	
	/**
	 * Get instance of forms finder
	 */
	public static function get_instance(){
		
		if ( null == self::$instance ){
			
			self::$instance = new self;
			
			self::$instance->init();
			
		} // end if
		
		return self::$instance;
		
	} // end get_instance
	
	/**
	 * Set properties and call methods at initialization of the class
	 * @return void
	 */
	private function init(){
		
		require_once 'classes/class-cahnrswp-forms-save.php';
		require_once 'classes/class-cahnrswp-forms-post-type.php';
		require_once 'classes/class-cahnrswp-forms-shortcodes.php';
		require_once 'classes/class-cahnrswp-forms-how-to.php';
		require_once 'classes/class-cahnrswp-forms-policy.php';
		require_once 'classes/class-cahnrswp-forms-resource.php';
		require_once 'classes/class-cahnrswp-forms-forms.php';
		require_once 'classes/class-cahnrswp-forms-display.php';
		require_once 'classes/class-cahnrswp-forms-topics.php';
		require_once 'classes/class-cahnrswp-forms-ajax.php';
		require_once 'classes/class-cahnrswp-forms-finder-terms.php';
		
		$this->how_to = new CAHNRSWP_Forms_How_To();
		$this->policy = new CAHNRSWP_Forms_Policy();
		$this->resource = new CAHNRSWP_Forms_Resource();
		$this->forms = new CAHNRSWP_Forms_Forms();
		$this->display = new CAHNRSWP_Forms_Display();
		$this->topics = new CAHNRSWP_Forms_Topics();
		
		
		$this->how_to->init();
		$this->policy->init();
		$this->resource->init();
		$this->forms->init();
		$this->topics->init();
		
		$shortcodes = new CAHNRSWP_Forms_Shortcodes();
		$shortcodes->add_shortcodes();
		
		$ajax = new CAHNRSWP_Forms_Ajax();
		
		if ( is_admin() ){
			
			add_action( 'admin_enqueue_scripts', array( $this , 'add_admin_scripts') ); 
		
		} else {
			
			add_action( 'wp_enqueue_scripts', array( $this , 'add_public_scripts') ); 
			
		} // end if
		
	} // end init
	
	/**
	 * Method called on the admin_enqueue_scripts action
	 */
	public function add_admin_scripts(){
		
		wp_enqueue_style( 'cahnrswp_forms_admin_css' , plugin_dir_url( __FILE__ ) . '/css/admin.css' , false , '0.0.1' );  
		
	} // end add_admin_scripts
	
	/**
	 * Method called on the wp_enqueue_scripts action
	 */
	public function add_public_scripts(){
		
		wp_enqueue_style( 'cahnrswp_forms_public_css' , plugin_dir_url( __FILE__ ) . '/css/public.css' , false , '0.0.1' );  
		
		wp_enqueue_script( 'cahnrswp_forms_public_script' , plugin_dir_url( __FILE__ ) . '/js/script.js' , false , '0.0.1' , true ); 
		
	} // end add_admin_scripts
	

} // end CAHNRSWP_Forms_Finder

CAHNRSWP_Forms_Finder::get_instance();
