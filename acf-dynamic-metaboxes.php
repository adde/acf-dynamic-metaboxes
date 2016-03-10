<?php
/*
Plugin Name: Advanced Custom Fields: Dynamic Metaboxes
Plugin URI:
Description: This plugin gives you the option to hide and show ACF metaboxes based on values on the post.
Version: 1.1.0
Author: Andreas Jönsson
Author URI:
License: MIT
Copyright: Andreas Jönsson
*/



class WPACFDM {

	public function __construct()
	{
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_script' ) );
		add_action( 'admin_menu', array( $this, 'init_menu_page' ) );
		add_action( 'wp_ajax_acfdm_get_metaboxes',        array( $this, 'acfdm_get_metaboxes' ) );
		add_action( 'wp_ajax_nopriv_acfdm_get_metaboxes', array( $this, 'acfdm_get_metaboxes' ) );
	}

	/**
	 * Add javascript to post edit pages in WP admin
	 */
	public function enqueue_script( $hook)
	{
		if ('post.php' != $hook) {
			return;
		}
		wp_enqueue_script( 'acfdm-toggle-metaboxes', plugins_url('js/toggle-metaboxes.js', __FILE__) );
	}

	/**
	 * Add menu page and register settings.
	 */
	public function init_menu_page() {
		add_submenu_page(
			'options-general.php',
			'Dynamic Metaboxes',
			'Dynamic Metaboxes',
			'manage_options',
			'acf-dynamic-metaboxes',
			array( $this, 'menu_page' )
		);

		//call register settings function
		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
	}

	/**
	 * Render the menu page
	 */
	public function menu_page() {
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
	 * Register configuration field as a setting
	 */
	public function register_plugin_settings() {
		register_setting( 'acfdm-settings-group', 'acfdm-config' );
	}

	/**
	 * Ajax endpoint for metabox information
	 */
	public function acfdm_get_metaboxes()
	{
		global $wpdb;

		$res = get_option( 'acfdm-config' );

		// Output as JSON
		header( "Content-Type:application/json;charset=utf-8" );
		echo $res;
		wp_die();
	}

}

$wpAcfDm = new WPACFDM();






