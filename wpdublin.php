<?php
/*
Plugin Name: WordPress Dublin
Plugin URI: https://wpdublin.com/
Description: Set a primary category for your (custom) posts and query them in your template using native WordPress queries.
Author: Ciprian Popescu
Author URI: https://wpdublin.com/
Version: 1.0.3
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
include 'classes/Updater.php';

/**
 * Include recommendations
 */
include 'classes/class-tgm-plugin-activation.php';

if (is_admin()) {
    $config = array(
        'slug' => plugin_basename(__FILE__),
        'proper_folder_name' => 'wpdublin',
        'github_url' => 'https://github.com/getButterfly/wpdublin',
        'requires' => '4.6',
        'tested' => '4.9.4',
        'readme' => 'readme.txt',
    );
    new WP_GitHub_Updater($config);
}

add_action('tgmpa_register', 'wpd_register_required_plugins');

function wpd_register_required_plugins() {
	$plugins = array(
		array(
			'name'      => 'WP Super Cache',
			'slug'      => 'wp-super-cache',
			'required'  => false,
		),
		array(
			'name'      => 'WP Sweep',
			'slug'      => 'wp-sweep',
			'required'  => false,
		),
		array(
			'name'      => 'WP Mail From II',
			'slug'      => 'wp-mailfrom-ii',
			'required'  => false,
		),
		array(
			'name'      => 'PHP Compatibility Checker',
			'slug'      => 'php-compatibility-checker',
			'required'  => false,
		),
	);

	$config = array(
		'id'           => 'wpdublin',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'notice_can_install_recommended'  => _n_noop(
                /* translators: 1: plugin name(s). */
                'WPDublin recommends the following plugin: %1$s.',
                'WPDublin recommends the following plugins: %1$s.',
                'tgmpa'
            )
        ),
    );

	tgmpa($plugins, $config);
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
