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
 */
final class EatMySpam_Admin {

	const API_HOST = 'api.eat-my-spam.de';

	/**
	 * Version
	 *
	 * @var string
	 **/
	protected $version = '0.5.0';

	/**
	 * @var array
	 */
	protected $rulesets;

	/**
	 * Constructor
	 *
	 * @access public
	 * @author Daniel Jost
	 **/
	public function __construct() {

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'spam_comment', array( $this, 'report_spam' ) );
		add_action( 'unspam_comment', array( $this, 'report_ham' ) );

		add_filter( 'plugin_action_links_eat-my-spam/eat-my-spam.php', array( $this, 'add_settings_link' ) );
	}

	public function admin_init() {

		register_setting( 'eat-my-spam-settings', 'eatmyspam_threshold' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_remove_spam' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_send_notifications' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_excluded_rulesets' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_disable_reports' );

	}

	public function admin_menu() {

		add_options_page( 'EatMySpam Settings', 'EatMySpam', 'manage_options', 'eat-my-spam', array(
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

		$excludedRulesets = get_option( 'eatmyspam_excluded_rulesets', array() );

		if ( ! is_array( $excludedRulesets ) ) {
			$excludedRulesets = array();
		}

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

	public function report_ham( $comment_id ) {
		if ( get_option( 'eatmyspam_disable_reports' ) !== 'on' ) {
			$this->report( 'ham', $comment_id );
		}
	}

	public function report_spam( $comment_id ) {
		if ( get_option( 'eatmyspam_disable_reports' ) !== 'on' ) {
			$this->report( 'spam', $comment_id );
		}
	}

	protected function report( $type, $comment_id ) {

		global $wpdb;

		$comment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->comments} WHERE comment_ID = %d", $comment_id ), ARRAY_A );

		if ( $comment ) {

			$url = 'https://' . self::API_HOST . '/report';

			$data = array(
				'type'    => $type,
				'message' => $comment['comment_content']
			);

			$args = array(
				'headers'     => array(
					'Content-Type' => 'application/json; charset=' . get_option( 'blog_charset' ),
					'User-Agent'   => 'EatMySpam/' . $this->version . ', WordPress/' . $GLOBALS['wp_version']
				),
				'body'        => json_encode( $data ),
				'httpversion' => '1.0',
				'timeout'     => 15
			);

			$response = wp_remote_post( $url, $args );

			if ( is_wp_error( $response ) ) {
				return false;
			} else {
				return json_decode( $response['body'] );
			}

		}
	}

}

new EatMySpam_Admin();
