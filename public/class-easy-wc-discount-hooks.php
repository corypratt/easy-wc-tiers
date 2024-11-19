<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EASY_Wc_Public_Cart_Discount_Hooks {

	/**
	 * An array of possible cart discounts.
	 *
	 * @var array
	 */
	protected $discounts;
	protected $product_pricing;
	protected $loader;

	public $applied_pricings = false;

	// public function __construct() {
	// 	$this->loader = $loader;
	// }
	/**
	 * Init the public WC hooks
	 */
	public function init() {
		// add_action( 'woocommerce_cart_item_price', array( __CLASS__, 'enable_price_and_badge_hooks' ) );
		// add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'enable_price_and_badge_hooks' ) );
		add_action( 'woocommerce_before_calculate_totals', array( &$this, 'reset_applied_pricings' ) );
		add_action( 'woocommerce_before_calculate_totals', array( &$this, 'apply_pricings' ), 9998 );
		add_action( 'woocommerce_add_to_cart', array( &$this, 'reset_applied_pricings' ) );
		add_action( 'woocommerce_cart_item_removed', array( &$this, 'reset_applied_pricings' ) );
		add_action( 'woocommerce_checkout_update_order_review', array( &$this, 'reset_applied_pricings' ) );
		add_action( 'woocommerce_after_cart_item_quantity_update', array( &$this, 'reset_applied_pricings' ) );

		add_filter( 'woocommerce_cart_item_price', array( &$this, 'cart_item_price' ), 10, 3 );

		add_action( 'woocommerce_before_mini_cart_contents', array( &$this, 'calculate_cart_totals', 10 ) );

	}

	public function calculate_cart_totals() {
		if ( ! WC()->cart || WC()->cart->is_empty() ) {
			return;
		}

		// do_action( 'wccs_before_calculate_cart_totals' );
		WC()->cart->calculate_totals();
		// do_action( 'wccs_after_calculate_cart_totals' );
	}

	/**
	 * Logic to add discount.
	 */
	public function apply_pricings( $cart = null ) {
		$cart = $cart && is_a( $cart, 'WC_Cart' ) ? $cart : WC()->cart;
		if ( $cart->is_empty() ) {
			return;
		}
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}

		$cart_contents = $cart->get_cart();
		foreach ( $cart_contents as $cart_item_key => $cart_item ) {
			$product       = $cart_item['data'];
			$pricing_tiers = get_post_meta( $cart_item['data']->get_id(), 'ewt_product_tiers', true );
			if ( ! $pricing_tiers || empty( $pricing_tiers['tier_position'] ) ) {
				continue;
			}
			$item_price = $cart_item['data']->get_regular_price();

			$discount_type   = '';
			$discount_amount = 0;

			foreach ( $pricing_tiers['tier_position'] as $tier_number ) {
				// var_dump( $pricing_tiers['max_qty'][ $tier_number ] );

				if ( ( $cart_item['quantity'] >= $pricing_tiers['min_qty'][ $tier_number ] && $cart_item['quantity'] <= $pricing_tiers['max_qty'][ $tier_number ] ) || ( $cart_item['quantity'] >= $pricing_tiers['min_qty'][ $tier_number ] && '-1' === $pricing_tiers['max_qty'][ $tier_number ] ) ) {
					$discount_type   = $pricing_tiers['ewt_discount_type'][ $tier_number ];
					$discount_amount = $pricing_tiers['discount'][ $tier_number ];
					break;
				}
			}
			if ( $discount_type && $discount_amount ) {

				if ( 'percentage' === $discount_type ) {
					$item_discounted_price = $item_price * ( 100 - $discount_amount ) / 100;
				} else {
					$item_discounted_price = $item_price - $discount_amount;
				}

				$cart->cart_contents[ $cart_item_key ]['_ewt_main_price']              = $cart_item['data']->get_price( 'edit' );
				$cart->cart_contents[ $cart_item_key ]['_ewt_main_display_price']      = self::get_cart_item_main_display_price( $cart_item, $product );
				$cart->cart_contents[ $cart_item_key ]['_ewt_before_discounted_price'] = self::get_cart_item_before_discounted_price( $cart_item, $product );
				$cart->cart_contents[ $cart_item_key ]['_ewt_discounted_price']        = wc_format_decimal( $item_discounted_price );

				if ( $item_discounted_price < $item_price ) {
					$cart_item['data']->set_sale_price( $item_discounted_price );

				}

				$cart_item['data']->set_price( $item_discounted_price );
			}
		}
		$this->applied_pricings = true;
	}


	public static function cart_item_price( $price, $cart_item, $cart_item_key ) {
		if ( ! isset( $cart_item['_ewt_discounted_price'] ) || ! isset( $cart_item['_ewt_before_discounted_price'] ) || ! isset( $cart_item['_ewt_main_price'] ) ) {
			return $price;
		}

		if ( ! apply_filters( 'wccs_change_cart_item_price', true ) ) {
			return $price;
		}

		if ( isset( $cart_item['_ewt_main_sale_price'] ) && $cart_item['_ewt_main_sale_price'] == $cart_item['_ewt_main_price'] ) {
			$before_discounted_price = wc_get_price_to_display( $cart_item, array( 'qty' => 1 ) );
			$main_price              = (float) $cart_item['data']->get_regular_price();
		} else {
			$before_discounted_price = $cart_item['_ewt_before_discounted_price'];
			$main_price              = (float) $cart_item['_ewt_main_price'];
		}

		if ( $main_price > (float) $cart_item['_ewt_discounted_price'] ) {
			$content = '<del>' . $before_discounted_price . '</del> <ins>' . $price . '</ins>';
			return apply_filters( 'wccs_cart_item_price', $content, $price, $cart_item, $cart_item_key );
		}

		return apply_filters( 'wccs_cart_item_price', $price, $price, $cart_item, $cart_item_key );
	}

	public function get_price_html( $price, $product ) {
		if ( empty( $price ) ) {
			return $price;
		}

		$product_pricing = $this->get_product_pricing( $product );
		return $product_pricing->get_price_html( $price );
	}

	public function remove_pricings( $cart = null ) {
		$cart = $cart && is_a( $cart, 'WC_Cart' ) ? $cart : WC()->cart;
		if ( $cart->is_empty() ) {
			return;
		}

		$cart_contents = $cart->get_cart();
		foreach ( $cart_contents as $cart_item_key => $cart_item ) {
			if ( isset( $cart_item['_wccs_main_price'] ) ) {
				$cart_item['data']->set_price( $cart_item['_wccs_main_price'] );
				unset( $cart->cart_contents[ $cart_item_key ]['_wccs_main_price'] );
				unset( $cart->cart_contents[ $cart_item_key ]['_wccs_main_display_price'] );
				unset( $cart->cart_contents[ $cart_item_key ]['_wccs_before_discounted_price'] );
				unset( $cart->cart_contents[ $cart_item_key ]['_wccs_discounted_price'] );
				unset( $cart->cart_contents[ $cart_item_key ]['_wccs_prices'] );
				unset( $cart->cart_contents[ $cart_item_key ]['_wccs_prices_main'] );
			}

			if ( isset( $cart_item['_wccs_main_sale_price'] ) ) {
				$cart_item['data']->set_sale_price( $cart_item['_wccs_main_sale_price'] );
				unset( $cart->cart_contents[ $cart_item_key ]['_wccs_main_sale_price'] );
			}

			// It is a cart item product so do not override its price.
			// if ( isset( $cart->cart_contents[ $cart_item_key ]['data'] ) ) {
			// 	WCCS()->custom_props->set_prop( $cart->cart_contents[ $cart_item_key ]['data'], 'wccs_is_cart_item', true );
			// }
		}
	}

	/**
	 * Reset applied pricings.
	 *
	 * @since  2.8.0
	 *
	 * @return void
	 */
	public function reset_applied_pricings() {
		if ( $this->applied_pricings ) {
			$this->applied_pricings = false;
			// $this->pricing->reset_cache();
			// Enable remove pricing hook after reset applied pricings.
			if ( ! has_action( 'woocommerce_before_calculate_totals', array( &$this, 'remove_pricings' ) ) ) {
				add_action( 'woocommerce_before_calculate_totals', array( &$this, 'remove_pricings' ) );
			}
		}
	}

	protected static function get_cart_item_main_display_price( $cart_item, $product ) {
		$args = array(
			'qty'   => 1,
			'price' => $product->get_price(),
		);

		$price_to_display = (float) wc_get_price_to_display( $cart_item['data'], array( 'price' => $cart_item['data']->get_price( 'edit' ) ) );
		return $price_to_display;
	}

	protected static function get_cart_item_before_discounted_price( $cart_item, $product ) {
		$args = array( 'qty' => 1 );
		if ( WC()->cart->display_prices_including_tax() ) {
			$product_price = wc_get_price_including_tax( $product, $args );
		} else {
			$product_price = wc_get_price_excluding_tax( $product, $args );
		}
		return wc_price( $product_price );
	}
}
