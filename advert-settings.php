<?php
/**
 * Plugin Name:     Advert Settings
 * Description:     Simple WordPress Plugin
 * Version:         0.1.0
 * Author:          Bhaskar K C
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     advert-settings
 *
 * @package         advertisement-settings
 */

/**
 * Class AdvertisementSettings
 */
class AdvertisementSettings {

	const FIELDNAMES = [
		'_advert_enabled' => 'boolean',
		'_advert_name'    => 'string',
		'_advert_type'    => 'string',
	];

	/**
	 * Instance
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Singleton instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'advert_settings_register_meta_fields' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Register fields to the rest API.
	 */
	public function advert_settings_register_meta_fields() {
		foreach ( self::FIELDNAMES as $fieldname => $type ) {
			register_meta( 'post', $fieldname,
				[
					'show_in_rest'  => true,
					'auth_callback' => fn() => true,
					'single'        => true,
					'type'          => $type,
				]
			);
		}
	}

	/**
	 * Enqueue Scripts
	 */
	public function enqueue_scripts() {
		if ( get_current_screen()->post_type !== 'post' ) {
			return;
		}

		wp_enqueue_script(
			'advert-settings-sidebar-js',
			plugin_dir_url( __FILE__ ) . 'build/index.js',
			[
				'wp-plugins',
				'wp-edit-post',
				'wp-element',
				'wp-components',
				'wp-data',
				'wp-dom-ready',
			],
			filemtime( dirname( __FILE__ ) . '/build/index.js' )
		);
	}
}

AdvertisementSettings::get_instance();
