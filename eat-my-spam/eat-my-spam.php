<?php

/*
Plugin Name: EatMySpam
Plugin URI: https://github.com/dnl-jst/eat-my-spam-wordpress
Description: Check your WordPress comments for spam
Version: 0.6.2
Author: Daniel Jost
Author URI: http://www.daniel-jost.de/
Text Domain: eat-my-spam
*/

/**
* Security, checks if WordPress is running
**/
if ( !function_exists( 'add_action' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

load_plugin_textdomain( 'eat-my-spam', false, basename( dirname( __FILE__ ) ) . '/languages/' );

if ( is_admin() )
    require_once( 'includes/eat-my-spam.admin.php' );
else
    require_once( 'includes/eat-my-spam.php' );