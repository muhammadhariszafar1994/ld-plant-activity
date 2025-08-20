<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    LD_Plant_Activity
 * @subpackage LD_Plant_Activity/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    LD_Plant_Activity
 * @subpackage LD_Plant_Activity/admin
 * @author     Your Name <email@example.com>
 */
class LD_Plant_Activity_Admin {

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
	 * @param      string    $ld_plant_activity       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $ld_plant_activity, $version ) {

		$this->ld_plant_activity = $ld_plant_activity;
		$this->version = $version;

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// add_filter( 'learndash_submenu', [ $this, 'add_ld_submenu' ] );
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->ld_plant_activity, plugin_dir_url( __FILE__ ) . 'css/ld-plant-activity-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( 
			$this->ld_plant_activity, 
			plugin_dir_url( __FILE__ ) . 'js/ld-plant-activity-admin.js', 
			[ 'jquery' ], 
			$this->version, 
			true 
		);
	
		wp_localize_script( 
			$this->ld_plant_activity, 
			'PlantActivityStats', 
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'plant_activity_nonce' )
			]
		);
	}

	// public function add_ld_submenu( $submenu ) {
	// 	$menu = array(
	// 		'ld-plant-activity' => array(
	// 			'name' => __( 'Plant Activity', 'ld-plant-activity' ),
	// 			'cap'  => 'manage_options',
	// 			'link' => 'edit.php?post_type=plant-activity',
	// 		),
	// 	);

	// 	array_splice( $submenu, 9, 0, $menu );

	// 	return $submenu;
	// }

	// public function add_ld_submenu( $submenu ) {
	// 	$submenu[] = array(
	// 		'parent_slug' => 'learndash-lms',
	// 		'page_title'  => 'Plant Activity',
	// 		'menu_title'  => 'Plant Activity',
	// 		'capability'  => 'manage_options',
	// 		'menu_slug'   => 'ldlms-plant-activity',
	// 		'function'    => [ $this, 'render_admin_page' ],
	// 	);
	// 	return $submenu;
	// }

	// public function render_admin_page() {
	// 	include_once plugin_dir_path( __FILE__ ) . 'partials/ld-plant-activity-admin-display.php';
	// }

	public function add_columns( $columns ) {
		unset( $columns['title'] );
		unset( $columns['date'] );

		$columns['user']               = 'User';
		$columns['lesson']             = 'Module';
		$columns['activity_status']    = 'Status';
		$columns['activity_started']   = 'Started';
		$columns['activity_completed'] = 'Completed';
		$columns['activity_updated']   = 'Updated';
		$columns['actions']   = 'Actions';
		
		return $columns;
	}

	public function show_column_data( $column, $post_id ) {
		switch ( $column ) {
			case 'user':
				$user_id = get_post_meta( $post_id, '_user_id', true );
				if ( $user_id ) {
					$user = get_userdata( $user_id );
					echo esc_html( $user ? $user->display_name : '—' );
				} else {
					echo '—';
				}
				break;

			case 'lesson':
				$lesson_id = get_post_meta( $post_id, '_lesson_id', true );
				echo esc_html( $lesson_id ? get_the_title( $lesson_id ) : '—' );
				break;

			case 'activity_status':
				$status = get_post_meta( $post_id, '_activity_status', true );
				echo $status ? 'Completed' : 'Pending';
				break;

			case 'activity_started':
			case 'activity_completed':
			case 'activity_updated':
				$ts = get_post_meta( $post_id, "_$column", true );
				echo $ts ? esc_html( date( 'Y-m-d H:i:s', intval( $ts ) ) ) : '—';
				break;
			case 'actions':
				$user_id   = get_post_meta( $post_id, '_user_id', true );
				$lesson_id = get_post_meta( $post_id, '_lesson_id', true );
				echo '<a href="#" class="view-statistics" data-user="' . esc_attr( $user_id ) . '" data-lesson="' . esc_attr( $lesson_id ) . '">View Statistic</a>';
				break;
		}
	}

	public function add_custom_field( $setting_option_fields = array(), $settings_metabox_key = '' ) {
		if ( 'learndash-lesson-display-content-settings' === $settings_metabox_key ) {
			$post_id = get_the_ID();
			if ( !$post_id ) return $setting_option_fields;
			
			$plant_activity_checkbox_value = get_post_meta( $post_id, '_plant_activity_key', true );

			if ( empty( $plant_activity_checkbox_value ) ) {
				$plant_activity_checkbox_value = '';
			}

			$setting_option_fields['plant-activity-switch'] = array(
				'name'      => 'plant-activity-switch',
				'label'     => esc_html__( 'Plant Activity', 'learndash' ),
				'type'      => 'checkbox-switch',
				'value'     => $plant_activity_checkbox_value === 'yes' ? 'yes' : '',
				'default'   => '',
				'options'   => array(
					'yes' => '',
				),
				'help_text' => esc_html__( 'Enable this option to force the module timer.', 'learndash' ),
			);
			
			// $setting_option_fields['plant-activity-extra-html'] = array(
			// 	'type'  => 'html',
			// 	'value' => '<label class="learndash-custom-note">Use shortcode: <code>&#91;plant_activity_react_app&#93;</code></label>',
			// );
		}

		return $setting_option_fields;
	}

	public function save_my_custom_meta( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if(isset($_POST['learndash-lesson-display-content-settings']['nonce'])) {
			if (isset($_POST['learndash-lesson-display-content-settings']['plant-activity-switch'])) {
				update_post_meta( $post_id, '_plant_activity_key', 'yes' );
			}
			else {
				update_post_meta( $post_id, '_plant_activity_key', '' );
			}
		}
	}

	public function add_plant_activity_modal_statistic() {
		$screen = get_current_screen();

		if ( 'edit-sfwd-plant-activity' !== $screen->id ) {
			return;
		}

		?>
		<div id="statistic-modal" style="display:none;">
			<div id="statistic-modal-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:10000;"></div>
			<div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#fff;padding:20px;width:400px;max-width:90%;z-index:10001;border-radius:8px;">
				<h2>Activity Statistics</h2>
				<div id="statistic-modal-content">Loading...</div>
				<p><button id="statistic-modal-close" class="button">Close</button></p>
			</div>
		</div>
		<?php
	}

	public function ajax_get_plant_activity_statistics() {
		check_ajax_referer( 'plant_activity_nonce' );

		$lesson_id = intval( $_POST['lesson_id'] ?? 0 );
		$user_id   = intval( $_POST['user_id'] ?? 0 );

		if ( ! $lesson_id || ! $user_id ) {
			wp_send_json_error( [ 'message' => 'Invalid request' ] );
		}

		$existing = get_posts( [
			'post_type'   => 'sfwd-plant-activity',
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => [
				[ 'key' => '_user_id', 'value' => $user_id ],
				[ 'key' => '_lesson_id', 'value' => $lesson_id ],
			]
		] );

		if ( empty( $existing ) ) {
			wp_send_json_error( [ 'message' => 'No existing activity found' ], 404 );
		}

		$post_id = $existing[0];

		$stats = [
			'water_progress'       => get_post_meta( $post_id, '_water_progress', true ),
			'water_points'         => get_post_meta( $post_id, '_water_points', true ),
			'sun_progress'         => get_post_meta( $post_id, '_sun_progress', true ),
			'sun_points'           => get_post_meta( $post_id, '_sun_points', true ),
			'nutrient_progress'    => get_post_meta( $post_id, '_nutrient_progress', true ),
			'nutrient_points'      => get_post_meta( $post_id, '_nutrient_points', true ),
			'dead_leaves_progress' => get_post_meta( $post_id, '_dead_leaves_progress', true ),
			'dead_leaves_points'   => get_post_meta( $post_id, '_dead_leaves_points', true ),
			// 'total_progress' => get_post_meta( $post_id, '_total_progress', true ),
			// 'total_points'   => get_post_meta( $post_id, '_total_points', true )
			'total'   => get_post_meta( $post_id, '_total', true )
		];

		wp_send_json_success( [ 'stats' => $stats ] );
	}
}