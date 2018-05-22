<?php

function fbr_enqueue_scripts(){

  wp_register_style(
    'fbr_slider',
    plugins_url( '/assets/styles/slider.css', FBR_PLUGIN_URL )
  );

  wp_enqueue_style( 'fbr_slider' );

  wp_register_script(
    'fbr_slider',
    plugins_url( '/assets/scripts/slider.js', FBR_PLUGIN_URL ),
    array('jquery'),
    '1.0.0',
    true
  );

  wp_enqueue_script( 'fbr_slider' );
}