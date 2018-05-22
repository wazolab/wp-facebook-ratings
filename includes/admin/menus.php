<?php

function fbr_admin_menus(){
  add_menu_page(
    'Facebook Ratings Options',
    'FB Ratings',
    'edit_theme_options',
    'fbr_plugin_opts',
    'fbr_plugin_opts_page'
  );
}