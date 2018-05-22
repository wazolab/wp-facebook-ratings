<?php

function fbr_activate_plugin(){
  if( version_compare( get_bloginfo( 'version' ), '4.5', '<' ) ){
    wp_die( __( 'You must update Wordpress to use this plugin', 'fbsr' ) );
  }

  // Setup Option Page fields
  $fbr_opts = get_option( 'fbr_opts' );

  if( !$fbr_opts ){
    $opts = [
      'fbr_app_id' => null,
      'fbr_app_secret' => null,
      'fbr_page_id' => null,
      'fbr_page_access_token' => null,
      'fbr_ratings' => array(),
    ];

    add_option( 'fbr_opts', $opts );
  }
}