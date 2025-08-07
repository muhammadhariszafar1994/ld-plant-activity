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

		// react game build
		wp_enqueue_script( 'plant-activity-app', plugin_dir_url(__FILE__) . 'build/static/js/main.c14c425a.js', [], '1.0.0', true );
		wp_localize_script( 'plant-activity-app', 'LDPlantActivityData', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'plant_activity_nonce' ),
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

	public function react_enqueue_scripts() {
		wp_enqueue_script( 'plant-grow-react-app', plugin_dir_url(__FILE__) . 'build/static/js/main.c14c425a.js', array(), null, true );
	}

	public function react_enqueue_styles() {
		wp_enqueue_style( 'plant-grow-react-style', plugin_dir_url(__FILE__) . 'build/static/css/main.12e4b0b4.css' );
	}

	public function render_react_app() {
		$this->react_enqueue_scripts();
		$this->react_enqueue_styles();

		return '<div id="root" style="width:100%!important;max-width:100%;"></div>';
	}
}