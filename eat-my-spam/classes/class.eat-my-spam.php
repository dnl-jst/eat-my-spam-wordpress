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
 * @author Daniel Jost
 **/
final class EatMySpam {

	const API_HOST = 'api.eat-my-spam.de';
	const VERSION = '0.4.0';

	/**
	 * Constructor
	 *
	 * @access public
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
	public function check_comment( $comment ) {

		$post              = get_post( $comment['comment_post_ID'] );
		$excluded_rulesets = get_option( 'eatmyspam_excluded_rulesets', array() );

		$result = $this->do_post( 'analyze', array(
			'message'          => $comment['comment_content'],
			'excludedRulesets' => $excluded_rulesets,
			'threshold'        => get_option( 'eatmyspam_threshold', 5 )
		) );

		if ( $result !== null ) {

			if ( isset( $result->spam ) && $result->spam === true ) {

				if ( get_option( 'eatmyspam_remove_spam' ) === 'on' ) {

					wp_safe_redirect( esc_url_raw( get_permalink( $post ) ) );
					die();


				} else {

					add_filter(
						'pre_comment_approved',
						create_function(
							'',
							'return "spam";'
						)
					);

				}

				if ( get_option( 'eatmyspam_send_notifications' ) === 'on' ) {

					add_filter(
						'comment_post',
						array(
							__CLASS__,
							'send_mail_notification'
						)
					);

				}
			}
		}

		return $comment;
	}

	/**
	 * @param int $id
	 *
	 * @return int
	 */
	public function send_mail_notification( $id ) {
		$comment = get_comment( $id, ARRAY_A );

		if ( empty( $comment ) ) {
			return $id;
		}

		$subject = sprintf(
			'[%s] %s',
			stripslashes_deep(
				html_entity_decode(
					get_bloginfo( 'name' ),
					ENT_QUOTES
				)
			),
			esc_html__( 'Comment marked as spam', 'eat-my-spam' )
		);

		if ( ! $content = strip_tags( stripslashes( $comment['comment_content'] ) ) ) {
			$content = sprintf(
				'-- %s --',
				esc_html__( 'Content removed by EatMySpam', 'eat-my-spam' )
			);
		}

		wp_mail(
			get_bloginfo( 'admin_email' ),
			$subject,
			$content
		);

		return $id;
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
