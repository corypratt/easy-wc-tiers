<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://lonemill.com
 * @since      1.0.0
 *
 * @package    Easy_Wc_Tiers
 * @subpackage Easy_Wc_Tiers/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Wc_Tiers
 * @subpackage Easy_Wc_Tiers/public
 * @author     Cory Pratt <cory@lonemill.com>
 */
class Easy_Wc_Tiers_Public {

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
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Easy_Wc_Tiers_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	private $loader;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->include_files();

	}

	/**
	 * Include Admin specific files.
	 */
	public function include_files() {
		require_once plugin_dir_path( __DIR__ ) . '/public/class-easy-wc-discount-hooks.php';

		$pricing_hooks = new EASY_Wc_Public_Cart_Discount_Hooks();
		$pricing_hooks->init();
	}
	
	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-wc-tiers-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-wc-tiers-public.js', array( 'jquery' ), $this->version, false );

	}

}
