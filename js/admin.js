var CAHNRSWP = {
	
	wrap: jQuery('#cahnrswp-forms-settings'),
	
	init: function(){
		
		CAHNRSWP.url.init();
		
	},
	
	url:  {
		
		init: function(){
			
			CAHNRSWP.wrap.on('change','.cahnrswp-forms-get-documents select' , function(){ CAHNRSWP.url.set_url( jQuery( this ).val() );});
		},
		
		set_url: function( url ){
			
			CAHNRSWP.wrap.find('.cahnrswp-forms-source input').val( url );
			
		},
		
	}
	
}
CAHNRSWP.init();