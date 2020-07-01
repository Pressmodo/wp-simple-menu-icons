<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Parse the YAML definition of font awesome into json file.
 *
 * @package   wp-simple-menu-icons
 * @author    Sematico LTD <hello@sematico.com>
 * @copyright 2020 Sematico LTD
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://directorystack.com
 */

namespace Pressmodo\MenuIcons;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Parser {

	/**
	 * Class instance.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Get the class instance
	 *
	 * @return static
	 */
	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self() ) : self::$instance;
	}

	/**
	 * Get things started.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Hook into WP.
	 *
	 * @return void
	 */
	public function init() {

		if ( ! defined( 'WP_DEBUG' ) || ! current_user_can( 'manage_options' ) || ( defined( 'WP_DEBUG' ) && WP_DEBUG !== true ) || ! defined( 'WP_SMI_DEBUG' ) ) {
			return;
		}

		add_action( 'admin_bar_menu', array( $this, 'add_parse_trigger_link' ), 100 );

	}

	/**
	 * Adds a link to trigger the fontawesome icons parser.
	 *
	 * @param object $admin_bar
	 * @return void
	 */
	public function add_parse_trigger_link( $admin_bar ) {
		$admin_bar->add_menu(
			array(
				'id'    => 'wpsmi-parse-fa',
				'title' => esc_html__( 'Parse FA Icons' ),
				'href'  => admin_url(),
				'meta'  => array(
					'title' => esc_html__( 'Parse FA Icons' ),
				),
			)
		);
	}

}
