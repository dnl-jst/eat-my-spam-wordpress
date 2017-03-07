<?php

/**
 * Security, checks if WordPress is running
 **/
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


/**
 * EatMySpam class
 *
 * @package EatMySpam
 * @since v0.1.0
 * @author Daniel Jost
 **/
final class EatMySpam {

	const API_HOST = 'api.eat-my-spam.de';
	const VERSION = '0.1.0';

	/**
	 * Constructor
	 *
	 * @access public
	 * @since v0.1.0
	 * @author Daniel Jost
	 **/
	public function __construct() {
		add_filter( 'preprocess_comment', array( $this, 'check_comment' ), 1 );
	}

	/**
	 * check whether comment is spam
	 *
	 * @param array $commentdata
	 *
	 * @return array
	 */
	public function check_comment( $commentdata ) {

		$post              = get_post( $commentdata['comment_post_ID'] );
		$excluded_rulesets = get_option( 'eatmyspam_excluded_rulesets', array() );

		$result = $this->do_post( 'analyze', array(
			'message'          => $commentdata['comment_content'],
			'excludedRulesets' => $excluded_rulesets
		) );

		if ( $result !== null ) {

			if ( isset( $result->spam ) && $result->spam === true ) {

				wp_safe_redirect( esc_url_raw( get_permalink( $post ) ) );
				die();
			}
		}

		return $commentdata;
	}

	/**
	 * make a post request to the eat my spam api
	 *
	 * @param string $method method to be called
	 * @param string $data array of data to be posted
	 *
	 * @return bool|object decoded response from eat my spam api
	 */
	protected function do_post( $method, $data ) {
		$url = 'https://' . self::API_HOST . '/' . $method;

		$args = array(
			'headers'     => array(
				'Content-Type' => 'application/json; charset=' . get_option( 'blog_charset' ),
				'User-Agent'   => 'EatMySpam/' . self::VERSION . ', WordPress/' . $GLOBALS['wp_version']
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

new EatMySpam;
