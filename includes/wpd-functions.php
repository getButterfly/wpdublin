<?php
function wpd_get_site_details() {
    global $wpdb, $wp_version;

    // Assign details
    $wpd_current_theme = wp_get_theme();

    $wpd_has_gzip = 0;
    if ($_SERVER['HTTP_ACCEPT_ENCODING'] == 'gzip' || function_exists('ob_gzhandler') || ini_get('zlib.output_compression')) {
        $wpd_has_gzip = 1;
    }

    $wpd_has_https = 'HTTPS=off';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $wpd_has_https = 'HTTPS=on';
    } else if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
        $wpd_has_https = 'HTTPS=on;SSL=on';
    }

    $wpd_db_size = 0;

    $results = $wpdb->get_results("SHOW TABLE STATUS FROM " . DB_NAME, ARRAY_A);
    foreach ($results as $row) {
        $usedspace = $row['Data_length'] + $row['Index_length'];
        $usedspace = $usedspace / 1024;
        $usedspace = round($usedspace, 2);
        $wpd_db_size += $usedspace;
    }

    $wpd_cpu = '';
    if (function_exists('sys_getloadavg')) {
        $load = sys_getloadavg();
        $wpd_cpu = 'Current CPU load: ' . implode(', ', $load);
    }

    $wpd_wp_version      = $wp_version; // WordPress version
    $wpd_wp_theme        = $wpd_current_theme->get('Name') . ' ' . $wpd_current_theme->get('Version') . ' (<code>' . get_template_directory_uri() . '</code>)'; // Current theme
    $wpd_wp_memory       = WP_MEMORY_LIMIT; // Memory limit
    $wpd_wp_db_size      = $wpd_db_size; // Database size
    $wpd_server_version  = $_SERVER['SERVER_SOFTWARE']; // Server software
    $wpd_server_protocol = wp_get_server_protocol(); // Server protocol
    $wpd_server_ssl      = $wpd_has_https; // Server certificate
    $wpd_server_gzip     = $wpd_has_gzip; // GZip
    $wpd_server_cpu      = $wpd_cpu; // CPU load
    $wpd_php_version     = PHP_VERSION; // PHP version
    $wpd_mysql_version   = $wpdb->db_version(); // MySQL version

    /**
     * get_option('active_plugins');
     * get_plugins() will give you all the plugins including the inactive ones.
     * https://codex.wordpress.org/Function_Reference/get_plugins
     * https://wordpress.stackexchange.com/a/54782
     */
    $wpd_active_plugins_array = get_option('active_plugins'); 
    $wpd_active_plugins = '<ul>';
    foreach($wpd_active_plugins_array as $key => $value) {
        $string = explode('/', $value);
        $wpd_active_plugins .= '<li>' . $string[0] . '</li>';
    }
    $wpd_active_plugins .= '</ul>';

    $specificationsTable = '<h3>Site Specifications</h3>
    <ul>
        <li>WordPress Address (URL): ' . get_option('siteurl') . '</li>
        <li>Site Address (URL): ' . home_url() . '</li>
        <li>WordPress version: ' . $wpd_wp_version . '</li>
        <li>Current theme: ' . $wpd_wp_theme . '</li>
        <li>WordPress memory limit: ' . $wpd_wp_memory . '</li>
        <li>WordPress database size: ' . $wpd_wp_db_size . '</li>
        <li>Server software: ' . $wpd_server_version . '</li>
        <li>Server PHP version: ' . $wpd_php_version . '</li>
        <li>Server MySQL version: ' . $wpd_mysql_version . '</li>
        <li>Server protocol: ' . $wpd_server_protocol . '</li>
        <li>Server certificate: ' . $wpd_server_ssl . '</li>
        <li>Server GZip support: ' . $wpd_has_gzip . '</li>
        <li>Server CPU: ' . $wpd_server_cpu . '</li>
        <li>Active plugins: ' . $wpd_active_plugins . '</li>
    </ul>';

    /*
    List of registered post types
    List of plugins
    List of themes
    Admin email
    HTTP/1/2
    OPcache support
    Is debug active
    */

    return $specificationsTable;
}
