<?php
class Wordsysform {

	protected $loader;
	protected $plugin_name;	
	protected $version;
	
	public function __construct() {
		if ( defined( 'WORDSYSFORM_VERSION' ) ) {
			$this->version = WORDSYSFORM_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wordsysform';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wordsysform_Loader. Orchestrates the hooks of the plugin.
	 * - Wordsysform_i18n. Defines internationalization functionality.
	 * - Wordsysform_Admin. Defines all hooks for the admin area.
	 * - Wordsysform_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wordsysform-loader.php';

		/**
		 * The class responsible for defining internationalization functionality of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wordsysform-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wordsysform-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wordsysform-public.php';

		$this->loader = new Wordsysform_Loader();

	}
	
	private function set_locale() {
		$plugin_i18n = new Wordsysform_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}
	
	private function define_admin_hooks() {
		$plugin_admin = new Wordsysform_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}
	
	private function define_public_hooks() {
		$plugin_public = new Wordsysform_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}
	
	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}
	
	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
