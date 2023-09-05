<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wisdmlabs.com
 * @since      1.0.0
 *
 * @package    Libraryaccess
 * @subpackage Libraryaccess/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Libraryaccess
 * @subpackage Libraryaccess/public
 * @author     Sumeet Dubey <sumeet.dubey@wisdmlabs.com>
 */
class Libraryaccess_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode('learndash_loading_message', array($this, 'learndash_loading_message_shortcode'));
		add_action('wp_ajax_get_course_count', array($this, 'get_course_count'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/libraryaccess-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/libraryaccess-public.js', array('jquery'), $this->version, false);

		$localized_data = array(
			'ajax_url' => admin_url('admin-ajax.php'),
		);
		wp_localize_script($this->plugin_name, 'libraryaccess_public_vars', $localized_data);
	}

	function learndash_loading_message_shortcode()
	{
		ob_start();

		if (has_shortcode(get_post()->post_content, 'ld_profile')) {
			echo '<div id="learndash-loading" class="ld-loading-style" style="display:none">
        			<strong>Please wait.... Your Courses is adding.</strong>
    			</div>';
		}
		return ob_get_clean();
	}


	function get_course_count() {
		// Fetching products with library access
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'post_password',
					'compare' => 'NOT EXISTS',
				),
			),
		);
	
		$library_access_products = get_posts($args);
	
		// Check if the user has purchased any of the specified products using WooCommerce functions.
		if (function_exists('wc_customer_bought_product')) {
			$user_id = get_current_user_id();
			$course_count =0;
	
			foreach ($library_access_products as $product) {
				$product_id = $product->ID;
				
				if (wc_customer_bought_product($user_id, $user_id, $product_id)) {
					// Check if the product is a library_access product.
					$is_library_access_product = get_post_meta($product_id, '_library', true);

					if ($is_library_access_product === 'yes') {
						$related_courses = get_post_meta($product_id, '_related_course', true);
						$course_count = count($related_courses);
					}else{
						$enrolled_users_count =$this->get_enrolled_users_count();	
						$course_count = $enrolled_users_count;
					}
				}
			}
	
			if ($course_count) {
				echo $course_count;
			}else {
				$enrolled_users_count =$this->get_enrolled_users_count();	
				echo $enrolled_users_count;
		}
	
		
	}
	die();
	
}

function get_enrolled_users_count() {
	$user_id = get_current_user_id();
	$enrolled_courses = learndash_user_get_enrolled_courses($user_id);
	$enrolled_users_count = count($enrolled_courses);
	
	return $enrolled_users_count;
}

}