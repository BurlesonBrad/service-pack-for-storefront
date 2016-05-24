jQuery( document ).ready( function( $ ) {
	
	if ( $( '.ssp-widget-newsletter-send' ).length > 0 ) {
		
		var busy  = null;
		
		$( '.ssp-widget-newsletter-send' ).click( function() {
			
			var form  = $( this ).closest( 'form' );
			
			if ( busy ) busy.abort();
			busy = $.ajax( {
				url: ssp_widget_newsletter_ajax.url,
				type: 'POST',
				data: form.serialize(),
				success: function( response ) {
					
					$( 'body' ).append( '<div id="ssp-widget-newsletter-noty"></div>' );
					
					if ( response.success === true ) {
						$( '#ssp-widget-newsletter-noty' ).attr( 'class', 'ssp-widget-newsletter-noty-success' ).html( response.data );
						form[0].reset();
					}
					else {
						$( '#ssp-widget-newsletter-noty' ).attr( 'class', 'ssp-widget-newsletter-noty-error' ).html( response.data );
					}
					$( '#ssp-widget-newsletter-noty' ).slideDown();
					$( '#ssp-widget-newsletter-noty' ).delay( 10000 ).slideUp();
				}
			} );
			
			return false;
		} );
	}
} );
