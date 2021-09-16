<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Job Board
 * Plugin URI:        https://jploft.in
 * Description:       create a Job Board on your website for job listing.
 * Version:           1.1.2
 * Author:            Lokendra Singh
 * Author URI:        https://jploft.in
 * License:           GPL
 * License URI:       https://jploft.in
 * Text Domain:       jobs-board
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * */

if (!defined('ABSPATH')) {die;}

/*Plugin version Defined*/

if (!defined('JPLF_PLUGIN_VERSION')) {
    define('JPLF_PLUGIN_VERSION', '1.1.2');
}

update_option('jplf_version', JPLF_PLUGIN_VERSION);

/*main plugin directory path define */

define('JPLF_MAIN_FILE_PATH', __FILE__);

define('JPLF_FILE_URL', __FILE__);

/*create jobs board post type */

require plugin_dir_path(__FILE__) . 'includes/jplf-job-board-post-type.php';

/* Styles for jobs board plugin back-end and front-end admin  */

function jplf_jobs_board_scripts()
{
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('style', $plugin_url . "/assets/css/admin-style.css");
    wp_enqueue_style('fontawesome-iconpicker-min', plugins_url('/assets/css/fontawesome-iconpicker.min.css', __FILE__));

    if ($_GET['page'] == 'jobs_board_genral_setting') {
        wp_enqueue_style('bootstrap-min-css', plugins_url('/assets/css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('bootstrap-theme-min-css', plugins_url('/assets/css/bootstrap-theme.min.css', __FILE__));

        wp_enqueue_script('releated-script', plugins_url('/assets/js/jquery.min.js', __FILE__), array('jquery', 'jquery-ui-droppable', 'jquery-ui-draggable', 'jquery-ui-sortable'));

        wp_enqueue_script('releated-bootstrap-min', plugins_url('/assets/js/bootstrap.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('bootstrap-bundle-min', plugins_url('/assets/js/bootstrap.bundle.min.js', __FILE__), array('jquery'));
        wp_enqueue_script('custom-modal-js', plugins_url('/assets/js/custom.js', __FILE__), array('jquery'));
    }


}

add_action('admin_print_styles', 'jplf_jobs_board_scripts');

/*front end js and css*/

function enqueue_related_pages_scripts_and_styles()
{
    wp_enqueue_style('related-styles', plugins_url('/assets/css/front-style.css', __FILE__));
    wp_enqueue_style('font-awesome-min', plugins_url('/assets/css/font-awesome.min.css', __FILE__));
    wp_enqueue_style('fontawesome-iconpicker-min', plugins_url('/assets/css/fontawesome-iconpicker.min.css', __FILE__));
    wp_enqueue_style('bootstrap-min-css', plugins_url('/assets/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('bootstrap-theme-min-css', plugins_url('/assets/css/bootstrap-theme.min.css', __FILE__));
    wp_enqueue_script('releated-script', plugins_url('/assets/js/jquery.min.js', __FILE__), array('jquery', 'jquery-ui-droppable', 'jquery-ui-draggable', 'jquery-ui-sortable'));

    wp_enqueue_script('releated-bootstrap-min', plugins_url('/assets/js/bootstrap.min.js', __FILE__), array('jquery'));
    wp_enqueue_script('bootstrap-bundle-min', plugins_url('/assets/js/bootstrap.bundle.min.js', __FILE__), array('jquery'));
    wp_enqueue_script('custom-modal-js', plugins_url('/assets/js/custom.js', __FILE__), array('jquery'));
      

}
add_action('wp_enqueue_scripts', 'enqueue_related_pages_scripts_and_styles');

/*admin menu for jobs post types*/

add_action('admin_menu', 'jplf_admin_menu');

function jplf_admin_menu()
{
    add_submenu_page('edit.php?post_type=jobs', esc_html__('How can use', 'jobs-board'), esc_html__('How can use', 'jobs-board'), 'manage_options', 'jobs_board_genral_setting', 'jobs_board_genral_setting');

    add_submenu_page('edit.php?post_type=jobs', esc_html__('Apply User', 'jobs-board'), esc_html__('Apply User', 'jobs-board'), 'manage_options', 'jobs_board_apply_user_list', 'jobs_board_apply_user_list');
}

require plugin_dir_path(__FILE__) . 'includes/jobs-board-apply-user-list.php';

function jobs_board_genral_setting()
{

    require plugin_dir_path(__FILE__) . 'includes/jobs-board-genral-setting.php';
}


function jobs_board_apply_user_list(){
  $Jobsapplicant = new My_Jobsapplicant_List_Table();
  echo '<div class="wrap"><h2>Applicants users</h2>'; 
  $Jobsapplicant->prepare_items(); 
  $Jobsapplicant->display(); 
  echo '</div>'; 
}


function deactivate_jplf_job_board()
{
    require_once plugin_dir_path(__FILE__) . 'includes/jobs-board-deactivator.php';

    jplf_Job_Board_Deactivation::deactivated();
}

register_deactivation_hook(plugin_dir_path(__FILE__), 'deactivate_jplf_job_board');

function jobs_board_add_settings_link($links)
{

    $settings_link = '<a href="edit.php?post_type=jobs&page=jobs_board_genral_setting">
' . __('Settings') . '
';
    array_push($links, $settings_link);
    return $links;
}

$jobboards = plugin_basename(__FILE__);

add_filter("plugin_action_links_$jobboards", 'jobs_board_add_settings_link');

require_once plugin_dir_path(__FILE__) . 'includes/jobsboard-list-shortcode.php';

require_once plugin_dir_path(__FILE__) . 'postmeta/job-post-meta-field-init.php';

require_once plugin_dir_path(__FILE__) . 'includes/jobs-board-applicate-ajax.php';

//require_once plugin_dir_path(__FILE__) . 'includes/jobs-board-admin-setting-class.php';

require_once plugin_dir_path(__FILE__) . 'includes/add-jobs-board-options.php';

function create_jobs_apply_database_table()
{
    global $wpdb;
    $table_name_apply = $wpdb->prefix . 'jobs_apply_user';
    $charset_collate  = $wpdb->get_charset_collate();

    if ($wpdb->get_var("show tables like '$table_name_apply'") != $table_name_apply) {
        $sql = "CREATE TABLE $table_name_apply (
                id int(11) NOT NULL auto_increment,
                jobs_board_id int(11) NOT NULL,
                first_name varchar(60) NOT NULL,
                last_name varchar(200) NOT NULL,
                email varchar(255) NOT NULL,
                contact_no varchar(255) NOT NULL,
                job_type_apply varchar(255) NOT NULL,
                jobs_name varchar(255) NOT NULL,
                jobs_category varchar(255) NOT NULL,
                jobs_locations varchar(255) NOT NULL,
                apply_expirence varchar(255) NOT NULL,
                apply_resume varchar(255) NOT NULL,
                previes_company varchar(255) NOT NULL,
                apply_short_description varchar(1000) NOT NULL,
                UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}

register_activation_hook(__FILE__, 'create_jobs_apply_database_table');
