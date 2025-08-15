<?php
    global $wpdb;

    $referer = wp_get_referer();

    if ( ! is_user_logged_in() ) {
        wp_redirect( $referer );
        exit;
    }

    $current_user = wp_get_current_user();
    if ( ! in_array( 'subscriber', (array) $current_user->roles, true ) ) {
        wp_redirect( $referer );
        exit;
    }

    $lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
    if ( ! $lesson_id ) {
        wp_redirect( $referer );
        exit;
    }

    // $existing = get_posts( [
    //     'post_type'   => 'sfwd-plant-activity',
    //     'post_status' => 'publish',
    //     'numberposts' => 1,
    //     'fields'      => 'ids',
    //     'meta_query'  => [ [ 'key' => '_user_id', 'value' => $current_user->ID ], [ 'key' => '_lesson_id', 'value' => $lesson_id ] ]
    // ] );
    // if ( empty( $existing ) ) {
        
    // }
    
    $enabled = get_post_meta($lesson_id, '_plant_activity_key', true);
    
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
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plant Activity</title>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <div id="plant-activity-container">
            <?php
                if (isset($_GET['lesson_id'])) {
                    echo do_shortcode('[plant_activity_react_app lesson_id="' . intval($_GET['lesson_id']) . '"]');
                } 
                else {
                    echo do_shortcode('[plant_activity_react_app]');
                }
            ?>
        </div>
        <?php wp_footer(); ?>
    </body>
</html>

<?php
    } else {
        wp_redirect( $referer );
        exit;
    }
?>