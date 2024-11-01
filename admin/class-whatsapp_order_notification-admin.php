<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.whatso.net/
 * @since      1.0.0
 *
 * @package    Whatsapp_order_notification
 * @subpackage Whatsapp_order_notification/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Whatsapp_order_notification
 * @subpackage Whatsapp_order_notification/admin
 * @author     whatso <hi@whatso.net>
 */
class Whatsapp_order_notification_Admin {

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

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        $valid_pages=array("whatsapp_order_notification");

        $page=isset($_REQUEST['page'])? sanitize_text_field(wp_unslash($_REQUEST['page'])): '';

        if(in_array($page,$valid_pages)){

            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/whatsapp_order_notification-admin.css', array(), $this->version, 'all' );

            // Css Links
            wp_register_style('prefix_bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all');
            wp_enqueue_style('prefix_bootstrap');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        $valid_pages=array("whatsapp_order_notification");
        $page=isset($_REQUEST['page'])? sanitize_text_field(wp_unslash($_REQUEST['page'])): '';

        if(in_array($page,$valid_pages)){

            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/whatsapp_order_notification-admin.js', array( 'jquery' ), $this->version, false );

            wp_register_script('prefix_bootstrap', includes_url('js/jquery/jquery.min.js'), array(), $this->version, false);
            wp_enqueue_script('prefix_bootstrap');


        }

    }

    //create menu method
    public function whatsapp_order_notification_menu(){

        add_menu_page("whatsapp_order_notification", "WhatsApp Order Notification","manage_options","whatsapp_order_notification", array($this,"whatsapp_order_notification_display"), plugin_dir_url( __FILE__ ) . 'images/whatso-new-logo.png', "100");
    }

    //menu call back function
    public function whatsapp_order_notification_display(){
        ob_start();//started buffer

        if (!get_option('order_notification')) {
            include_once(WHATSAPP_ORDER_NOTIFICATION_PATH."admin/partials/whatsapp_order_notification-admin-setup.php");
            //include template file
        }
        elseif(get_option('order_notification')){

            $data = get_option('order_notification');
            $data = json_decode($data);
            $whatso_email = $data->whatso_email;
            $whatso_username = $data->whatso_username;
            $whatso_password = $data->whatso_password;
            if(($whatso_email=="")||($whatso_username=="")||($whatso_password=="")){
                include_once(WHATSAPP_ORDER_NOTIFICATION_PATH."admin/partials/whatsapp_order_notification-admin-setup.php");
            }
            else{
                include_once(WHATSAPP_ORDER_NOTIFICATION_PATH."admin/partials/whatsapp_order_notification-admin-display.php");
            }
        }

        $view = ob_get_contents();//reading content
        ob_end_clean();//closing and cleaning buffer

        global $allowedposttags;
        $allowed_atts = array('align'=>array(), 'class'=>array(), 'id'=>array(), 'dir'=>array(), 'lang'=>array(), 'style'=>array(), 'xml:lang'=>array(), 'src'=>array(), 'alt'=>array(), 'name'=>array(), 'value'=>array(), 'type'=>array(), 'for'=>array(), 'form'=>array(), 'readonly'=>array(), 'required' => array(), 'autocomplete'=>array(), 'placeholder'=>array(), 'maxlength' => array(), 'method' => array(), 'action' => array(), 'checked' => array());

        $allowedposttags['strong'] = $allowed_atts;
        $allowedposttags['small'] = $allowed_atts;
        $allowedposttags['span'] = $allowed_atts;
        $allowedposttags['abbr'] = $allowed_atts;
        $allowedposttags['sup'] = $allowed_atts;
        $allowedposttags['form'] = $allowed_atts;
        $allowedposttags['button'] = $allowed_atts;
        $allowedposttags['label'] = $allowed_atts;
        $allowedposttags['div'] = $allowed_atts;
        $allowedposttags['img'] = $allowed_atts;
        $allowedposttags['input'] = $allowed_atts;
        $allowedposttags['textarea'] = $allowed_atts;
        $allowedposttags['h1'] = $allowed_atts;
        $allowedposttags['h2'] = $allowed_atts;
        $allowedposttags['h3'] = $allowed_atts;
        $allowedposttags['h4'] = $allowed_atts;
        $allowedposttags['h5'] = $allowed_atts;
        $allowedposttags['ol'] = $allowed_atts;
        $allowedposttags['ul'] = $allowed_atts;
        $allowedposttags['li'] = $allowed_atts;
        $allowedposttags['em'] = $allowed_atts;
        $allowedposttags['p'] = $allowed_atts;
        $allowedposttags['a'] = $allowed_atts;
        $allowedposttags['script'] = $allowed_atts;
        $allowedposttags['b'] = $allowed_atts;
        $allowedposttags['u'] = $allowed_atts;
        $allowedposttags['table'] = $allowed_atts;

        echo wp_kses_post($view, $allowedposttags);
    }





}