<?php

function fbr_deactivate_plugin(){
  if( version_compare( get_bloginfo( 'version' ), '4.5', '<' ) ){
    wp_die( __( 'You must update Wordpress to use this plugin', 'fbsr' ) );
  }

  // Setup Option Page fields
  $fbr_opts = get_option( 'fbr_opts' );

  if( $fbr_opts ){
    delete_option( 'fbr_opts' );
  }
}