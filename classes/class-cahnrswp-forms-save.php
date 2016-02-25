<?php
/**
 * Methods for saving post data
 * @author Danial Bleile
 * @version 0.0.1
 */
class CAHNRSWP_Forms_Save {
	
	/**
	 * Get settings from $_POST
	 * @param array $fields Field name => data type
	 * @param array|bool $defaults Default values for each key
	 */
	public function get_post_settings( $fields , $defaults = false ){
		
		$settings = array();
		
		foreach( $fields as $key => $type ){
			
			if ( ! empty( $_POST[ $key ] ) ){
				
				$settings[ $key ] = $_POST[ $key ];
				
			} else {
				
				$settings[ $key ] = '';
				
			}// end if
			
		} // end foreach
		
		return $settings;
		
	} // end get_post_settings
	
	
	/**
	 * Get settings from post meta
	 * @param int $post_id Id of the post
	 * @param array $fields Field name => data type
	 * @param string $prefix Prefix of settings if is array: prefix[setting]
	 * @param array|bool $defaults Default values for each key
	 */
	public function get_meta_settings( $post_id , $fields , $defaults = false ){
		
		$settings = array();
		
		if ( ! is_array( $fields ) ){
			
			$fields = array( $fields => 'null' );
			
		} // end fields
		
		foreach( $fields as $key => $type ){
				
			$settings[ $key ] = get_post_meta( $post_id , $key , true );
			
		} // end foreach
		
		return $settings;
		
	} // end get_meta_settings
	
	/**
	 * Check if should save and user has permission to do so
	 * @return bool TRUE if save FALSE if don't
	 */
	protected function check_can_save(){
		
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return false;

		} // end if
		
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return false;

		} // end if

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {

			return false;

			} // end if

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {

				return false;

			} // end if

		} // end if
		
		return true;
		
	} // end check_can_save
	
	public function update_meta( $post_id , $settings ){
		
		if ( ! $this->check_can_save() ) return;
		
		foreach( $settings as $key => $value ){
			
			update_post_meta( $post_id , $key , $value );
			
		} // end foreach
		
	} 
	
	
} // end CAHNRSWP_Forms_Resource