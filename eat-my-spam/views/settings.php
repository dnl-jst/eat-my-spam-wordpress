<div class="wrap">

    <h1><?php esc_html_e( 'EatMySpam Settings', 'eat-my-spam' ); ?></h1>

    <form method="post" action="options.php">

		<?php settings_fields( 'eat-my-spam-settings' ); ?>

		<?php do_settings_sections( 'eat-my-spam-settings' ); ?>

        <h2><?php esc_html_e( 'Settings:', 'eat-my-spam' ); ?></h2>

        <table>

            <tr>
                <th><label for="eatmyspam_threshold">Spam threshold:</label></th>
                <td><input type="number" id="eatmyspam_threshold" name="eatmyspam_threshold"
                           value="<?php echo get_option( 'eatmyspam_threshold', 5 ); ?>"></td>
            </tr>

            <tr>
                <td colspan="2">
                    <input id="eatmyspam_remove_spam" name="eatmyspam_remove_spam"
                           type="checkbox" <?php echo ( get_option( 'eatmyspam_remove_spam' ) === 'on' ) ? 'checked="checked"' : ''; ?>/>
                    <label for="eatmyspam_remove_spam">Remove spam?</label>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <input id="eatmyspam_send_notifications" name="eatmyspam_send_notifications"
                           type="checkbox" <?php echo ( get_option( 'eatmyspam_send_notifications' ) === 'on' ) ? 'checked="checked"' : ''; ?>/>
                    <label for="eatmyspam_send_notifications">Send spam notifications to admin?</label>
                </td>
            </tr>

        </table>

        <h2><?php esc_html_e( 'Excluded rulesets:', 'eat-my-spam' ); ?></h2>

        <table>

            <tr>
                <th></th>
                <th><?php esc_html_e( 'Ruleset key', 'eat-my-spam' ); ?></th>
                <th><?php esc_html_e( 'Ruleset description', 'eat-my-spam' ); ?></th>
            </tr>

			<?php foreach ( $rulesets as $ruleset ) : ?>
                <tr>
                    <td><input id="eatmyspam_exclude_ruleset_<?php echo esc_attr( $ruleset->key ); ?>"
                               type="checkbox"
                               name="eatmyspam_excluded_rulesets[]" <?php echo in_array( $ruleset->key, $excludedRulesets ) ? 'checked="checked"' : ''; ?>
                               value="<?php echo esc_attr( $ruleset->key ); ?>"/>
                    </td>
                    <td>
                        <label for="eatmyspam_exclude_ruleset_<?php echo esc_attr( $ruleset->key ); ?>"><?php echo esc_html( $ruleset->key ); ?></label>
                    </td>
                    <td><?php echo esc_html( $ruleset->title ); ?></td>
                </tr>
			<?php endforeach; ?>

        </table>

		<?php submit_button(); ?>

    </form>

</div>