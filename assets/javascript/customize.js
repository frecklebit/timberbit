jQuery( function ( $ ) {

  /**
   * Get the customize control element, inputs and value
   */
  function $setting ( name ) {

    // Validate setting name
    if ( 'string' !== typeof name ) {
      return;
    }

    this.$el     = $( '#customize-control-' + name );
    this.$inputs = this.$el.find( 'input,select,textarea' );
    this.value   = this.$el.find( 'input:checked, select option:checked, input[type="text"], textarea' ).val();

    return this;
  }

  /**
   * Hiding/Showing Off-canvas position in header
   */
  if ( 'offcanvas' === $setting( 'wpt_mobile_menu_layout' ).value ) {
    $setting( 'wpt_offcanvas_position' ).$el.show();
  } else {
    $setting( 'wpt_offcanvas_position' ).$el.hide();
  }

  $setting( 'wpt_mobile_menu_layout' ).$inputs.on( 'change', function () {
    if ( 'offcanvas' === $( this ).val() ) {
      $setting( 'wpt_offcanvas_position' ).$el.show();
    } else {
      $setting( 'wpt_offcanvas_position' ).$el.hide();
    }
  } );

} );
