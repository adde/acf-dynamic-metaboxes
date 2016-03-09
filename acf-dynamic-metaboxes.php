<?php
/*
Plugin Name: Advanced Custom Fields: Dynamic Metaboxes
Plugin URI:
Description: This plugin gives you the option to hide and show ACF metaboxes based on values on the post.
Version: 1.0.0
Author: Andreas Jönsson
Author URI:
License: MIT
Copyright: Andreas Jönsson
*/



/**
 * Add javascript to post edit pages in WP admin
 */
function acfdm_custom_admin_js( $hook ) {
	if ('post.php' != $hook) {
		return;
	}
	wp_enqueue_script( 'acfdm-toggle-metaboxes', plugins_url('js/toggle-metaboxes.js', __FILE__) );
}
add_action('admin_enqueue_scripts', 'acfdm_custom_admin_js');



/**
 * Add menu page and register settings.
 */
add_action( 'admin_menu', 'acfdm_custom_menu_page' );

function acfdm_custom_menu_page() {
	add_submenu_page(
		'options-general.php',
		'Dynamic Metaboxes',
		'Dynamic Metaboxes',
		'manage_options',
		'acf-dynamic-metaboxes',
		'acfdm_menu_page'
	);

	//call register settings function
	add_action( 'admin_init', 'register_acfdm_plugin_settings' );
}



/**
 * Register configuration field as a setting
 */
function register_acfdm_plugin_settings() {
	//register our settings
	register_setting( 'acfdm-settings-group', 'acfdm-config' );
}



/**
 * Render the menu page
 */
function acfdm_menu_page() {
	?>
	<div class='wrap'>
		<h2>Dynamic Metaboxes for ACF</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'acfdm-settings-group' ); ?>
			<?php do_settings_sections( 'acfdm-settings-group' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">Configuration</th>
						<td>
							<p><label for="acfdm-config">Enter the configuration as an JSON-expression. Make sure the JSON is valid, you can do this at <a href="http://jsonlint.com/" target="_blank">jsonlint.com</a></label></p>
							<p>
								<textarea name="acfdm-config" rows="25" cols="50" id="acfdm-config" class="large-text code"><?php echo esc_attr( get_option('acfdm-config') ); ?></textarea>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}



/**
 * Ajax endpoint for metabox information
 */
function acfdm_get_metaboxes()
{
	global $wpdb;

	$res = get_option( 'acfdm-config' );

	// Output as JSON
	header( "Content-Type:application/json;charset=utf-8" );
	echo $res;
	wp_die();
}

add_action( 'wp_ajax_acfdm_get_metaboxes',        'acfdm_get_metaboxes' );
add_action( 'wp_ajax_nopriv_acfdm_get_metaboxes', 'acfdm_get_metaboxes' );