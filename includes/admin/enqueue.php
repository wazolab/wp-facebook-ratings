<?php

function fbr_admin_enqueue(){
  
  if( !isset($_GET['page']) || $_GET['page'] != 'fbr_plugin_opts' ){
    return;
  }

  wp_register_style(
    'fbr_bootstrap',
    plugins_url( '/assets/styles/bootstrap.min.css', FBR_PLUGIN_URL )
  );

  wp_enqueue_style( 'fbr_bootstrap' );

  wp_register_script(
    'fbr_bootstrap',
    plugins_url( '/assets/scripts/bootstrap.min.js', FBR_PLUGIN_URL ),
    array('jquery'),
    '1.0.0',
    true
  );

  wp_enqueue_script( 'fbr_bootstrap' );
}