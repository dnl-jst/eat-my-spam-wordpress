<?php

/*
Plugin Name: EatMySpam
Plugin URL: https://github.com/dnl-jst/eat-my-spam-wordpress
Description: Check your WordPress comments for spam
Version: 0.4.0
Author: Daniel Jost
Author URL: http://www.daniel-jost.de/
*/

/**
* Security, checks if WordPress is running
**/
if ( !function_exists( 'add_action' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( is_admin() )
    require_once( 'classes/class.eat-my-spam.admin.php' );
else
    require_once( 'classes/class.eat-my-spam.php' );