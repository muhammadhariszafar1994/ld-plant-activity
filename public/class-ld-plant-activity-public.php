<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    LD_Plant_Activity
 * @subpackage LD_Plant_Activity/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    LD_Plant_Activity
 * @subpackage LD_Plant_Activity/public
 * @author     Your Name <email@example.com>
 */
class LD_Plant_Activity_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ld_plant_activity    The ID of this plugin.
	 */
	private $ld_plant_activity;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $ld_plant_activity       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $ld_plant_activity, $version ) {

		$this->ld_plant_activity = $ld_plant_activity;
		$this->version = $version;

		add_shortcode('plant_activity_react_app', array($this, 'render_react_app'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->ld_plant_activity, plugin_dir_url( __FILE__ ) . 'css/ld-plant-activity-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->ld_plant_activity, plugin_dir_url( __FILE__ ) . 'js/ld-plant-activity-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'plant-activity-app', plugin_dir_url(__FILE__) . 'plantgrow/build/static/js/main.bundle.js', [], '1.0.0', true );
		wp_localize_script( 'plant-activity-app', 'LDPlantActivityData', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'plant_activity_nonce' ),
			'post_id'  => get_the_ID()
		] );
	}

	public function add_sfwd_plant_activity_post_type() {
		$args = array(
			'sfwd-plant-activity' => array(
				'name'        => 'sfwd-plant-activity',
				'post_label'  => esc_html__( 'Plant Activity', 'ld-plant-activity' ),
				'cpt_options' => array(
					'labels' => array(
						'name'               => esc_html__( 'Plant Activities', 'ld-plant-activity' ),
						'singular_name'      => esc_html__( 'Plant Activity', 'ld-plant-activity' ),
						'add_new'            => esc_html__( 'Add New', 'ld-plant-activity' ),
						'add_new_item'       => esc_html__( 'Add New Plant Activity', 'ld-plant-activity' ),
						'edit_item'          => esc_html__( 'Edit Plant Activity', 'ld-plant-activity' ),
						'new_item'           => esc_html__( 'New Plant Activity', 'ld-plant-activity' ),
						'view_item'          => esc_html__( 'View Plant Activity', 'ld-plant-activity' ),
						'search_items'       => esc_html__( 'Search Plant Activities', 'ld-plant-activity' ),
						'not_found'          => esc_html__( 'No Plant Activities found.', 'ld-plant-activity' ),
						'not_found_in_trash' => esc_html__( 'No Plant Activities found in Trash.', 'ld-plant-activity' ),
					),
					'public'              => true,
					'show_ui'             => true,
					'show_in_nav_menus'   => true,
					'show_in_menu'        => 'learndash-lms',
					'show_in_rest'        => true,
					'has_archive'         => false,
					'rewrite'             => array( 'slug' => 'plant-activity' ),
					'supports'            => array( 'title', 'editor', 'thumbnail' ),
					'menu_position'       => 35,
					'menu_icon'           => 'dashicons-admin-site-alt',
					'map_meta_cap' => true,
					'capabilities'    => array(
						'create_posts' => false,
					),
				),
			),
		);

		foreach ( $args as $post_type => $data ) {
			register_post_type( $post_type, $data['cpt_options'] );
		}
	}

	// just for the testing purpose
	// public function save_plant_activity_meta( $post_id, $post, $update ) {
	// 	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	// 		return;
	// 	}

	// 	if ( $post->post_type !== 'sfwd-plant-activity' ) {
	// 		return;
	// 	}

	// 	if ( $update ) {
	// 		return;
	// 	}

	// 	if ( in_array( 'subscriber', (array) $current_user->roles ) ) {
	// 		return;
	// 	}

	// 	$current_user = wp_get_current_user();
	// 	update_post_meta( $post_id, '_user_id', $current_user->ID );

	// 	$lesson_id = isset($_POST['related_lesson_id']) ? intval($_POST['related_lesson_id']) : 0;
	// 	update_post_meta( $post_id, '_lesson_id', $lesson_id );

	// 	update_post_meta( $post_id, '_activity_status', 0 );
	// 	update_post_meta( $post_id, '_activity_started', time() );
	// 	update_post_meta( $post_id, '_activity_completed', '' );
	// 	update_post_meta( $post_id, '_activity_updated', time() );
	// }

	public function save_sfwd_plant_activity_handler($data) {
		check_ajax_referer( 'plant_activity_nonce', '_wpnonce' );

		// Require login
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => 'Not logged in' ], 401 );
		}

		$current_user = wp_get_current_user();

		// Role check
		if ( ! in_array( 'subscriber', (array) $current_user->roles, true ) ) {
			wp_send_json_error( [ 'message' => 'Only subscribers can submit activity' ], 403 );
		}

		$lesson_id = isset( $_POST['lesson_id'] ) ? intval( $_POST['lesson_id'] ) : 0;
		if ( ! $lesson_id ) {
			wp_send_json_error( [ 'message' => 'Lesson ID is required' ], 400 );
		}

		$existing = get_posts( [
			'post_type'   => 'sfwd-plant-activity',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => [
				[ 'key' => '_user_id', 'value' => $current_user->ID ],
				[ 'key' => '_lesson_id', 'value' => $lesson_id ],
			]
		] );

		$now = time();

		if ( ! empty( $existing ) ) {
			$post_id = $existing[0];

			// Update only relevant meta
			update_post_meta( $post_id, '_activity_updated', $now );

			// Optional: update status, completed flag, etc.
			// update_post_meta( $post_id, '_activity_status', 1 );
			// update_post_meta( $post_id, '_activity_completed', $now );

			wp_send_json_success( [
				'message' => 'Activity already exists and was updated.',
				'post_id' => $post_id,
			] );
		}

		$post_id = wp_insert_post( [
			'post_title'   => 'Plant Activity - ' . current_time( 'Y-m-d H:i:s' ),
			'post_status'  => 'publish',
			'post_type'    => 'sfwd-plant-activity',
			'post_content' => '',
		] );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( [ 'message' => 'Failed to create activity' ], 500 );
		}

		$now = time();

		update_post_meta( $post_id, '_user_id', $current_user->ID );
		update_post_meta( $post_id, '_lesson_id', $lesson_id );
		update_post_meta( $post_id, '_activity_status', 0 );
		update_post_meta( $post_id, '_activity_started', $now );
		update_post_meta( $post_id, '_activity_completed', '' );
		update_post_meta( $post_id, '_activity_updated', $now );

		wp_send_json_success( [
			'message' => 'Activity created',
			'post_id' => $post_id,
		] );
	}

	public function complete_sfwd_plant_activity_handler() {
		check_ajax_referer( 'plant_activity_nonce', '_wpnonce' );

		// Require login
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => 'Not logged in' ], 401 );
		}

		$current_user = wp_get_current_user();

		// Role check
		if ( ! in_array( 'subscriber', (array) $current_user->roles, true ) ) {
			wp_send_json_error( [ 'message' => 'Only subscribers can complete activity' ], 403 );
		}

		$lesson_id = isset( $_POST['lesson_id'] ) ? intval( $_POST['lesson_id'] ) : 0;
		if ( ! $lesson_id ) {
			wp_send_json_error( [ 'message' => 'Lesson ID is required' ], 400 );
		}

		// Find existing activity
		$existing = get_posts( [
			'post_type'   => 'sfwd-plant-activity',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => [
				[ 'key' => '_user_id', 'value' => $current_user->ID ],
				[ 'key' => '_lesson_id', 'value' => $lesson_id ],
			]
		] );

		if ( empty( $existing ) ) {
            $post_id = wp_insert_post( [
                'post_title'   => 'Plant Activity - ' . current_time( 'Y-m-d H:i:s' ),
                'post_status'  => 'publish',
                'post_type'    => 'sfwd-plant-activity',
                'post_content' => '',
            ] );
            if ( is_wp_error( $post_id ) ) {
                wp_send_json_error( [ 'message' => 'Failed to create activity' ], 500 );
            }

			//            wp_send_json_error( [ 'message' => 'No existing activity found to complete' ], 404 );
            $now = time();

            update_post_meta( $post_id, '_user_id', $current_user->ID );
            update_post_meta( $post_id, '_lesson_id', $lesson_id );
            update_post_meta( $post_id, '_activity_status', 0 );
            update_post_meta( $post_id, '_activity_started', $now );
            update_post_meta( $post_id, '_activity_completed', '' );
            update_post_meta( $post_id, '_activity_updated', $now );

        }
        else {
            $post_id = $existing[0];
        }

		$post_id = $existing[0];
		$now = time();

		// Mark complete
		update_post_meta( $post_id, '_activity_status', 1 );
		update_post_meta( $post_id, '_activity_completed', $now );
		update_post_meta( $post_id, '_activity_updated', $now );

		wp_send_json_success( [
			'message' => 'Activity marked as completed',
			'post_id' => $post_id,
			'completed_at' => date( 'Y-m-d H:i:s', $now ),
		] );
	}

	public function sfwd_save_plant_activity_statistic_handler() {
		check_ajax_referer( 'plant_activity_nonce', '_wpnonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => 'Not logged in' ], 401 );
		}

		$current_user = wp_get_current_user();

		if ( ! in_array( 'subscriber', (array) $current_user->roles, true ) ) {
			wp_send_json_error( [ 'message' => 'Only subscribers can update activity' ], 403 );
		}

		$lesson_id = isset( $_POST['lesson_id'] ) ? intval( $_POST['lesson_id'] ) : 0;
		if ( ! $lesson_id ) {
			wp_send_json_error( [ 'message' => 'Lesson ID is required' ], 400 );
		}

		// Find existing activity
		$existing = get_posts( [
			'post_type'   => 'sfwd-plant-activity',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => [
				[ 'key' => '_user_id', 'value' => $current_user->ID ],
				[ 'key' => '_lesson_id', 'value' => $lesson_id ],
			]
		] );

		if ( empty( $existing ) ) {
            $post_id = wp_insert_post( [
                'post_title'   => 'Plant Activity - ' . current_time( 'Y-m-d H:i:s' ),
                'post_status'  => 'publish',
                'post_type'    => 'sfwd-plant-activity',
                'post_content' => '',
            ] );

            if ( is_wp_error( $post_id ) ) {
                wp_send_json_error( [ 'message' => 'Failed to create activity' ], 500 );
            }

            $now = time();

            update_post_meta( $post_id, '_user_id', $current_user->ID );
            update_post_meta( $post_id, '_lesson_id', $lesson_id );
            update_post_meta( $post_id, '_activity_status', 0 );
            update_post_meta( $post_id, '_activity_started', $now );
            update_post_meta( $post_id, '_activity_completed', '' );
            update_post_meta( $post_id, '_activity_updated', $now );
        } else {
            $post_id = $existing[0];

		}

		$post_id = $existing[0];

		// Water
		if ( isset( $_POST['water_progress'] ) ) {
			update_post_meta( $post_id, '_water_progress', intval( $_POST['water_progress'] ) );
		}
		if ( isset( $_POST['water_points'] ) ) {
			update_post_meta( $post_id, '_water_points', intval( $_POST['water_points'] ) );
		}

		// Sun
		if ( isset( $_POST['sun_progress'] ) ) {
			update_post_meta( $post_id, '_sun_progress', intval( $_POST['sun_progress'] ) );
		}
		if ( isset( $_POST['sun_points'] ) ) {
			update_post_meta( $post_id, '_sun_points', intval( $_POST['sun_points'] ) );
		}

		// Nutrients (expecting 3 separate values from frontend)
		if ( isset( $_POST['nutrient_progress'] ) ) {
			update_post_meta( $post_id, '_nutrient_progress', intval( $_POST['nutrient_progress'] ) );
		}
		if ( isset( $_POST['nutrient_points'] ) ) {
			update_post_meta( $post_id, '_nutrient_points', intval( $_POST['nutrient_points'] ) );
		}

		// Dead leaves / purines
		// if ( isset( $_POST['dead_leaves_progress'] ) ) {
		// 	update_post_meta( $post_id, '_dead_leaves_progress', intval( $_POST['dead_leaves_progress'] ) );
		// }
		// if ( isset( $_POST['dead_leaves_points'] ) ) {
		// 	update_post_meta( $post_id, '_dead_leaves_points', intval( $_POST['dead_leaves_points'] ) );
		// }

		// Total progress
		// if ( isset( $_POST['total_progress'] ) ) {
		// 	update_post_meta( $post_id, '_total_progress', intval( $_POST['total_progress'] ) );
		// }
		// if ( isset( $_POST['total_points'] ) ) {
		// 	update_post_meta( $post_id, '_total_points', intval( $_POST['total_points'] ) );
		// }
		
		if ( isset( $_POST['balance_recovery_points'] ) ) {
			update_post_meta( $post_id, '_balance_recovery_points', intval( $_POST['balance_recovery_points'] ) );
		}

		if ( isset( $_POST['total'] ) ) {
			update_post_meta( $post_id, '_total', intval( $_POST['total'] ) );
		}

		if ( isset( $_POST['last_growth_point'] ) ) {
			update_post_meta( $post_id, '_last_growth_point', intval( $_POST['last_growth_point'] ) );
		}

		if ( isset( $_POST['plant_assets'] ) ) {
			update_post_meta( $post_id, '_plant_assets', $_POST['plant_assets'] );
		}

		update_post_meta( $post_id, '_activity_updated', time() );

		wp_send_json_success( [
			'message' => 'Activity updated',
			'post_id' => $post_id
		] );
	}

	public function sfwd_get_plant_activity_statistic_handler() {
		check_ajax_referer( 'plant_activity_nonce', '_wpnonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( [ 'message' => 'Not logged in' ], 401 );
		}

		$current_user = wp_get_current_user();

		if ( ! in_array( 'subscriber', (array) $current_user->roles, true ) ) {
			wp_send_json_error( [ 'message' => 'Only subscribers can view activity' ], 403 );
		}

		$lesson_id = isset( $_POST['lesson_id'] ) ? intval( $_POST['lesson_id'] ) : 0;
		if ( ! $lesson_id ) {
			wp_send_json_error( [ 'message' => 'Lesson ID is required' ], 400 );
		}

		// Find the activity based on lesson ID and user
		$existing = get_posts( [
			'post_type'   => 'sfwd-plant-activity',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => [
				[ 'key' => '_user_id', 'value' => $current_user->ID ],
				[ 'key' => '_lesson_id', 'value' => $lesson_id ],
			]
		] );

		if ( empty( $existing ) ) {
            $post_id = wp_insert_post( [
                'post_title'   => 'Plant Activity - ' . current_time( 'Y-m-d H:i:s' ),
                'post_status'  => 'publish',
                'post_type'    => 'sfwd-plant-activity',
                'post_content' => '',
            ] );

            if ( is_wp_error( $post_id ) ) {
                wp_send_json_error( [ 'message' => 'Failed to create activity' ], 500 );
            }

            $now = time();

            update_post_meta( $post_id, '_user_id', $current_user->ID );
            update_post_meta( $post_id, '_lesson_id', $lesson_id );
            update_post_meta( $post_id, '_activity_status', 0 );
            update_post_meta( $post_id, '_activity_started', $now );
            update_post_meta( $post_id, '_activity_completed', '' );
            update_post_meta( $post_id, '_activity_updated', $now );
        } else {
            $post_id = $existing[0];
        }

		$post_id = $existing[0];

		// Retrieve all the required post meta fields
		$data = [
			'water_progress'      => empty(get_post_meta($post_id, '_water_progress', true)) ? 0 : get_post_meta($post_id, '_water_progress', true),
			'water_points'        => empty(get_post_meta($post_id, '_water_points', true)) ? 0 : get_post_meta($post_id, '_water_points', true),
			'sun_progress'        => empty(get_post_meta($post_id, '_sun_progress', true)) ? 0 : get_post_meta($post_id, '_sun_progress', true),
			'sun_points'          => empty(get_post_meta($post_id, '_sun_points', true)) ? 0 : get_post_meta($post_id, '_sun_points', true),
			'nutrient_progress'   => empty(get_post_meta($post_id, '_nutrient_progress', true)) ? 0 : get_post_meta($post_id, '_nutrient_progress', true),
			'nutrient_points'     => empty(get_post_meta($post_id, '_nutrient_points', true)) ? 0 : get_post_meta($post_id, '_nutrient_points', true),
			// 'dead_leaves_progress'=> empty(get_post_meta($post_id, '_dead_leaves_progress', true)) ? 0 : get_post_meta($post_id, '_dead_leaves_progress', true),
			// 'dead_leaves_points'  => empty(get_post_meta($post_id, '_dead_leaves_points', true)) ? 0 : get_post_meta($post_id, '_dead_leaves_points', true),
			'balance_recovery_points' => empty(get_post_meta($post_id, '_balance_recovery_points', true)) ? 0 : get_post_meta($post_id, '_balance_recovery_points', true),
			'total'               => empty(get_post_meta($post_id, '_total', true)) ? 0 : get_post_meta($post_id, '_total', true),
			'last_growth_point'   => empty(get_post_meta($post_id, '_last_growth_point', true)) ? 0 : get_post_meta($post_id, '_last_growth_point', true),
			'plant_assets'        => empty(get_post_meta($post_id, '_plant_assets', true)) ? 0 : get_post_meta($post_id, '_plant_assets', true),
			'activity_updated'    => empty(get_post_meta($post_id, '_activity_updated', true)) ? 0 : get_post_meta($post_id, '_activity_updated', true),
		];

		wp_send_json_success( [
			'message' => 'Activity data retrieved successfully',
			'data'    => $data,
		] );
	}


	public function react_enqueue_scripts() {
		wp_enqueue_script( 'plant-grow-react-app', plugin_dir_url(__FILE__) . 'plantgrow/build/static/js/main.bundle.js', array(), null, true );
	}

	public function react_enqueue_styles() {
		wp_enqueue_style( 'plant-grow-react-style', plugin_dir_url(__FILE__) . 'plantgrow/build/static/css/main.bundle.css' );
	}

	public function render_react_app($atts) {
		$this->react_enqueue_scripts();
		$this->react_enqueue_styles();

		$atts = shortcode_atts([
			'lesson_id' => 0,
		], $atts, 'plant_activity_react_app');

		$lesson_id = intval($atts['lesson_id']);

		return '<div id="root" style="width:100%!important;max-width:100%;" data-lesson-id="' . esc_attr($lesson_id) . '"></div>';
	}

	// public function append_plant_activity_shortcode_if_enabled($content) {
	// 	if (is_singular('sfwd-lessons')) {
	// 		global $post;

	// 		$enabled = get_post_meta($post->ID, '_plant_activity_key', true);
			
	// 		if ($enabled === 'yes') {
	// 			$shortcode_output = do_shortcode('[plant_activity_react_app]');
	// 			$content .= $shortcode_output;
	// 		}
	// 	}

	// 	return $content;
	// }

	public function append_plant_activity_shortcode_if_enabled($content) {
		if (is_singular('sfwd-lessons')) {
			global $post;
			global $wpdb;

			$enabled = get_post_meta($post->ID, '_plant_activity_key', true);
			$current_user = wp_get_current_user();
			$lesson_id = $post->ID;

			$activity_status = $wpdb->get_var($wpdb->prepare("
                            SELECT pm_status.meta_value
                            FROM {$wpdb->postmeta} pm_user
                            INNER JOIN {$wpdb->postmeta} pm_lesson 
                                ON pm_user.post_id = pm_lesson.post_id
                            INNER JOIN {$wpdb->postmeta} pm_status 
                                ON pm_user.post_id = pm_status.post_id
                            WHERE pm_user.meta_key = '_user_id' 
                            AND pm_user.meta_value = %d
                            AND pm_lesson.meta_key = '_lesson_id'
                            AND pm_lesson.meta_value = %d
                            AND pm_status.meta_key = '_activity_status'
                            LIMIT 1
                        ", $current_user->ID, $lesson_id));

			if ( 
				$enabled === 'yes' 
				&& $activity_status !== '1' 
				&& !learndash_is_lesson_complete($current_user->ID, $lesson_id) 
			) {
			// if ($enabled === 'yes') {
				$custom_page_url = add_query_arg([
					'plant_activity' => 'yes',
					'lesson_id'      => $post->ID
				], site_url('/index.php'));

				$button_html = '<a href="' . esc_url($custom_page_url) . '" 
					style="display:inline-block;padding:10px 20px;background:#4CAF50;color:#fff;
					text-decoration:none;border-radius:5px;" target="_blank">
					Start Plant Activity
				</a>';

				$content .= $button_html;
			}
		}

		return $content;
	}

	public function maybe_load_plant_activity_template() {
		if ( isset($_GET['plant_activity']) && $_GET['plant_activity'] === 'yes' ) {
			status_header(200);
			nocache_headers();

			include plugin_dir_path(__FILE__) . 'templates/plant-activity-template.php';
			
			exit;
		}
	}
}