<?php
/*
Plugin Name: WordPress Dublin
Plugin URI: https://wpdublin.com/
Description: Set a primary category for your (custom) posts and query them in your template using native WordPress queries.
Author: Ciprian Popescu
Author URI: https://wpdublin.com/
Version: 1.0.0
Text Domain: wpdublin

WordPress Dublin
Copyright (C) 2018 Ciprian Popescu (getbutterfly@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Include updater
 */
require_once IP_PLUGIN_PATH . '/classes/Updater.php';

if (is_admin()) {
    $config = array(
        'slug' => plugin_basename(__FILE__),
        'proper_folder_name' => 'wpdublin',
        'github_url' => 'https://github.com/getButterfly/wpdublin',
        'requires' => '4.6',
        'tested' => '4.9.4',
        'readme' => 'README.MD',
    );
    new WP_GitHub_Updater($config);
}

/**
 * Include plugin settings
 */
include 'includes/wpd-functions.php';
include 'includes/wpd-settings.php';

/**
 * Load dashboard styles
 */
function wpd_load_admin_style($hook) {
    wp_enqueue_style('wpd', plugins_url('assets/css/dashboard.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'wpd_load_admin_style');

/**
 * Create plugin options menu
 */
function wpd_plugin_menu() {
    add_menu_page(__('WPDublin', 'wp-primary-category'), __('WPDublin', 'wp-primary-category'), 'manage_options', 'wpd_settings', 'wpd_settings', 'dashicons-admin-tools', 4);
}
add_action('admin_menu', 'wpd_plugin_menu');

/**
 * Add default/initial options
 */
function wpd_install() {
    //add_option('wpd_primary_category', '');
}
register_activation_hook(__FILE__, 'wpd_install');
