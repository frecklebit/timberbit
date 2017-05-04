jQuery(document).foundation();

jQuery( function ( $ ) {

  // Since CSS can't traverse up, add class has-border
  // to custom-logo if both logo and header-text exist
  $( '.header-text' ).prev( '.custom-logo' ).addClass( 'has-border' );

} );
