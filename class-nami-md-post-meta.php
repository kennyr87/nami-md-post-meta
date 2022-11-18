<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since 1.0.0
 *
 * @package    NAMI_MD_Post_Meta
 * @subpackage NAMI_MD_Post_Meta
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    NAMI_MD_Post_Meta
 * @subpackage NAMI_MD_Post_Meta
 * @author     I G W T
 */
class NAMI_MD_Post_Meta {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $nami_md_post_meta    The string used to uniquely identify this plugin.
	 */
	protected $nami_md_post_meta;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Reference to admin class instance
	 *
	 * @access public
	 * @var    NAMI_MD_Post_Meta_Admin  $plugin_admin
	 */
	public $plugin_admin;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
  public function __construct() {
    if ( defined( 'NAMI_MD_POST_META_VERSION' ) ) {
        $this->version = NAMI_MD_POST_META_VERSION;
    } else {
        $this->version = '1.0.0';
    }
      $this->nami_md_post_meta = 'nami_md_post_meta';

      $this->load_dependencies();
      $this->define_admin_hooks();
  }

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - NAMI_MD_Post_Meta_Loader. Orchestrates the hooks of the plugin.
	 * - NAMI_MD_Post_Meta_i18n. Defines internationalization functionality.
	 * - NAMI_MD_Post_Meta_Admin. Defines all hooks for the admin area.
	 * - NAMI_MD_Post_Meta_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
  private function load_dependencies() {
      /**
       * The class responsible for defining all actions that occur in the admin area.
       */
      require_once plugin_dir_path( __FILE__ ) . 'admin/class-nami-md-post-meta-admin.php';
  }

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
  private function define_admin_hooks() {
      $this->plugin_admin = new NAMI_MD_Post_Meta_Admin( $this->get_nami_md_post_meta(), $this->get_version() );

      add_action( 'admin_enqueue_scripts', array( $this->plugin_admin, 'enqueue_styles' ) );
      add_action( 'admin_enqueue_scripts', array( $this->plugin_admin, 'enqueue_scripts' ) );
      add_action( 'admin_menu', array( $this->plugin_admin, 'admin_menu' ) );
  }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
  public function run() {
       add_action( 'admin_post_nami_md_post_meta_submit', array( $this, 'hook_admin_posts' ) );
      // add_action( 'wp_loaded', array($this, 'admin_page_hook'));
  }

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string    The name of the plugin.
	 */
  public function get_nami_md_post_meta() {
      return $this->nami_md_post_meta;
  }

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string    The version number of the plugin.
	 */
  public function get_version() {
       return $this->version;
  }

  public function hook_admin_posts() {
      check_admin_referer( 'nami_md_post_meta_submit' );

      // iterate through wpdm_posts and update yoast meta
      $wpdm_posts = $this->get_wpdmpro_posts();
    foreach ( $wpdm_posts as $post_id ) {
        $meta = $this->update_wpdmpro_post_meta( $post_id );
    }

      $url = admin_url( "tools.php?page={$this->plugin_admin->admin_slug}" );
      $this->redirect( $url );
  }

  private function log_errors() {
  }

  private function redirect( $url ) {
    if ( wp_redirect( $url ) ) {
        exit;
    }
  }

	/**
	 * Get all wpdmpro posts
	 *
	 * @return array
	 */
  private function get_wpdmpro_posts() {
      $args  = array(
          'numberposts'      => -1,
          'orderby'          => 'date',
          'order'            => 'DESC',
          'post_type'        => 'wpdmpro',
          'suppress_filters' => true,
          'fields'           => 'ids',
      );
      $posts = get_posts( $args );

      return $posts;
  }

	/**
	 * Update yoast meta data for wpdm post
	 *
	 * @param int $wpdm_post_id
	 *
	 * @return string[]|false Yoast meta data value
	 */
  private function update_wpdmpro_post_meta( int $wpdm_post_id ) {
      $meta_key = '_yoast_wpseo_primary_category';
      $cat_id   = get_the_category( $wpdm_post_id )[0]->term_id;

    if ( is_int( $cat_id ) ) {
        update_post_meta( $wpdm_post_id, $meta_key, $cat_id );
    }

      $meta = get_post_meta( $wpdm_post_id, $meta_key );

    if ( $cat_id != $meta[0] ) {
        error_log( "Invalid Yoast meta for WPDM post: ${wpdm_post_id}" );
    }

      return $meta;
  }
}
