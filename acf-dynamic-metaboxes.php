<?php
/*
Plugin Name: Advanced Custom Fields: Dynamic Metaboxes
Plugin URI: https://github.com/adde/acf-dynamic-metaboxes
Description: This plugin gives you the option to hide and show ACF metaboxes based on values on the post.
Version: 1.1.0
Author: Andreas Jönsson
Author URI: https://github.com/adde
License: MIT
Copyright: Andreas Jönsson
*/

class WPACFDM {

	/**
	 * Initialize
	 */
	public function __construct()
	{
		add_action( 'plugins_loaded',                      array( $this, 'load_textdomain' ) );
		add_action( 'admin_enqueue_scripts',               array( $this, 'enqueue_script' ) );
		add_action( 'admin_menu',                          array( $this, 'init_menu_page' ) );
		add_action( 'wp_ajax_acfdm_get_metaboxes',         array( $this, 'acfdm_get_metaboxes' ) );
		add_action( 'wp_ajax_nopriv_acfdm_get_metaboxes',  array( $this, 'acfdm_get_metaboxes' ) );
	}

	/**
	 * Load translations
	 */
	public function load_textdomain()
	{
		load_plugin_textdomain( 'acfdm', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
	}

	/**
	 * Add javascript to post edit pages in WP admin
	 */
	public function enqueue_script( $hook )
	{
		if ('post.php' != $hook) {
			return;
		}
		wp_enqueue_script( 'acfdm-toggle-metaboxes', plugins_url('js/toggle-metaboxes.js', __FILE__) );
		wp_localize_script( 'acfdm-toggle-metaboxes', 'acfdmConfig', array( 'categorySelector' => get_option( 'acfdm-category-selector' ) ) );
	}

	/**
	 * Add menu page and register settings.
	 */
	public function init_menu_page()
	{
		add_submenu_page(
			'options-general.php',
			__('Dynamic Metaboxes', 'acfdm' ),
			__('Dynamic Metaboxes', 'acfdm' ),
			'manage_options',
			'acf-dynamic-metaboxes',
			array( $this, 'menu_page' )
		);

		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
	}

	/**
	 * Register configuration field as a setting
	 */
	public function register_plugin_settings()
	{
		register_setting( 'acfdm-settings-group', 'acfdm-config' );
		register_setting( 'acfdm-settings-group', 'acfdm-category-selector' );
	}

	/**
	 * Render the menu page
	 */
	public function menu_page()
	{
		?>
		<div class='wrap'>
			<h2><?php _e( 'Dynamic Metaboxes for ACF', 'acfdm' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'acfdm-settings-group' ); ?>
				<?php do_settings_sections( 'acfdm-settings-group' ); ?>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><?php _e( 'Category selector', 'acfdm' ); ?></th>
							<td>
								<p><label for="acfdm-category-selector"><?php _e('Enter the CSS selector of the category dropdown that the plugin should check against', 'acfdm'); ?></label></p>
								<p>
									<input type="text" name="acfdm-category-selector" id="acfdm-category-selector" class="large-text" value="<?php echo esc_attr( get_option('acfdm-category-selector') ); ?>">
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e( 'Configuration', 'acfdm' ); ?></th>
							<td>
								<p><label for="acfdm-config"><?php _e( 'Enter the configuration as an JSON-expression. Make sure the JSON is valid, you can do this at', 'acfdm' ); ?> <a href="http://jsonlint.com/" target="_blank">jsonlint.com</a></label></p>
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
