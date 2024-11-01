<?php

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!defined('WP_UNINSTALL_PLUGIN')) {
    return;
}

delete_option('snapwizard_app_id');
delete_option('snapwizard_app_secret');
delete_option('snapwizard_secret_key');
delete_option('snapwizard_redirect_url');
delete_option('snapwizard_process_ig_feed_url');
delete_option('snapwizard_token');
delete_option('snapwizard_crypted_token');

delete_option('snapwizard_file_type');
delete_option('snapwizard_author');
delete_option('snapwizard_post_categories');
delete_option('snapwizard_media_categories');
delete_option('snapwizard_limit_per_page');
delete_option('snapwizard_exclude');

delete_option('snapwizard_last_run');
delete_option('snapwizard_computations_ms');
delete_option('snapwizard_system_calls_ms');
delete_option('snapwizard_last_refreshing_token');
