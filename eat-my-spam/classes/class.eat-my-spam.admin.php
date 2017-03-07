<?php

/**
 * Security, checks if WordPress is running
 **/
if ( !function_exists( 'add_action' ) ) :
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
endif;



/**
 * EatMySpam Admin Class
 *
 * @package EatMySpam
 * @since 0.1.0
 * @author Daniel Jost
 */
final class EatMySpam_Admin {

	/**
	 * Version
	 *
	 * @var string
	 **/
	protected $version = '0.1.0';

	/**
	 * Constructor
	 *
	 * @access public
	 * @since v0.1.0
	 * @author Daniel Jost
	 **/
	public function __construct()
    {
		//
	}

}

new EatMySpam_Admin();
