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
class EatMySpam_Admin {

	const API_HOST = 'api.eat-my-spam.de';

	/**
	 * Version
	 *
	 * @var string
	 **/
	protected static $version = '0.8.1';

	/**
	 * @var array
	 */
	protected static $rulesets;

	/**
	 * Constructor
	 *
	 * @access public
	 * @author Daniel Jost
	 **/
	public static function init() {

		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );

		add_action( 'spam_comment', array( __CLASS__, 'report_spam' ) );
		add_action( 'unspam_comment', array( __CLASS__, 'report_ham' ) );

		add_filter( 'plugin_action_links_eat-my-spam/eat-my-spam.php', array( __CLASS__, 'add_settings_link' ) );

		self::init_plugin_sources();

		wp_enqueue_script('ems_script');
		wp_enqueue_script('ems_script2');
		wp_enqueue_style('ems_style');

	}

	public static function admin_init() {

		register_setting( 'eat-my-spam-settings', 'eatmyspam_threshold' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_remove_spam' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_send_notifications' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_excluded_rulesets' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_disable_reports' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_disable_cf7_integration' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_delete_spam_after_days' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_allowed_languages' );
		register_setting( 'eat-my-spam-settings', 'eatmyspam_disallowed_languages' );

	}

	public static function admin_menu() {

		$page = add_options_page( 'EatMySpam Settings', 'EatMySpam', 'manage_options', 'eat-my-spam', array(
			__CLASS__,
			'menu_page_callback'
		) );

		# integrate js and styles
		add_action( 'admin_print_scripts-' . $page, array( __CLASS__, 'add_options_script' ) );
		add_action( 'admin_print_styles-' . $page, array( __CLASS__, 'add_options_style' ) );
	}

	public static function menu_page_callback() {

		$response = self::load_rulesets();

		if ( $response === false || ! isset( $response->rulesets ) || ! is_array( $response->rulesets ) ) {
			die( 'error loading rulesets' );
		}

		$rulesets = $response->rulesets;

		$response = self::load_languages();

		if ( $response === false || ! isset( $response->languages ) || ! is_array( $response->languages ) ) {
			die( 'error loading languages' );
		}

		$languages = $response->languages;

		$excludedRulesets = get_option( 'eatmyspam_excluded_rulesets', array() );

		if ( ! is_array( $excludedRulesets ) ) {
			$excludedRulesets = array();
		}

		$allowedLanguages = get_option( 'eatmyspam_allowed_languages', array() );

		if ( ! is_array( $allowedLanguages ) ) {
			$allowedLanguages = array();
		}

		$disallowedLanguages = get_option( 'eatmyspam_disallowed_languages', array() );

		if ( ! is_array( $disallowedLanguages ) ) {
			$disallowedLanguages = array();
		}

		include( plugin_dir_path( __FILE__ ) . '/../views/settings.php' );

	}

	protected static function load_rulesets() {

		$url = 'https://' . self::API_HOST . '/rulesets';

		$args = array(
			'headers'     => array(
				'User-Agent' => 'EatMySpam/' . self::$version . ', WordPress/' . $GLOBALS['wp_version']
			),
			'httpversion' => '1.0',
			'timeout'     => 5
		);

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		} else {
			return json_decode( $response['body'] );
		}
	}

	protected static function load_languages() {

		$url = 'https://' . self::API_HOST . '/languages';

		$args = array(
			'headers'     => array(
				'User-Agent' => 'EatMySpam/' . self::$version . ', WordPress/' . $GLOBALS['wp_version']
			),
			'httpversion' => '1.0',
			'timeout'     => 5
		);

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		} else {
			return json_decode( $response['body'] );
		}
	}

	public static function add_settings_link( $links ) {

		$settings_link = '<a href="options-general.php?page=eat-my-spam">' . __( 'Settings', 'eat-my-spam' ) . '</a>';

		array_unshift( $links, $settings_link );

		return $links;
	}

	public static function report_ham( $comment_id ) {
		if ( get_option( 'eatmyspam_disable_reports' ) !== 'on' ) {
			self::report( 'ham', $comment_id );
		}
	}

	public function report_spam( $comment_id ) {
		if ( get_option( 'eatmyspam_disable_reports' ) !== 'on' ) {
			self::report( 'spam', $comment_id );
		}
	}

	protected static function report( $type, $comment_id ) {

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
					'User-Agent'   => 'EatMySpam/' . self::$version . ', WordPress/' . $GLOBALS['wp_version']
				),
				'body'        => json_encode( $data ),
				'httpversion' => '1.0',
				'timeout'     => 5
			);

			$response = wp_remote_post( $url, $args );

			if ( is_wp_error( $response ) ) {
				return false;
			} else {
				return json_decode( $response['body'] );
			}

		}
	}

	public static function init_plugin_sources()
	{
		wp_register_script(
			'ems_script',
			plugins_url('../js/select2.full.min.js', __FILE__),
			array('jquery')
		);

		wp_register_script(
			'ems_script2',
			plugins_url('../js/main.js', __FILE__),
			array('ems_script')
		);

		wp_register_style(
			'ems_style',
			plugins_url('../css/select2.min.css', __FILE__),
			array()
		);
	}

}

EatMySpam_Admin::init();