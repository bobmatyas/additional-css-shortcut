<?php
/**
 * Plugin Name:       Additional CSS Shortcut
 * Plugin URI:        https://github.com/your-username/additional-css-shortcut
 * Description:       Adds a quick-access link to the Additional CSS panel in the Site Editor for block themes.
 * Version:           1.0.1
 * Requires at least: 6.9
 * Requires PHP:      8.3
 * Author:            Bob Matyas
 * Author URI:        https://bobmatyas.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       additional-css-shortcut
 *
 * @package AdditionalCSSShortcut
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the Additional CSS link to the Appearance menu.
 *
 * Creates a submenu item under the Appearance menu that links directly
 * to the Additional CSS panel in the Site Editor.
 *
 * @since 1.0.0
 * @return void
 */
function acss_add_admin_menu() {
	// Only show for block themes.
	if ( ! wp_is_block_theme() ) {
		return;
	}

	add_submenu_page(
		'themes.php',
		__( 'Additional CSS', 'additional-css-shortcut' ),
		__( 'Additional CSS', 'additional-css-shortcut' ),
		'edit_theme_options',
		'additional-css-shortcut',
		'__return_null'
	);
}
add_action( 'admin_menu', 'acss_add_admin_menu' );

/**
 * Redirect to the Additional CSS panel before any output.
 *
 * @since 1.0.0
 * @return void
 */
function acss_redirect_to_additional_css() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No data processing, just admin page routing for redirect.
	if ( ! isset( $_GET['page'] ) || 'additional-css-shortcut' !== $_GET['page'] ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'additional-css-shortcut' ) );
	}

	wp_safe_redirect( admin_url( 'site-editor.php?p=%2Fstyles&section=%2Fcss' ) );
	exit;
}
add_action( 'admin_init', 'acss_redirect_to_additional_css' );
