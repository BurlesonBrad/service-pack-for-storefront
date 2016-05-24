jQuery( document ).ready( function( $ ) {
	
	if ( $( '#ssp-contact-form-send' ).length > 0 ) {
		
		var busy  = null;
		
		$( '#ssp-contact-form-send' ).click( function() {
			
			var form  = $( this ).closest( 'form' ),
					error = null,
					message = null;
					
			if ( busy ) busy.abort();
			busy = $.ajax( {
				url: ssp_contact_form_ajax.url,
				type: 'POST',
				data: form.serialize(),
				success: function( response ) {
					
					$( 'body' ).append( '<div id="ssp-contact-form-noty"></div>' );
					
					if ( response.success === true ) {
						$( '#ssp-contact-form-noty' ).attr( 'class', 'ssp-contact-form-noty-success' ).html( response.data );
						form.find( '[required]' ).each( function() {
							$( this ).removeAttr( 'style' );
						} );
						form[0].reset();
					}
					else {
						$( '#ssp-contact-form-noty' ).attr( 'class', 'ssp-contact-form-noty-error' ).html( response.data );
						form.find( '[required]' ).each( function() {
						
							if( $.trim( $( this ).val() ) === '' ) {
								$( this ).css( 'border-color', '#FF0000' );
							}
							else {
								$( this ).css( 'border-color', '#44a62b' );
							}
						} );
					}
					$( '#ssp-contact-form-noty' ).slideDown();
					$( '#ssp-contact-form-noty' ).delay( 10000 ).slideUp();
				}
			} );
			
			return false;
		} );
	}
} );
