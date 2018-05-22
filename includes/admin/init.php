<?php

function fbr_admin_init(){
  include( 'enqueue.php' );
  include( 'options-page.php' );
  include( 'fb-login-cb.php' );

  add_action( 'admin_enqueue_scripts', 'fbr_admin_enqueue' );
  add_action( 'admin_post_fbr_fb_login_cb', 'fbr_fb_login_cb' );
}