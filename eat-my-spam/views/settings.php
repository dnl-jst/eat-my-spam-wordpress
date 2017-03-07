<div class="wrap">

    <h1><?php esc_html_e( 'EatMySpam Settings', 'eat-my-spam' ); ?></h1>

    <form method="post" action="options.php">

		<?php settings_fields( 'eat-my-spam-settings' ); ?>

		<?php do_settings_sections( 'eat-my-spam-settings' ); ?>

        <h2><?php esc_html_e( 'Excluded rulesets:', 'eat-my-spam' ); ?></h2>

        <table>

            <tr>
                <th></th>
                <th><?php esc_html_e( 'Ruleset key', 'eat-my-spam' ); ?></th>
                <th><?php esc_html_e( 'Ruleset description', 'eat-my-spam' ); ?></th>
            </tr>

			<?php foreach ( $rulesets as $ruleset ) : ?>
                <tr>
                    <td><input type="checkbox"
                               name="eatmyspam_excluded_rulesets[]" <?php echo in_array( $ruleset->key, get_option( 'eatmyspam_excluded_rulesets' ) ) ? 'checked="checked"' : ''; ?>
                               value="<?php echo esc_attr( $ruleset->key ); ?>"/>
                    </td>
                    <td><?php echo esc_html( $ruleset->key ); ?></td>
                    <td><?php echo esc_html( $ruleset->title ); ?></td>
                </tr>
			<?php endforeach; ?>

        </table>

		<?php submit_button(); ?>

    </form>

</div>