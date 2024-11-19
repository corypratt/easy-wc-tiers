<?php
/**
 * Easy WC Ajax EASY_WC_AJAX. AJAX Event Handlers.
 *
 * @class   EASY_WC_AJAX
 * @package WooCommerce\Classes
 */

defined( 'ABSPATH' ) || exit;

/**
 * EASY_WC_Ajax class.
 */
class EASY_WC_AJAX {
	/**
	 * Hook in ajax handlers.
	 */
	public static function init() {
		// add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		// add_action( 'template_redirect', array( __CLASS__, 'do_wc_ajax' ), 0 );
		self::add_ajax_events();
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public static function add_ajax_events() {
		add_action( 'wp_ajax_ewt_add_tier', array( __CLASS__, 'add_tier' ) );
		add_action( 'wp_ajax_ewt_save_tiers', array( __CLASS__, 'save_tiers' ) );

	}
	
	/**
	 * Add an attribute row.
	*/
	public static function add_tier() {
		ob_start();

		check_ajax_referer( 'add_tier_nonce', 'security' );

		if ( ! current_user_can( 'edit_products' ) || ! isset( $_POST['i'] ) ) {
			wp_die( -1 );
		}

		$i    = absint( $_POST['i'] );
		$tier = new WC_Product_Tier();
		$tier->set_position( $i );
		/* phpcs:disable WooCommerce.Commenting.CommentHooks.MissingHookComment */
		// $tier->set_visible( apply_filters( 'woocommerce_attribute_default_visibility', 1 ) );

		include __DIR__ . '/partials/html-product-tier.php';
		wp_die();
	}

	/**
	 * Save tiers
	 */
	public static function save_tiers() {
		check_ajax_referer( 'save_tier_nonce', 'security' );

		if ( ! current_user_can( 'edit_products' ) || ! isset( $_POST['data'], $_POST['post_id'] ) ) {
			wp_die( -1 );
		}

		$response = array();

		try {
			parse_str( wp_unslash( $_POST['data'] ), $data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			update_post_meta( wp_unslash( $_POST['post_id'] ), 'ewt_product_tiers', $data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			ob_start();
			$i = -1;
			if ( ! empty( $data['tier_position'] ) ) {
				foreach ( $data['tier_position'] as $tier_position ) {
					++$i;
					$tier = new WC_Product_Tier();
					$tier->set_position( $tier_position );
					$tier->set_min( $data['min_qty'][ $tier_position ] );
					$tier->set_max( $data['max_qty'][ $tier_position ] );
					$tier->set_discount( $data['discount'][ $tier_position ] );
					$tier->set_discount_type( $data['ewt_discount_type'][ $tier_position ] );
					$tier->get_min( $data['min_qty'][ $tier_position ] );
					$tier->get_max( $data['max_qty'][ $tier_position ] );
					$tier->get_discount( $data['discount'][ $tier_position ] );
					$tier->get_discount_type( $data['ewt_discount_type'][ $tier_position ] );
					include __DIR__ . '/partials/html-product-tier.php';
				}
			}
			$response['html'] = ob_get_clean();
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'error' => $e->getMessage() ) );
		}

		// wp_send_json_success must be outside the try block not to break phpunit tests.
		wp_send_json_success( $response );
	}
}

EASY_WC_AJAX::init();
