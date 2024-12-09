<?php
/**
 * Represents a product tier
 *
 * Attributes can be global (taxonomy based) or local to the product itself.
 * Uses ArrayAccess to be BW compatible with previous ways of reading attributes.
 *
 * @package WooCommerce\Classes
 * @version 3.0.0
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Product attribute class.
 */
class WC_Product_Tier {

	/**
	 * Data array.
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $data = array(
		'id'       => 0,
		'position' => 0,
		'min'      => null,
		'max'      => null,
		'discount' => null,
		'type'     => '',
	);


	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set ID (this is the attribute ID).
	 *
	 * @param int $value Attribute ID.
	 */
	public function set_id( $value ) {
		$this->data['id'] = absint( $value );
	}

	/**
	 * Set position.
	 *
	 * @param int $value Attribute position.
	 */
	public function set_position( $value ) {
		$this->data['tier_position'] = absint( $value );
	}

	/**
	 * Set position.
	 *
	 * @param int $value Attribute position.
	 */
	public function set_min( $value ) {
		$this->data['min'] = absint( $value );
	}

	/**
	 * Set position.
	 *
	 * @param int $value Attribute position.
	 */
	public function set_max( $value ) {
		// $value = $value <= 0 ? $value : $value;

		$this->data['max'] = $value;
	}

	/**
	 * Set position.
	 *
	 * @param int $value Attribute position.
	 */
	public function set_discount_type( $value ) {
		$this->data['type'] = esc_html( $value );
	}

	/**
	 * Set position.
	 *
	 * @param int $value Attribute position.
	 */
	public function set_discount( $value ) {
		$this->data['discount'] = absint( $value );
	}


	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the ID.
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->data['id'];
	}

	/**
	 * Get position.
	 *
	 * @return int
	 */
	public function get_position() {
		return $this->data['tier_position'];
	}

	/**
	 * Get min.
	 *
	 * @return int
	 */
	public function get_min() {
		return $this->data['min'];
	}

	/**
	 * Get max.
	 *
	 * @return int
	 */
	public function get_max() {
		return $this->data['max'];
	}

	/**
	 * Get type.
	 *
	 * @return int
	 */
	public function get_discount_type() {
		return $this->data['type'];
	}

	/**
	 * Get discount.
	 *
	 * @return int
	 */
	public function get_discount() {
		return $this->data['discount'];
	}
}