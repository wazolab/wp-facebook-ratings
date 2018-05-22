<?php 

function fbr_plugin_opts_page(){

  $fbr_opts = get_option( 'fbr_opts' );
  extract( $fbr_opts );

  $isSetup = ( $fbr_app_id && $fbr_app_secret && isset( $_SESSION['fb_access_token'] ) ) ? true : false;

  if( $isSetup ) {

    $fb = new Facebook\Facebook([
      'app_id' => $fbr_app_id,
      'app_secret' => $fbr_app_secret,
      'default_graph_version' => 'v2.2',
      'default_access_token' => $_SESSION['fb_access_token']
    ]);

    try {
      // Get the \Facebook\GraphNodes\GraphUser object for the current user.
      // If you provided a 'default_access_token', the '{access-token}' is optional.
      $response = $fb->get('/me/accounts');
    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
    
    $graphEdge = $response->getGraphEdge();
  }
  ?>
  <div class="wrap">
    <h2>Facebook Ratings</h2>
    <p>Connectez-vous et autorisez Facebook Ratings à accéder à vos Pages.</p>
    <?php if( isset( $_GET['status'] ) ) {
      switch( $_GET['status'] ){
        case 1:
          fbr_create_alert('Options sauvegardées !');
          break;
        case 2:
          fbr_create_alert('Connexion à Facebook réussie !');
          break;
        case 3:
          fbr_create_alert('Page enregistrée avec succès !');
          break;
        default:
          break;
      }
    } ?>
    <div class="card">
      <div class="card-body">
        
        <?php if( $isSetup ) { ?>
          <form method="POST" action="admin-post.php">
            <input type="hidden" name="action" value="fbr_select_page" />
            <?php wp_nonce_field( 'fbr_select_page_verify' ); ?>
            <div class="form-group">
              <label for="user-page-select">
                <?php _e( 'Mes Pages: ', 'wp-fb-ratings' ); ?>
              </label>
              <select id="user-page-select" class="form-control" name="fbr_page_selected" required>              
                <option selected>Choisissez une Page</option>
                <?php foreach( $graphEdge as $graphNode ) {
                  $isSelected = null;
                  if( $fbr_page_id == $graphNode->getField( 'id' ) ) $isSelected = 'selected';
                  echo '<option '. $isSelected .' value="'. $graphNode->getField( 'id' ) .','. $graphNode->getField( 'access_token' ) .'">'. $graphNode->getField( 'name' ) .'</option>';
                } ?>
              </select>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <?php _e( 'Enregistrer les modifications', 'wp-fb-ratings' ); ?>
              </button>
            </div>
          </form>

        
        <?php } else { ?>
        
          <form method="POST" action="admin-post.php">
            <input type="hidden" name="action" value="fbr_save_options" />
            <?php wp_nonce_field( 'fbr_save_options_verify' ); ?>
            <div class="alert alert-secondary" role="alert">
              Plus d'infos sur commence créer un App: <a href="https://developers.facebook.com/docs/apps/register?locale=fr_FR" target="_blank">ici</a>
            </div>
            <div class="form-group">
              <label for="fbr-app-id">
                <?php _e( "ID de l'App : ", 'wp-fb-ratings' ); ?>
              </label>
              <input
                id="fbr-app-id"
                type="text"
                class="form-control"
                name="fbr_app_id"
                value="<?php echo ($fbr_app_id) ? $fbr_app_id : null; ?>"
                placeholder="Ex: 012345678901234"
                minlength="15"
                maxlength="15"
                required />
            </div>
            <div class="form-group">
              <label for="fbr-app-secret">
                <?php _e( "Clé Secrète de l'App", 'wp-fb-ratings' ); ?>
              </label>
              <input
                id="fbr-app-secret"
                type="password"
                class="form-control"
                name="fbr_app_secret"
                value="<?php echo ($fbr_app_secret) ? $fbr_app_secret : null; ?>"
                placeholder="Ex: 3ee4c5282f968b1e32d170cf7f2fe78e"
                minlength="32"
                maxlength="32"
                required />
            </div>
            <hr class="my-4">
            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <?php _e( 'Connecter mon App', 'wp-fb-ratings' ); ?>
              </button>
            </div>
          </form>

        <?php } ?>

      </div>
    </div>
  </div>
  <?php
}

function fbr_create_alert( $content ){
  $html = '<div class="alert alert-success" role="alert">';
  $html .= __( $content, 'wp-fb-ratings' );
  $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
  $html .= '</div>';
  echo $html;
}