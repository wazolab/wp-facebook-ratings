<?php

function fbr_save_options(){
  if( !current_user_can( 'edit_theme_options' ) ){
    wp_die( __( 'You are not allowed to access this page.', 'wp-fb-ratings' ) );
  }

  check_admin_referer( 'fbr_save_options_verify' );

  $fbr_opts = get_option( 'fbr_opts' );

  $fbr_opts['fbr_app_id'] = sanitize_text_field( $_POST['fbr_app_id'] );
  $fbr_opts['fbr_app_secret'] = sanitize_text_field( $_POST['fbr_app_secret'] );

  $fb = new Facebook\Facebook([
    'app_id' => $fbr_opts['fbr_app_id'],
    'app_secret' => $fbr_opts['fbr_app_secret'],
    'default_graph_version' => 'v2.2',
  ]);

  $helper = $fb->getRedirectLoginHelper();

  $permissions = ['email','public_profile','manage_pages','pages_show_list'];
  $loginUrl = $helper->getLoginUrl(admin_url( 'admin-post.php?action=fbr_fb_login_cb' ), $permissions);

  update_option( 'fbr_opts', $fbr_opts );

  wp_redirect( $loginUrl );
  exit;
}