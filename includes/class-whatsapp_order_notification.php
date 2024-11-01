<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.whatso.net/
 * @since      1.0.0
 *
 * @package    Whatsapp_order_notification
 * @subpackage Whatsapp_order_notification/includes
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
 * @package    Whatsapp_order_notification
 * @subpackage Whatsapp_order_notification/includes
 * @author     whatso <hi@whatso.net>
 */
class Whatsapp_order_notification {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Whatsapp_order_notification_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WHATSAPP_ORDER_NOTIFICATION_VERSION' ) ) {
			$this->version = WHATSAPP_ORDER_NOTIFICATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'whatsapp_order_notification';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Whatsapp_order_notification_Loader. Orchestrates the hooks of the plugin.
	 * - Whatsapp_order_notification_i18n. Defines internationalization functionality.
	 * - Whatsapp_order_notification_Admin. Defines all hooks for the admin area.
	 * - Whatsapp_order_notification_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-whatsapp_order_notification-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-whatsapp_order_notification-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-whatsapp_order_notification-admin.php';

	
		$this->loader = new Whatsapp_order_notification_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Whatsapp_order_notification_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Whatsapp_order_notification_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Whatsapp_order_notification_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		//add action for menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'whatsapp_order_notification_menu' );
		//add action for user credentials
		$this->loader->add_action( 'whatso_user_credentials', $plugin_admin, 'whatso_get_user_credentials' );
		//add action for order
		add_action('woocommerce_checkout_order_processed', array($this, 'order_processed'), 99, 4);
		 add_filter( 'admin_footer_text', array($this, 'updatefooteradmin' ));



    }


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Whatsapp_order_notification_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	//to get user data
	public static function whatso_get_user_credentials($whatso_emailid)
	{
		$data_decoded = array("emailId" => $whatso_emailid, "forWhichFunctionality" => "api");

		$data = json_encode($data_decoded);

		$url = "https://webapi.whatso.net/api/UnAuthorized/get-api-credentials";

		$response = wp_remote_post($url, array(
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json; charset=utf-8', 'WPRequest' => 'abach34h4h2h11h3h'
			),
			'body' => $data
		));
		if (is_array($response) and isset($response['body'])) {

			$response_obj = json_decode($response['body']);
			if (is_object($response_obj) && isset($response_obj->message) && $response_obj->message === 'Success') {
				return $response_obj->message;
			} else {
				return false;
			}
		}
	}

	public function order_processed($order_id, $posted_data, $order)
	{
		$execute_flag = true;
		global $wpdb;
		$order_table = $wpdb->prefix . 'whatso_order_notification_details';
		if (is_a($order, 'WC_Order_Refund')) {
			$execute_flag = false;
		}

		if ($order === false) {
			$execute_flag = false;
		}

		if ($execute_flag) {

			if (!empty(get_option('order_notification'))) {
				$data = get_option('order_notification');
				$data = json_decode($data);
				$whatso_username = $data->whatso_username;
				$whatso_password = $data->whatso_password;
				$admin_mobileno = $data->admin_mobileno;
				$admin_message = $data->admin_message;
				$customer_notification = $data->customer_notification;
				$customer_message = $data->customer_message;
				$from_number = $data->from_number;
			


				$store_name = get_bloginfo('name');
				$billing_email = $order->get_billing_email();
				$order_currency = $order->get_currency();
				$order_amount = $order->get_total();
				$order_date = $order->get_date_created();
				$order_customer = $order->get_billing_first_name();
				$items = $order->get_items();
				$products_array = array();

				
				foreach ($items as $item) {
					
					$product = $item->get_product();
					$product_name = '';
					if (!is_object($product)) {
						$product_name = $item->get_name();
					} else {

						$product_name = $product->get_title();
					}
					array_push($products_array, $product_name);
				}

				$countryCode = $order->get_billing_country();
				if (empty($countryCode)) {
					$countryCode = $order->get_shipping_country();
				}
				$city = $order->get_billing_city();
				if (empty($city)) {
					$city = $order->get_shipping_city();
				}
				$stateCode = $order->get_billing_state();
				if (empty($stateCode)) {
					$stateCode = $order->get_shipping_state();
				}

				$customernumber = $order->get_billing_phone();

				$exploded_names = implode(",", $products_array);

				$order_date_formatted = $order_date->date("d M Y H:i");

				
				$admin_message = str_replace('{customername}', $order_customer, $admin_message);
				$admin_message = str_replace('{storename}', $store_name, $admin_message);
				$admin_message = str_replace('{orderdate}', $order_date_formatted, $admin_message);
				$admin_message = str_replace('{productname}', $exploded_names, $admin_message);
				$admin_message = str_replace('{amountwithcurrency}', $order_currency . ' ' . $order_amount, $admin_message);
				$admin_message = str_replace('{customeremail}', $billing_email, $admin_message);
				$admin_message = str_replace('{billingcity}', $city, $admin_message);
				$admin_message = str_replace('{billingstate}', $stateCode, $admin_message);
				$admin_message = str_replace('{billingcountry}', $countryCode, $admin_message);
				$customernumber = preg_replace('/[^0-9]/', '', $customernumber);
				$admin_message = str_replace('{customernumber}', $customernumber, $admin_message);
				$admin_message = preg_replace("/\r\n/", "<br>", $admin_message);
				

				$data_decoded = array(
					"Username" => $whatso_username, "Password" => $whatso_password, "MessageText" => $admin_message, "MobileNumbers" => $admin_mobileno, "ScheduleDate" => '', "FromNumber" => $from_number,
					"Channel" => '1'
				);

				$data = json_encode($data_decoded);

				$url = "https://api.whatso.net/api/v2/SendMessage";

				$response = wp_remote_post($url, array(
					'method' => 'POST',
					'headers' => array(
						'Content-Type' => 'application/json; charset=utf-8', 'WPRequest' => 'abach34h4h2h11h3h'
					),
					'body' => $data
				));
				if (is_array($response) and isset($response['body'])) {

					$response_obj = json_decode($response['body']);
					if (is_object($response_obj)) {
						//code to update whatso_order_notification_details
						$insert_array = array(
							'user_type' => 'admin',
							'message_api_request' => $data,
							'message_api_response' =>  wp_json_encode($response_obj) ,
							);
		
						$wpdb->insert($order_table, $insert_array);
					}
				}
				$customernumber = preg_replace('/[^0-9]/', '', $customernumber);
				$country_code = $countryCode;

				if ($customernumber != "") {

					if ($country_code ==  "UK") //united kingdom
					{
						$customernumber = $this->mobile_number_validation(10, 44, $customernumber);
					} elseif ($country_code ==  "AT") //Australia
					{
						$customernumber = $this->mobile_number_validation(10, 61, $customernumber);
					} elseif ($country_code ==  "US") //United Status
					{
						$customernumber = $this->mobile_number_validation(10, 1, $customernumber);
					} elseif ($country_code ==  "RU") //Russia
					{
						$customernumber = $this->mobile_number_validation(10, 7, $customernumber);
					} elseif ($country_code ==  "IT") //Italy
					{
						$customernumber = $this->mobile_number_validation(10, 39, $customernumber);
					} elseif ($country_code ==  "IN") //India
					{
						$customernumber = $this->mobile_number_validation(10, 91, $customernumber);
					} elseif ($country_code ==  "IR") //Iran
					{
						$customernumber = $this->mobile_number_validation(10, 98, $customernumber);
					} elseif ($country_code ==  "CA") //Canada
					{
						$customernumber = $this->mobile_number_validation(10, 1, $customernumber);
					} elseif ($country_code ==  "ZA") //South Africa
					{
						$customernumber = $this->mobile_number_validation(9, 27, $customernumber);
					} elseif ($country_code ==  "BR") //Brazil
					{
						$customernumber = $this->mobile_number_validation(11, 55, $customernumber);
					} elseif ($country_code ==  "CN") //China
					{
						$customernumber = $this->mobile_number_validation(11, 86, $customernumber);
					} elseif ($country_code ==  "ID") //Indonesia
					{
						$customernumber = $this->mobile_number_validation(10, 62, $customernumber);
					} elseif ($country_code ==  "PK") //Pakistan
					{
						$customernumber = $this->mobile_number_validation(10, 92, $customernumber);
					} elseif ($country_code ==  "NG") //Nigeria
					{
						$customernumber = $this->mobile_number_validation(8, 234, $customernumber);
					} elseif ($country_code ==  "BD") //Bangladesh
					{
						$customernumber = $this->mobile_number_validation(10, 880, $customernumber);
					} elseif ($country_code ==  "MX") //Mexico
					{
						$customernumber = $this->mobile_number_validation(10, 52, $customernumber);
					} elseif ($country_code ==  "JP") //japan
					{
						$customernumber = $this->mobile_number_validation(10, 81, $customernumber);
					} elseif ($country_code ==  "ET") //Ethiopia
					{
						$customernumber = $this->mobile_number_validation(9, 251, $customernumber);
					} elseif ($country_code ==  "PH") //Phillipines
					{
						$customernumber = $this->mobile_number_validation(10, 63, $customernumber);
					} elseif ($country_code ==  "EG") //Egypt
					{
						$customernumber = $this->mobile_number_validation(10, 20, $customernumber);
					} elseif ($country_code ==  "VN") //Vietnam
					{
						$customernumber = $this->mobile_number_validation(9, 84, $customernumber);
					} elseif ($country_code ==  "DE") //Germany
					{
						$customernumber = $this->mobile_number_validation(10, 49, $customernumber);
					} elseif ($country_code ==  "TR") //Turkey
					{
						$customernumber = $this->mobile_number_validation(11, 90, $customernumber);
					} elseif ($country_code ==  "TH") //Thailand
					{
						$customernumber = $this->mobile_number_validation(9, 66, $customernumber);
					} elseif ($country_code ==  "FR") //France
					{
						$customernumber = $this->mobile_number_validation(9, 33, $customernumber);
					} elseif ($country_code ==  "IT") //Italy
					{
						$customernumber = $this->mobile_number_validation(13, 39, $customernumber);
					} elseif ($country_code ==  "TZ") //Tanzania
					{
						$customernumber = $this->mobile_number_validation(9, 255, $customernumber);
					} elseif ($country_code ==  "ES") //Spain
					{
						$customernumber = $this->mobile_number_validation(9, 34, $customernumber);
					} elseif ($country_code ==  "MM") //Myanmar
					{
						$customernumber = $this->mobile_number_validation(10, 95, $customernumber);
					} elseif ($country_code ==  "KE") //kenya
					{
						$customernumber = $this->mobile_number_validation(10, 254, $customernumber);
					}

					elseif ($country_code ==  "UG") //Uganda
					{
						$customernumber = $this->mobile_number_validation(9, 256, $customernumber);
					} elseif ($country_code ==  "AR") //Argentina
					{
						$customernumber = $this->mobile_number_validation(9, 54, $customernumber);
					} elseif ($country_code ==  "DZ") //Algeria
					{
						$customernumber = $this->mobile_number_validation(9, 213, $customernumber);
					} elseif ($country_code ==  "SD") //Sudan
					{
						$customernumber = $this->mobile_number_validation(9, 249, $customernumber);
					}
                    elseif ($country_code ==  "AF") //Afghanistan
					{
						$customernumber = $this->mobile_number_validation(9, 93, $customernumber);
					} elseif ($country_code ==  "PL") //Poland
					{
						$customernumber = $this->mobile_number_validation(9, 48, $customernumber);
					} elseif ($country_code ==  "SA") //Saudi Arabia
					{
						$customernumber = $this->mobile_number_validation(9, 966, $customernumber);
					} elseif ($country_code ==  "PE") //Peru
					{
						$customernumber = $this->mobile_number_validation(9, 51, $customernumber);
					} elseif ($country_code ==  "MY") //Malaysia
					{
						$customernumber = $this->mobile_number_validation(7, 60, $customernumber);
					} elseif ($country_code ==  "MZ") //Mozambique
					{
						$customernumber = $this->mobile_number_validation(12, 258, $customernumber);
					} elseif ($country_code ==  "GH") //Ghana
					{
						$customernumber = $this->mobile_number_validation(9, 233, $customernumber);
					} elseif ($country_code ==  "YE") //Yemen
					{
						$customernumber = $this->mobile_number_validation(9, 967, $customernumber);
					} elseif ($country_code ==  "VE") //Venezuela
					{
						$customernumber = $this->mobile_number_validation(7, 58, $customernumber);
					} else {
						$customernumber = $this->mobile_number_validation_without_country($customernumber);
					}
				}


				if ($customer_notification == '1') {

					$customer_message = str_replace('{customername}', $order_customer, $customer_message);
					$customer_message = str_replace('{storename}', $store_name, $customer_message);
					$customer_message = str_replace('{orderdate}', $order_date_formatted, $customer_message);
					$customer_message = str_replace('{productname}', $exploded_names, $customer_message);
					$customer_message = str_replace('{amountwithcurrency}', $order_currency . ' ' . $order_amount, $customer_message);
					$customer_message = str_replace('{customeremail}', $billing_email, $customer_message);
					$customer_message = str_replace('{billingcity}', $city, $customer_message);
					$customer_message = str_replace('{billingstate}', $stateCode, $customer_message);
					$customer_message = str_replace('{billingcountry}', $countryCode, $customer_message);
					$customer_message = str_replace('{customernumber}', $customernumber, $customer_message);
					$customer_message = preg_replace("/\r\n/", "<br>", $customer_message);

				
					$data_decoded = array(
						"Username" => $whatso_username, "Password" => $whatso_password, "MessageText" => $customer_message, "MobileNumbers" => $customernumber, "ScheduleDate" => '', "FromNumber" => $from_number,
						"Channel" => '1'
					);
					$data = json_encode($data_decoded);

					$url = "https://api.whatso.net/api/v2/SendMessage";

					$response = wp_remote_post($url, array(
						'method' => 'POST',
						'headers' => array(
							'Content-Type' => 'application/json; charset=utf-8', 'WPRequest' => 'abach34h4h2h11h3h'
						),
						'body' => $data
					));
					if (is_array($response) and isset($response['body'])) {
						$response_obj = json_decode($response['body']);
						if (is_object($response_obj)) {
							//code to update whatso_order_notification_details
							$insert_array = array(
								'user_type' => 'customer',
								'message_api_request' => $data,
								'message_api_response' =>  wp_json_encode($response_obj) ,
								);
			
							$wpdb->insert($order_table, $insert_array);
						}
					}
				}
			}
		}
	}

	public function mobile_number_validation($countrynumberlength, $countrycode, $customernumber)
	{

		if (strlen($customernumber) === $countrynumberlength) {

			$customernumber = $countrycode . $customernumber;
		} elseif (strlen($customernumber) === $countrynumberlength - 1) {

			$customernumber = "";
		} elseif (strlen($customernumber) == $countrynumberlength + 1) {
			$result = substr($customernumber, 0, 1);
			if (($result == "0") || ($result == $countrycode)) {
				$customernumber = substr($customernumber, 1, $countrynumberlength);
				$customernumber = $countrycode . $customernumber;
			} else {
				$customernumber = "";
			}
		} elseif (strlen($customernumber) == $countrynumberlength + 2) {
			$result = substr($customernumber, 0, 2);
			if (strcmp($result, $countrycode)) {
				$customernumber = "";
			}
		} elseif (strlen($customernumber) == $countrynumberlength + 3) {

			$result = substr($customernumber, 0, 3);

			if (strcmp($result, $countrycode)) {
				$customernumber = "";
			}
		} elseif (strlen($customernumber) >= $countrynumberlength + 4) {

			$result = substr($customernumber, 0, 4);

			if (strcmp($result, $countrycode)) {
				$customernumber = "";
			}
		}
		// return $customernumber;
		//Additional Validation
		$data = get_option('whatso_abandoned');
		$data = json_decode($data);
		$default_county_code = $data->default_country;
		// get the countrynumberlength(withcode) and check if the $customernumber.lenght is equal  to it or not
		$countrynumberlength1 = $countrynumberlength + strlen($countrycode);

		if (strlen($customernumber) === $countrynumberlength1) {
			// if true return $customernumber	
			return $customernumber;
		}
		// if not true - get the default country code saved by admin and append it to the $customernumber in another temp variable
		else {
			$tempnumber = $default_county_code . $customernumber;
			// now check if tempvariable lenght is equal to countrynumberlength - if yes, return tempvariable number else return $customernumber
			if (strlen($tempnumber) == $countrynumberlength1) {
				// if true return $customernumber
				$customernumber = $tempnumber;
				return $customernumber;
			}
		}
	}

	public function updatefooteradmin ( $default ) {
		global $pagenow;
		
		$setting_pages = array(
			'whatsapp_order_notification'

		);
		
        $post_type = filter_input( INPUT_GET, 'post_type' );
		if ( ! $post_type ) {
			$post_type = get_post_type( filter_input( INPUT_GET, 'post' ) );
		}
		
		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && in_array( sanitize_text_field($_GET['page']), $setting_pages )) {
			echo ' ' . esc_attr( 'by' ) . ' <a href="https://www.whatso.net/" target="_blank">Whatso </a> WhatsApp Order Notification V 1.0';
        }
	}
}
