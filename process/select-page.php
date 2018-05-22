<?php

function fbr_select_page() {
  if( !current_user_can( 'edit_theme_options' ) ){
    wp_die( __( 'You are not allowed to access this page.', 'wp-fb-ratings' ) );
  }

  check_admin_referer( 'fbr_select_page_verify' );

  $fbr_opts = get_option( 'fbr_opts' );

  $data = explode(',', $_POST['fbr_page_selected']);

  $fbr_opts['fbr_page_id'] = sanitize_text_field( $data[0] );
  $fbr_opts['fbr_page_access_token'] = sanitize_text_field( $data[1] );

  update_option( 'fbr_opts', $fbr_opts );

  wp_redirect( admin_url( 'admin.php?page=fbr_plugin_opts&status=3' ) );
  exit;
}