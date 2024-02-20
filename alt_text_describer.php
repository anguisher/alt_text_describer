<?php
/*
Plugin Name: Alt Text Describer
Description: Autogenerate alternative text of images in bulk for better SEO.
Version: 1.01
Author: Prisakaru
*/

require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
require_once plugin_dir_path( __FILE__ ) . 'includes/database_operations.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/image_operations.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/requests_operations.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/Image_List_Table.php';

add_action('admin_menu', 'alt_text_describer_menu');
add_action('plugins_loaded', 'init_plugin');
add_action('admin_enqueue_scripts', 'enqueue_custom_scripts_and_styles');
add_action('wp_ajax_generate_alt_for_images', 'generate_alt_for_images');
add_action('wp_ajax_generate_alt_for_all_images', 'generate_alt_for_all_images');

function generate_alt_for_images(){
    $language = $_POST['language'];
    $req_operations = new requests_operations();
    $req_operations->generate_alt_for_images($language);
}
function generate_alt_for_all_images(){
    $language = $_POST['language'];
    $req_operations = new requests_operations();
    $req_operations->generate_alt_for_all_images($language);
}
function init_plugin(){
    $db_operations = new database_operations();
    $db_operations->create_descriptions_table();
}
function alt_text_describer_menu() {
    add_menu_page(
        'Alt Text Describer',
        'Alt Text Describer',
        'manage_options',
        'alt-text-describer',
        'alt_text_describer_admin_page',
        'dashicons-admin-generic'
    );
}
function enqueue_custom_scripts_and_styles() {
    wp_enqueue_script('describer-script', plugin_dir_url(__FILE__) . 'assets/describer-script.js', array('jquery'), '1.0', true);
    wp_enqueue_style('describer-style', plugin_dir_url(__FILE__) . 'assets/describer-style.css', array(), '1.0', 'all');
}
function alt_text_describer_admin_page() {
    ?>
    <div class="wrap">
        <h2></h2>
        <h2 class="nav-tab-wrapper">
            <a href="?page=alt-text-describer&tab=settings" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] === 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
            <a href="?page=alt-text-describer&tab=describer" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] === 'describer' ? 'nav-tab-active' : ''; ?>">Describer</a>
        </h2>
        <?php
            $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'settings';
            switch ($active_tab) {
                case 'settings':
                    alt_text_describer_settings_page();
                    break;
                case 'describer':
                    alt_text_describer_describer_page();
                    break;
                default:
                    alt_text_describer_settings_page();
            }
        ?>
    </div>
    <?php
}

function alt_text_describer_settings_page() {
    include_once plugin_dir_path(__FILE__). 'includes/partials/admin-settings-page.php';
}

function alt_text_describer_describer_page() {
    include_once plugin_dir_path( __FILE__ ) . 'includes/partials/admin-describer-page.php';
}

