<?php
/**
 * WP and PHP compatibility.
 *
 * Functions used to gracefully fail when a theme doesn't meet the minimum WP or
 * PHP versions required. Note that only code that will work on PHP 5.2.4 should
 * go into this file. Otherwise, it'll break on sites not meeting the minimum
 * PHP requirement. Only call this file after initially checking that the site
 * doesn't meet either the WP or PHP requirement.
 *
 * @package   TH6
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright 2018 Justin Tadlock
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0-or-later
 * @link      https://themehybrid.com
 */

# Add actions to fail at certain points in the load process.
add_action( 'after_switch_theme', 'th6_switch_theme'   );
add_action( 'load-customize.php', 'th6_load_customize' );
add_action( 'template_redirect',  'th6_preview'        );

/**
 * Returns the compatibility messaged based on whether the WP or PHP minimum
 * requirement wasn't met.
 *
 * @since  1.0.0
 * @access public
 * @return string
 */
function th6_compat_message() {

	if ( version_compare( $GLOBALS['wp_version'], '4.9.6', '<' ) ) {

		return sprintf(
			// Translators: 1 is the required WordPress version and 2 is the user's current version.
			__( 'TH6 requires at least WordPress version %1$s. You are running version %2$s. Please upgrade and try again.' ),
			'4.9.6',
			$GLOBALS['wp_version']
		);

	} elseif ( version_compare( PHP_VERSION, '5.6', '<' ) ) {

		return sprintf(
			// Translators: 1 is the required PHP version and 2 is the user's current version.
			__( 'TH6 requires at least PHP version %1$s. You are running version %2$s. Please upgrade and try again.' ),
			'5.6',
			PHP_VERSION
		);
	}

	return '';
}

/**
 * Switches to the previously active theme after the theme has been activated.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $old_name  Previous theme name/slug.
 * @return void
 */
function th6_switch_theme( $old_name ) {

	switch_theme( $old_name ? $old_name : WP_DEFAULT_THEME );

	unset( $_GET['activated'] );

	add_action( 'admin_notices', 'th6_upgrade_notice' );
}

/**
 * Outputs an admin notice with the compatibility issue.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function th6_upgrade_notice() {

	printf( '<div class="error"><p>%s</p></div>', esc_html( th6_compat_message() ) );
}

/**
 * Kills the loading of the customizer.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function th6_load_customize() {

	wp_die( esc_html( th6_compat_message() ), '', array( 'back_link' => true ) );
}

/**
 * Kills the customizer previewer on installs prior to WP 4.7.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function th6_preview() {

	if ( isset( $_GET['preview'] ) ) { // WPCS: CSRF ok.
		wp_die( esc_html( th6_compat_message() ) );
	}
}
