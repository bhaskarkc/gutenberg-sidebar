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

	const POST_TYPE         = 'post',
		ATTR_ADVERT_ENABLED = 'advert-enabled',
		ATTR_ADVERT_TYPE    = 'advert-type',
		ATTR_ADVERT_NAME    = 'advert-name';

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
		add_action( 'init', [ $this, 'gutenberg_block' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'save_post', [ $this, 'save' ] );
		add_action( 'init', [ $this, 'advert_settings_register_meta' ] );
		add_action( 'rest_api_init', [ $this, 'register_advert_meta_route' ] );
	}

	/**
	 * Registers rest ep for updating advert meta
	 */
	public function register_advert_meta_route() {
		register_rest_route(
			'advert-settings/v1', '/update-meta',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'rest_api_advert_meta_update' ],
				'permission_callback' => [ $this, 'check_ep_permission' ],
				'args'                => [ 'id' => [ 'sanitize_callback', 'absint' ] ],
			]
		);
	}

	/**
	 * Checks permission for "/update-meta" endpoint
	 */
	public function check_ep_permission() {
		return current_user_can( 'edit_posts' )
		? true
		: new WP_Error( 'rest_forbidden', 'Permission denied!' );
	}

	/**
	 * Update advert meta.
	 *
	 * @param array $data
	 */
	public function rest_api_advert_meta_update( $data ) {
		var_dump( $data );
		die();
	}

	/**
	 * Register fields to the rest API.
	 */
	public function advert_settings_register_meta() {
		register_post_meta(
			'post', '_advert_settings_fields', [
				'single'       => true,
				'show_in_rest' => true,
				'type'         => 'array',
				'show_in_rest' => [
					'schema' => [
						'type'  => 'array',
						'items' => [
							'type' => 'string',
						],
					],
				],
			]
		);
	}

	/**
	 * Add meta box
	 *
	 * @param string $post_type
	 */
	public function add_meta_box( $post_type ) {
		if ( $post_type != 'post' ) {
			return;
		}
		add_meta_box(
			'advert-settings',
			'Advertisement Setttings',
			[ $this, 'metabox_display_callback' ],
			$post_type,
			/* 'side', */
			/* 'high', */
			[ '__back_compat_meta_box' => false ],
		);
	}

	/**
	 * Metabox display callback
	 *
	 * @param  WP_Post $post
	 */
	public function metabox_display_callback( $post ) {

		$advert_enabled  = get_post_meta( $post->ID, self::ATTR_ADVERT_ENABLED, true ) ?? 'off';
		$advert_type     = get_post_meta( $post->ID, self::ATTR_ADVERT_TYPE, true ) ?? 'none';
		$advertiser_name = get_post_meta( $post->ID, self::ATTR_ADVERT_NAME, true ) ?? '';

		ob_start();
		wp_nonce_field( 'nonce_action_advert_meta_box', 'advert_meta_box_nonce' );
		?>
			<div class="advert-metabox">
				<div id="field-1">
				<input type="checkbox" name="<?php echo self::ATTR_ADVERT_ENABLED; ?>"
					<?php echo checked( $advert_enabled, 'On' ); ?>>
					<label for="enable-advert">Advertisements</label>
				</div>
				<div id="field-2">
				<input type="radio" id="contentChoice1" name="<?php echo self::ATTR_ADVERT_TYPE; ?>"
					value="none" <?php echo checked( $advert_type, 'none' ); ?>>
					<label for="contentChoice1">None</label>

					<input type="radio" id="contentChoice2" name="<?php echo self::ATTR_ADVERT_TYPE; ?>"
						value="sponsored-content" <?php echo checked( $advert_type, 'sponsored-content' ); ?>>
					<label for="contentChoice2">Sponsored Content</label>

					<input type="radio" id="contentChoice3" name="<?php echo self::ATTR_ADVERT_TYPE; ?>"
						name="advert-type" <?php echo checked( $advert_type, 'partnered-content' ); ?>>
					<label for="contentChoice3">Partnered Content</label>

					<input type="radio" id="contentChoice4" name="<?php echo self::ATTR_ADVERT_TYPE; ?>"
						value="brought-to-you-by" <?php echo checked( $advert_type, 'brought-to-you-by' ); ?>>
					<label for="contentChoice4">Brought to you by</label>
				</div>
				<div id="field-3">
					<label for="advert-name">Advertiser Name</label>
					<input type="text" id="advert-name" name="<?php echo self::ATTR_ADVERT_TYPE; ?>"
						value="<?php echo $advertiser_name; ?>">
				</div>
			</div>
		<?php
		echo ob_get_clean();
	}

	/**
	 * Save Advert metabox settings.
	 *
	 * @param int $post_id
	 */
	public function save( $post_id ) {
		// Bail if nonce is not passed.
		/* if ( ! isset( $_POST['advert_meta_box_nonce'] ) || */
		/* 	! wp_verify_nonce( $_POST['advert_meta_box_nonce'], 'nonce_action_advert_meta_box' ) */
		/* ) { */
		/* 	return $post_id; */
		/* } */

		// Bail if doing autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		update_post_meta( $post_id, $_POST['enable-advert'], true );
		update_post_meta( $post_id, $_POST['advert-type'], true );
		update_post_meta( $post_id, sanitize_text_field( $_POST['advertiser-name'] ), true );
	}

	/**
	 * Registers all block assets so that they can be enqueued through the block editor
	 * in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
	 * @throws Error Build Error message.
	 */
	function gutenberg_block() {
		$dir = __DIR__;

		$script_asset_path = "$dir/build/index.asset.php";
		if ( ! file_exists( $script_asset_path ) ) {
			throw new Error(
				'You need to run `npm start` or `npm run build` for the "create-block/advert-settings" block first.'
			);
		}

		$index_js     = 'build/index.js';
		$script_asset = require( $script_asset_path );

		wp_register_script(
			'create-block-advert-settings-block-editor',
			plugins_url( $index_js, __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);

		wp_set_script_translations( 'create-block-advert-settings-block-editor', 'advert-settings' );

		$editor_css = 'build/index.css';
		wp_register_style(
			'create-block-advert-settings-block-editor',
			plugins_url( $editor_css, __FILE__ ),
			[],
			filemtime( "$dir/$editor_css" )
		);

		$style_css = 'build/style-index.css';
		wp_register_style(
			'create-block-advert-settings-block',
			plugins_url( $style_css, __FILE__ ),
			[],
			filemtime( "$dir/$style_css" )
		);

		register_block_type(
			'create-block/advert-settings',
			[
				'editor_script' => 'create-block-advert-settings-block-editor',
				'editor_style'  => 'create-block-advert-settings-block-editor',
				'style'         => 'create-block-advert-settings-block',
			]
		);
	}
}

AdvertisementSettings::get_instance();
