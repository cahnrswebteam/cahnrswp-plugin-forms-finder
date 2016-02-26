CAHNRS_Forms = {
	
	init: function(){
		
		CAHNRS_Forms.search_bar.init();
		
		CAHNRS_Forms.topics.init();
		
	},
	
	search_bar: {
		
		wrap: jQuery('.cahnrswp-form-finder'),
		
		result_wrap: jQuery('.cahnrswp-form-finder-results'),
		
		init: function(){
			
			CAHNRS_Forms.search_bar.wrap.on( 'keyup' , 'input.cahnrs-search' , function(){ CAHNRS_Forms.search_bar.do_search( jQuery( this ) ); });
			
		},
		
		do_search: function( input ){
			
			var str = input.val();
			
			if ( str.length < 3 )  return;
			
			CAHNRS_Forms.search_bar.do_query( str );
			
		}, // end do_search
		
		do_query: function( str ){
			
			var data = { "action":"do_search","fs_search": str }
			
			console.log( data );
			
			jQuery.post( 
				ajaxurl,
				data, 
				function( response ) {
					
					CAHNRS_Forms.search_bar.result_wrap.html( response );
					
				}
			);
			
		} // end do query
		
	}, // end search bar
	
	topics: {
		
		wrap: jQuery('.cahnrs-form-finder-topic-list'),
		
		init: function(){
			
			CAHNRS_Forms.topics.wrap.on('click', 'a.cahnrswp-topic' , function( event ){ 
				event.preventDefault(); 
				CAHNRS_Forms.topics.toggle( jQuery( this ) )
			});
		},
		
		toggle: function( ic ){
			
			var s = ic.siblings('ul');
			
			var p = ic.closest('li');
			
			s.stop();
			
			s.slideToggle('fast');
			
		},

		
	} // end topics
	
}
CAHNRS_Forms.init();