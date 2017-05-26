<div class="wrap">

    <h1><?php esc_html_e( 'EatMySpam Settings', 'eat-my-spam' ); ?></h1>

    <form method="post" action="options.php">

		<?php settings_fields( 'eat-my-spam-settings' ); ?>

		<?php do_settings_sections( 'eat-my-spam-settings' ); ?>

        <h2><?php esc_html_e( 'Settings:', 'eat-my-spam' ); ?></h2>

        <table>

            <tr>
                <td colspan="3">
                    <label for="eatmyspam_threshold"><?php esc_html_e( 'Spam threshold:', 'eat-my-spam' ); ?></label><br>
                    <input type="number" id="eatmyspam_threshold" name="eatmyspam_threshold"
                           value="<?php echo get_option( 'eatmyspam_threshold', 5 ); ?>">
                </td>
            </tr>


            <tr>
                <td colspan="3">
                    <input id="eatmyspam_send_notifications" name="eatmyspam_send_notifications"
                           type="checkbox" <?php echo ( get_option( 'eatmyspam_send_notifications' ) === 'on' ) ? 'checked="checked"' : ''; ?>/>
                    <label for="eatmyspam_send_notifications"><?php esc_html_e( 'Send spam notifications to admin?', 'eat-my-spam' ); ?></label>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <input id="eatmyspam_disable_reports" name="eatmyspam_disable_reports"
                           type="checkbox" <?php echo ( get_option( 'eatmyspam_disable_reports' ) === 'on' ) ? 'checked="checked"' : ''; ?>/>
                    <label for="eatmyspam_disable_reports"><?php esc_html_e( 'Disable automatic reports to EatMySpam server?', 'eat-my-spam' ); ?></label>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <input id="eatmyspam_disable_cf7_integration" name="eatmyspam_disable_cf7_integration"
                           type="checkbox" <?php echo ( get_option( 'eatmyspam_disable_cf7_integration' ) === 'on' ) ? 'checked="checked"' : ''; ?>/>
                    <label for="eatmyspam_disable_cf7_integration"><?php esc_html_e( 'Disable Contact Form 7 integration?', 'eat-my-spam' ); ?></label>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <input id="eatmyspam_remove_spam" name="eatmyspam_remove_spam"
                           type="checkbox" <?php echo ( get_option( 'eatmyspam_remove_spam' ) === 'on' ) ? 'checked="checked"' : ''; ?>/>
                    <label for="eatmyspam_remove_spam"><?php esc_html_e( 'Remove spam directly?', 'eat-my-spam' ); ?>
                        <span class="color: grey;">(<?php esc_html_e( 'Only works with WordPress comments. Otherwise spam is just tagged as spam and can be reviewed in the comment management area.', 'eat-my-spam' ); ?>)</span>
                    </label>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <label for="eatmyspam_delete_spam_after_days"><?php esc_html_e( 'Remove spam after amount of days:', 'eat-my-spam' ); ?>
                        <span class="color: grey;">(<?php esc_html_e( 'Only works with WordPress comments. If you do not delete spam directly, spam will be deleted automatically after this configured number of days. Defaults to 0 which disables this function.', 'eat-my-spam' ); ?>)</span>
                    </label><br>
                    <input id="eatmyspam_delete_spam_after_days" name="eatmyspam_delete_spam_after_days"
                           type="number" value="<?php echo get_option( 'eatmyspam_delete_spam_after_days', 0 ); ?>"/>
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