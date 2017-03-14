<?php

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) exit();

delete_option('eatmyspam_threshold' );
delete_option('eatmyspam_remove_spam' );
delete_option('eatmyspam_send_notifications' );
delete_option('eatmyspam_excluded_rulesets' );
delete_option('eatmyspam_disable_reports' );
delete_option('eatmyspam_disable_cf7_integration' );