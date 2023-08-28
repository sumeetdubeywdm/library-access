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
		add_action('admin_init', array($this, 'add_associate_courses_with_products'), 10, 1);
		add_action('admin_init', array($this, 'remove_associate_courses_with_products'), 10, 1);
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


	// add_library_checkbox function is used to add the checkbox in the product data.
	public function add_library_checkbox()
	{
		add_filter("product_type_options", array($this, "add_library_checkbox_array"));
	}

	// add_library_checkbox_array function is callback function.
	public function add_library_checkbox_array($library_product)
	{

		// Creating array to add option of library.
		$library_product["library"] = [
			"id"            => '_library',
			"wrapper_class" => 'show_if_simple',
			"label"         => __('library', 'woocommerce'),
			"description"   => __('Library Access - Give Access to all Courses', 'woocommerce'),
			"default"       => 'no',
		];

		return $library_product;
	}

	//save_checkbox_product_value function is used to save the data in database.

	public function save_checkbox_product_value($product_id)
	{
		$product = wc_get_product($product_id);

		$is_library = isset($_POST['_library']) ? 'yes' : 'no';
		$product->update_meta_data('_library', $is_library);
		$product->save_meta_data();
	}


	// Associating the existing courses with the product which have library option enabled.
	public function add_associate_courses_with_products()
	{
		// Getting all the publish courses.
		$args = array(
			'post_type' => 'sfwd-courses',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		);
		$courses = get_posts($args);

		// Getting all the the product which have library option enabled.
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => '_library',
					'value' => 'yes',
				),
			),
		);
		$products = get_posts($args);

		foreach ($products as $product) {
			$product_id = $product->ID;

			// Now creating an array and storing all the course id.
			foreach ($courses as $course) {
				$course_ids[] = $course->ID;
			}

			$product_object = wc_get_product($product_id);
			$product_object->update_meta_data('_related_course', $course_ids);
			$product_object->save_meta_data();
		}
	}

	public function remove_associate_courses_with_products()
	{
		// Getting all the the products.
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
		);

		$products = get_posts($args);

		foreach ($products as $product) {

			$product_id = $product->ID;
			$product_object = wc_get_product($product_id);

			$previous_library_value = $product_object->get_meta('_previous_library', true);
			$current_library_value = $product_object->get_meta('_library', true);

			/* Now comparing the previous_library with current library value if the previous value 
			 is yes and current value is not that means the library option is disabled then remove all 
			 the assoicated coures.*/
			if ($previous_library_value === 'yes' && $current_library_value === 'no') {
				$product_object->delete_meta_data('_related_course');
				$product_object->save_meta_data();
			}
			
			$product_object->update_meta_data('_previous_library', $current_library_value);
			$product_object->save_meta_data();
		}
	}
}
