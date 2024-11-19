<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://lonemill.com
 * @since      1.0.0
 *
 * @package    Easy_Wc_Tiers
 * @subpackage Easy_Wc_Tiers/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Wc_Tiers
 * @subpackage Easy_Wc_Tiers/admin
 * @author     Cory Pratt <cory@lonemill.com>
 */
class Easy_Wc_Tiers_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->include_files();
	}

	/**
	 * Include Admin specific files.
	 */
	public function include_files() {
		if ( is_admin() ) {
			require_once plugin_dir_path( __DIR__ ) . '/admin/class-easy-wc-tier.php';
			require_once plugin_dir_path( __DIR__ ) . '/admin/class-easy-wc-tiers-product.php';
			require_once plugin_dir_path( __DIR__ ) . '/admin/class-easy-wc-ajax.php';
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Easy_Wc_Tiers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Easy_Wc_Tiers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-wc-tiers-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Easy_Wc_Tiers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Easy_Wc_Tiers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( in_array( $screen_id, array( 'product', 'edit-product' ) ) ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-wc-tiers-admin.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'wc-admin-product-meta-boxes' ), $this->version );
			wp_localize_script(
				$this->plugin_name,
				'easy_wc_tiers',
				array(
					'ajax_url'        => admin_url( 'admin-ajax.php' ),
					'add_tier_nonce'  => wp_create_nonce( 'add_tier_nonce' ),
					'save_tier_nonce' => wp_create_nonce( 'save_tier_nonce' ),
				)
			);
		}
	}

}
