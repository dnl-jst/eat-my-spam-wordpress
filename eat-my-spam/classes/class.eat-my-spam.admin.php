<?php

/**
 * Security, checks if WordPress is running
 **/
if ( ! function_exists( 'add_action' ) ) :
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

	const API_HOST = 'api.eat-my-spam.de';

	/**
	 * Version
	 *
	 * @var string
	 **/
	protected $version = '0.1.0';

	/**
	 * @var array
	 */
	protected $rulesets;

	/**
	 * Constructor
	 *
	 * @access public
	 * @since v0.1.0
	 * @author Daniel Jost
	 **/
	public function __construct() {

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_filter( 'plugin_action_links_eat-my-spam/eat-my-spam.php', array( $this, 'add_settings_link' ) );
	}

	public function admin_init() {

		register_setting( 'eat-my-spam-settings', 'eatmyspam_excluded_rulesets' );

	}

	public function admin_menu() {

		add_options_page( 'EatMySpam! Settings', 'EatMySpam!', 'manage_options', 'eat-my-spam', array(
			$this,
			'menu_page_callback'
		) );

	}

	public function menu_page_callback() {

		$response = $this->load_rulesets();

		if ( $response === false || ! isset( $response->rulesets ) || ! is_array( $response->rulesets ) ) {
			die( 'error loading rulesets' );
		}

		$rulesets = $response->rulesets;

		include( plugin_dir_path( __FILE__ ) . '/../views/settings.php' );

	}

	protected function load_rulesets() {
		$url = 'https://' . self::API_HOST . '/rulesets';

		$args = array(
			'headers'     => array(
				'User-Agent' => 'EatMySpam/' . $this->version . ', WordPress/' . $GLOBALS['wp_version']
			),
			'httpversion' => '1.0',
			'timeout'     => 15
		);

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		} else {
			return json_decode( $response['body'] );
		}
	}

	public function add_settings_link( $links ) {

		$settings_link = '<a href="options-general.php?page=eat-my-spam">' . __( 'Settings', 'eat-my-spam' ) . '</a>';

		array_unshift( $links, $settings_link );

		return $links;
	}

}

new EatMySpam_Admin();
