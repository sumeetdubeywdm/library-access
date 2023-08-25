<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Libraryaccess
 * @subpackage Libraryaccess/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Libraryaccess
 * @subpackage Libraryaccess/admin
 * @author     Sumeet Dubey <sumeet.dubey@wisdmlabs.com>
 */
class Libraryaccess_Admin
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_init', array($this, 'add_library_checkbox'));
		add_action('woocommerce_process_product_meta', array($this, 'save_checkbox_product_value'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Libraryaccess_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Libraryaccess_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/libraryaccess-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Libraryaccess_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Libraryaccess_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/libraryaccess-admin.js', array('jquery'), $this->version, false);
	}

	public function add_library_checkbox()
	{
		add_filter("product_type_options", function ($library_product) {

			$library_product["library"] = [
				"id"            => "_library",
				"wrapper_class" => "show_if_simple",
				"label"         => "library",
				"description"   => "Library Access - Give Access to all Courses",
				"default"       => "no",
			];

			return $library_product;
		});
	}

	public function save_checkbox_product_value($product_id)
	{

		$is_library = isset($_POST['_library']) ? 'yes' : 'no';
		update_metadata('post', $product_id, '_library', $is_library);
	}
}
