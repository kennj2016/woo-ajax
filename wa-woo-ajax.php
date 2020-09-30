<?php
/**
 * Plugin Name: WA WooCommerce Ajax Filter
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * The main plugin class
 */
final class TA_WC_Variation_Swatches {
	/**
	 * The single instance of the class
	 *
	 * @var TA_WC_Variation_Swatches
	 */
	protected static $instance = null;

	/**
	 * Extra attribute types
	 *
	 * @var array
	 */
	public $types = array();

	/**
	 * Main instance
	 *
	 * @return TA_WC_Variation_Swatches
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->types = array(
			'color' => esc_html__( 'Color', 'wa' ),
			'image' => esc_html__( 'Image', 'wa' ),
			'label' => esc_html__( 'Label', '' ),
		);

		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once 'includes/class-admin.php';
		require_once 'includes/class-frontend.php';
	}

	/**
	 * Initialize hooks
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );

		add_filter( 'product_attributes_type_selector', array( $this, 'add_attribute_types' ) );

		if ( is_admin() ) {
			add_action( 'init', array( 'TA_WC_Variation_Swatches_Admin', 'instance' ) );
		} else {
			add_action( 'init', array( 'TA_WC_Variation_Swatches_Frontend', 'instance' ) );
		}
	}

	/**
	 * Load plugin text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( '', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Add extra attribute types
	 * Add color, image and label type
	 *
	 * @param array $types
	 *
	 * @return array
	 */
	public function add_attribute_types( $types ) {
		$types = array_merge( $types, $this->types );

		return $types;
	}

	/**
	 * Get attribute's properties
	 *
	 * @param string $taxonomy
	 *
	 * @return object
	 */
	public function get_tax_attribute( $taxonomy ) {
		global $wpdb;

		$attr = substr( $taxonomy, 3 );
		$attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attr'" );

		return $attr;
	}

	/**
	 * Instance of admin
	 *
	 * @return TA_WC_Variation_Swatches_Admin
	 */
	public function admin() {
		return TA_WC_Variation_Swatches_Admin::instance();
	}

	/**
	 * Instance of frontend
	 *
	 * @return TA_WC_Variation_Swatches_Frontend
	 */
	public function frontend() {
		return TA_WC_Variation_Swatches_Frontend::instance();
	}
}

/**
 * Main instance of plugin
 *
 * @return TA_WC_Variation_Swatches
 */
function TA_WCVS() {
	return TA_WC_Variation_Swatches::instance();
}

/**
 * Display notice in case of WooCommerce plugin is not activated
 */
function ta_wc_variation_swatches_wc_notice() {
	?>

	<div class="error">
		<p><?php esc_html_e( 'Soo Product Attribute Swatches is enabled but not effective. It requires WooCommerce in order to work.', 'wa' ); ?></p>
	</div>

	<?php
}

/**
 * Construct plugin when plugins loaded in order to make sure WooCommerce API is fully loaded
 * Check if WooCommerce is not activated then show an admin notice
 * or create the main instance of plugin
 */
function ta_wc_variation_swatches_constructor() {
	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'ta_wc_variation_swatches_wc_notice' );
	} else {
		TA_WCVS();
	}
}

add_action( 'plugins_loaded', 'ta_wc_variation_swatches_constructor' );

include_once __DIR__.'/wa.php';

