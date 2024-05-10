<?php
/*
Plugin Name: Instagram Last Post Fetcher
Description: Display your latest Instagram post by simply entering your access token and account link to showcase dynamic content on your site
Version: 1.0
Author: <a href="https://potterswheelmedia.com"> PottersWheel Media </a>
*/

// Add admin menu
function instagram_fetcher_menu() {
    add_menu_page('Instagram Fetcher Settings', 'Instagram Fetcher', 'manage_options', 'instagram-fetcher-settings', 'instagram_fetcher_settings_page');
}
add_action('admin_menu', 'instagram_fetcher_menu');

// Admin settings page
function instagram_fetcher_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Save settings
    if (isset($_POST['instagram_access_token']) && isset($_POST['instagram_post_link'])) {
        update_option('instagram_access_token', $_POST['instagram_access_token']);
        update_option('instagram_post_link', $_POST['instagram_post_link']);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    // Get settings
    $access_token = get_option('instagram_access_token');
    $post_link = get_option('instagram_post_link');
    ?>

    <div class="wrap">
        <h2>Instagram Last Post Fetcher Settings</h2>
        <h3>Use ShortCode :- instagram_post_fetcher</h3>
        <form method="post" action="">
            <label for="instagram_access_token">Access Token :</label><br>
            <input type="text" id="instagram_access_token" name="instagram_access_token" value="<?php echo esc_attr($access_token); ?>"><br><br>
            <label for="instagram_post_link">Account Link :</label><br>
            <input type="text" id="instagram_post_link" name="instagram_post_link" value="<?php echo esc_attr($post_link); ?>"><br><br>
            <input type="submit" class="button-primary" value="Save Settings">
        </form>
    </div>
    <?php
}

// Shortcode handler
function instagram_fetcher_shortcode($atts) {
    // Get access token
    $access_token = get_option('instagram_access_token');
    $post_link = get_option('instagram_post_link');

    // Display form if access token is not set
    if (empty($access_token)) {
        ob_start();
        ?>
        <div class="wrap">
            <h2>Instagram Fetcher</h2>
            <p>Please enter your Instagram access token to fetch posts.</p>
        </div>
        <?php
        return ob_get_clean();
    }

    // Fetch and display Instagram post
    ob_start();
    ?>
    <div id="instagram-post" data-access-token="<?php echo esc_attr($access_token); ?>" data-post-link="<?php echo esc_attr($post_link); ?>">
        Loading Instagram post...
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('instagram_post_fetcher', 'instagram_fetcher_shortcode');

// Enqueue JavaScript file
function instagram_fetcher_scripts() {
    wp_enqueue_script('instagram-fetcher-script', plugin_dir_url(__FILE__) . 'instagram-fetcher.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'instagram_fetcher_scripts');
