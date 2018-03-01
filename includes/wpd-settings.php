<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

function wpd_settings() { ?>
	<div class="wrap">
		<h2><?php _e('WordPress Dublin', 'wpdublin'); ?></h2>

        <?php $tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard'; ?>
        <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url('admin.php?page=wpd_settings&amp;tab=dashboard'); ?>" class="nav-tab <?php echo $tab == 'dashboard' ? 'nav-tab-active' : ''; ?>"><?php _e('Dashboard', 'wpdublin'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpd_settings&amp;tab=assistance'); ?>" class="nav-tab <?php echo $tab == 'assistance' ? 'nav-tab-active' : ''; ?>"><?php _e('Request Assistance', 'wpdublin'); ?></a>
        </h2>

        <?php if ((string) $tab === 'dashboard') { ?>
			<div class="wpd-card-welcome">
				<div class="wpd-card-welcome-element">
					<h2><?php _e('Hello!', 'wpdublin'); ?></h2>
				</div>
				<div class="wpd-card-welcome-element">
					<div class="wpd-card-welcome-element-title"><?php _e('What should I do next?', 'wpdublin'); ?></div>
					<div class="wpd-card-welcome-element-body"><?php _e('This plugin will help you contact us in case you need help with a quick fix, configuration change, malware detection and removal, feature request, bug report and more. Keep this plugin active so we can connect to your site and assist with any issue.', 'wpdublin'); ?></div>
				</div>
				<div class="wpd-card-welcome-element">
					<div class="wpd-card-welcome-element-title"><?php _e('What are my <b>WordPress Dublin</b> details?', 'wpdublin'); ?></div>
					<div class="wpd-card-welcome-element-body">
						<?php echo sprintf(__('Check your <a href="%s">WordPress Dublin Account</a>, check our <a href="%s">knowledgebase</a> or check our <a href="%s">frequently asked questions</a>.', 'wpdublin'), 'https://wpdublin.com/register/login/', 'https://wpdublin.com/category/blog/', 'https://wpdublin.com/frequently-asked-questions/'); ?>
					</div>
				</div>
				<div class="wpd-card-welcome-element">
					<div class="wpd-card-welcome-element-title"><?php _e('What does this plugin do?', 'wpdublin'); ?></div>
					<div class="wpd-card-welcome-element-body">
						<?php _e('This plugin allows us to request technical details and specifications in order to diagnose and fix various issues. It also bundles several helpers and utilities, such as uptime monitoring and on-demand malware checking.', 'wpdublin'); ?>
					</div>
				</div>
				<div class="wpd-card-welcome-element">
					<div class="wpd-card-welcome-element-title"><?php _e('Downtime happens. Get notified!', 'wpdublin'); ?></div>
					<div class="wpd-card-welcome-element-body">
                        <?php _e('Your site is checked every 5 minutes for uptime/downtime, using HTTP(S) on ports 80 and 443. We use multiple locations for each monitor (Ireland, UK and USA). In case of the site (connection) going down, we will alert you immediately via email.', 'wpdublin'); ?>
                    </div>
                </div>
			</div>
        <?php } else if ((string) $tab === 'assistance') {
            if (isset($_POST['info_update']) && current_user_can('manage_options')) {
                
                $wpd_email_request_subject = sanitize_text_field($_POST['wpd_email_request_subject']);
                $wpd_email_request_body = wp_kses_post($_POST['wpd_email_request_body']);

                $headers = array('Content-Type: text/html; charset=UTF-8');
                $wpd_body = '<h3>' . $wpd_email_request_subject . '</h3>' . $wpd_email_request_body;
                $wpd_body .= wpd_get_site_details();

                //wp_mail('ciprian@wpdublin.com', 'WPDublin Internal Support Request', $wpd_body, $headers);
                wp_mail('getbutterfly@gmail.com', 'WPDublin Internal Support Request', $wpd_body, $headers);

                echo '<div class="updated notice is-dismissible">
                    <p>' . __('Email request sent!', 'wpdublin') . '</p>
                    <p>' . __('We will investigate the issue and we will contact you if we need more details. Thank you for your patience.', 'wpdublin') . '</p>
                </div>';
            }
            $settings = array('media_buttons' => true);
            ?>

            <form method="post" action="">
                <h3><?php _e('New Assistance Request', 'wpdublin'); ?></h3>

                <p><span class="dashicons dashicons-editor-help"></span> <?php _e('What can we help you with today?', 'wpdublin'); ?></p>
                <p>
                    <label for="wpd-email-request-subject"><?php _e('Subject', 'wpdublin'); ?></label><br>
                    <input type="text" name="wpd_email_request_subject" id="wpd-email-request-subject" placeholder="<?php _e('I need help with...', 'wpdublin'); ?>" class="regular-text">
                    <br><small><?php _e('Keep the request subject short and concise.', 'wpdublin'); ?></small>
                </p>
                <p>
                    <label for="wpd-email-request-priority">Priority</label><br>
                    <select name="wpd_email_request_priority" id="wpd-email-request-priority" class="regular-text">
                        <option value="0">Low</option>
                        <option value="1">Minor</option>
                        <option value="2">Normal</option>
                        <option value="3">Major</option>
                        <option value="4">Critical</option>
                    </select>
                    <br><small>Use it responsibly.</small>
                </p>
                <p>
                    <label for="wpd-email-request-body">Description/Request</label><br>
                    <?php wp_editor('', 'wpd_email_request_body', $settings); ?>
                </p>

                <p><input type="submit" name="info_update" class="button button-primary" value="<?php _e('Send Request', 'wpdublin'); ?>"></p>
            </form>
        <?php } ?>
	</div>
<?php
}
