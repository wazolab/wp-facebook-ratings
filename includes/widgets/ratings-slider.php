<?php

class FB_Ratings_Slider_Widget extends WP_Widget
{
  /**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'description' => 'Displays Page Ratings into a neat slider.',
		);
		parent::__construct(
    	'fbr_ratings_slider_widget',
      'FB Ratings Slider',
      $widget_ops
    );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
    // outputs the options form on admin
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
    // processes widget options to be saved
    $instance = [];

		$fbr_opts = get_option( 'fbr_opts' );
		extract( $fbr_opts );

		$fbr_opts['fbr_ratings'] = array();
    
    $fb = new Facebook\Facebook([
      'app_id' => $fbr_app_id,
      'app_secret' => $fbr_app_secret,
      'default_graph_version' => 'v2.2',
    ]);

    try {
      // Get the \Facebook\GraphNodes\GraphUser object for the current user.
      // If you provided a 'default_access_token', the '{access-token}' is optional.
      $response = $fb->get('/'. $fbr_page_id .'/ratings?limit=15&fields=reviewer,has_rating,has_review,review_text,rating,created_time', $fbr_page_access_token);
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

		
		foreach( $graphEdge as $graphNode ) {
			if( $graphNode->getField( 'has_rating' ) && $graphNode->getField('has_review') ){
				array_push($fbr_opts['fbr_ratings'], $graphNode->asArray());
			}
		}

		error_log(print_r($fbr_opts['fbr_ratings'], true));

		update_option( 'fbr_opts', $fbr_opts );

    return $instance;
  }
  
  /**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		extract( $args );
		// extract( $instance );

		$fbr_opts = get_option( 'fbr_opts' );
		extract( $fbr_opts );

		echo $before_widget;

		?>
		<div id="ratings-slider" class="carousel slide" data-ride="false">
			<img class="slider-bg d-block w-100" src="<?php echo plugin_dir_url( FBR_PLUGIN_URL ) . '/assets/images/ratings-slider-placeholder.jpg'; ?>" alt="FB Ratings">
			<ol class="carousel-indicators">
				<?php foreach( $fbr_ratings as $key => $rating ) : ?>
					<li
						data-target="#ratings-slider"
						data-slide-to="<?php echo $key; ?>"
						class="<?php echo ( $key == 0 ) ? 'active' : ''; ?>"
					>
						<?php
						if( $key == 0 ) {
							echo jouvanceau_get_svg( array( 'icon' => 'dot-plain', 'id' => '', 'class' => 'd-block' ) );
						} else {
							echo jouvanceau_get_svg( array( 'icon' => 'dot-empty', 'id' => '', 'class' => 'd-block' ) );
						}
						?>
					</li>
				<?php endforeach; ?>
			</ol>
			<div class="carousel-inner">
				<?php foreach( $fbr_ratings as $key => $rating ) : ?>
					<div class="carousel-item <?php echo ( $key == 0 ) ? 'active' : ''; ?>">
						<div class="carousel-caption">
							<h2><?php fbr_get_stars_display( $rating['rating'] ); ?></h2>
							<p>
								<?php if( strlen( $rating['review_text'] ) > 255 ) {
									echo substr( $rating['review_text'], 0, 255 ) . '...';
								} else {
									echo $rating['review_text'];
								} ?>
							</p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<a class="carousel-control-prev" href="#ratings-slider" role="button" data-slide="prev">
			<?php echo jouvanceau_get_svg( array( 'icon' => 'left-arrow', 'id' => '', 'class' => '' ) ); ?>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#ratings-slider" role="button" data-slide="next">
				<?php echo jouvanceau_get_svg( array( 'icon' => 'right-arrow', 'id' => '', 'class' => '' ) ); ?>
				<span class="sr-only">Next</span>
			</a>
		</div>

		<?php
		echo $after_widget;

	}
}

function fbr_get_stars_display( $rating ){
	$html = '';

	for( $i = 0; $i < 5; $i++ ){
		if( $rating > $i ) {
			$html .= '<span>' . jouvanceau_get_svg( array( 'icon' => 'star-plain', 'id' => '', 'class' => '' ) ) . '</span>';
		} else {
			$html .= '<span>' . jouvanceau_get_svg( array( 'icon' => 'star-empty', 'id' => '', 'class' => '' ) ) . '</span>';
		}
	}

	echo $html;
}