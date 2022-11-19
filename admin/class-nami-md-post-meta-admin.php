<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    NAMI_MD_Post_Meta
 * @subpackage NAMI_MD_Post_Meta/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    NAMI_MD_Post_Meta
 * @subpackage NAMI_MD_Post_Meta/admin
 * @author     I G W T
 */
class NAMI_MD_Post_Meta_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $nami_md_post_meta    The ID of this plugin.
	 */
	private $nami_md_post_meta;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Slug of admin page.
	 *
	 * @access public
	 * @var string
	 */
	public $admin_slug;

	/**
	 * Hook for plugin screen
	 *
	 * @var string
	 */
	public $admin_page_hook;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $nami_md_post_meta       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $nami_md_post_meta, $version ) {

		$this->nami_md_post_meta = $nami_md_post_meta;
		$this->version           = $version;
		$this->admin_slug        = $this->nami_md_post_meta . '_admin';
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
		 * defined in NAMI_MD_Post_Meta_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The NAMI_MD_Post_Meta_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->nami_md_post_meta, plugin_dir_url( __FILE__ ) . 'css/nami-md-post-meta-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in NAMI_MD_Post_Meta_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The NAMI_MD_Post_Meta_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->nami_md_post_meta, plugin_dir_url( __FILE__ ) . 'js/nami-md-post-meta-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function admin_menu() {
		add_management_page(
			'Update WPDM Post Meta',
			'NAMI MD Post Meta',
			'activate_plugins',
			$this->admin_slug,
			array( $this, 'admin_menu_display' )
		);
	}

	public function admin_menu_display() {
		include( plugin_dir_path( __FILE__ ) . 'partials/nami-md-post-meta-admin-display.php' );
	}
}
