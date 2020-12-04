<?php
/*
* Plugin Name: AMS For Wordpress
* Plugin URI: https://amsnetwork.ca
* Author: amsnetwork.ca
* Author URI: https://amsnetwork.ca
* Description: Lorem Ipsum is simply dummy text of the printing and typesetting industry.
* Version: 1.0.0
*/

//If this file is called directly, abort.
if (!defined( 'WPINC' )) {
    die;
}

//register_deactivation_hook( __FILE__, 'amsnetwork_deactivate' );
//register_uninstall_hook( __FILE__, 'amsnetwork_deactivate' );
register_deactivation_hook( __FILE__, 'amsnetwork_deactivate' );
function amsnetwork_deactivate(){
   delete_option('wpams_url_btn_label');
   delete_option('wpams_apikey_btn_label');
   delete_option('wpams_landing_url_btn_label');
   delete_option('wpams_button_colour_btn_label');
}

//Define Constants
if ( !defined('WPD_AMS_PLUGIN_VERSION')) {
    define('WPD_AMS_PLUGIN_VERSION', '1.0.0');
}
if ( !defined('WPD_AMS_PLUGIN_DIR')) {
    define('WPD_AMS_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
}

add_action(
    'plugins_loaded', 
    array(Member::get_instance(), 'setup')
);

// Rewrite rule for members
class Member {

    protected static $instance = NULL;

    public function __construct() {}

    public static function get_instance() {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }    

    public function setup() {
        add_action('init', array($this, 'rewrite_rules'));
        add_filter('query_vars', array($this, 'query_vars'), 10, 1);
        add_action('parse_request', array($this, 'parse_request'), 10, 1);

        register_activation_hook(__FILE__, array($this, 'flush_rules' ));

    }

    public function rewrite_rules() {
        add_rewrite_rule('^members/([^/]*)/details/?', 'index.php?member_id=$matches[1]', 'top');

        flush_rewrite_rules();
    }

    public function query_vars($vars) {
        $vars[] = 'member_id';
        $vars[] = 'member_type';
        return $vars;
    }

    public function flush_rules(){
        $this->rewrite_rules();
        flush_rewrite_rules();
    }

    public function parse_request($wp){
        if ( array_key_exists( 'member_id', $wp->query_vars ) ){
            include plugin_dir_path(__FILE__) . 'member-details.php';
            exit();
        }
    }
}

// rewrite_rule
add_action(
    'plugins_loaded', 
    array(Registration::get_instance(), 'setup')
);

class Registration {

    protected static $instance = NULL;

    public function __construct() {}

    public static function get_instance() {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }    

    public function setup() {
        add_action('init', array($this, 'rewrite_rules'));
        add_filter('query_vars', array($this, 'query_vars'), 10, 1);
        add_action('parse_request', array($this, 'parse_request'), 10, 1);

        register_activation_hook(__FILE__, array($this, 'flush_rules' ));

    }

    public function rewrite_rules(){
       /* add_rewrite_rule('^product/([^/]*)/([^/]*)/?', 'index.php?category=$matches[1]&proname=$matches[2]', 'top');*/
        add_rewrite_rule('([^/]+)/([^/]*)/([^/]*)/?', 'index.php?category=$matches[2]&proname=$matches[3]', 'top');

        flush_rewrite_rules();
    }

    public function query_vars($vars){
        $vars[] = 'category';
        $vars[] = 'proname';
        return $vars;
    }

    public function flush_rules(){
        $this->rewrite_rules();
        flush_rewrite_rules();
    }

    public function parse_request($wp){
        if ( array_key_exists( 'category', $wp->query_vars ) ){
            include plugin_dir_path(__FILE__) . 'productdetails.php';
            exit();
        }
    }

}
// End rewrite_rule

// Category rewrite rule
add_action(
    'plugins_loaded', 
    array(CategoryRegistration::get_instance(), 'setup')
);

class CategoryRegistration {

    protected static $instance = NULL;

    public function __construct() {}

    public static function get_instance() {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }    

    public function setup() {

        add_action('init', array($this, 'catrewrite_rules'));
        add_filter('query_vars', array($this, 'query_vars'), 10, 1);
        add_action('parse_request', array($this, 'parse_request'), 10, 1);

        register_activation_hook(__FILE__, array($this, 'flush_rules' ));

    }

    public function catrewrite_rules(){

        $urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $tokens = explode('/', $urlArray);
        //echo $tokens[sizeof($tokens)-2];
        $pageslug = $tokens[sizeof($tokens)-2];

        add_rewrite_rule('^'.$pageslug.'/([^/]*)/?', 'index.php?categoryslug=$matches[1]', 'top');
        //add_rewrite_rule('^ams-assets/([^/]*)/?', 'index.php?categoryslug=$matches[1]', 'top');
        // add_rewrite_rule('([^/]+)/([^/]*)/?', 'index.php?categoryslug=$matches[2]', 'top');
        

        flush_rewrite_rules();
    }

    public function query_vars($vars){
        $vars[] = 'categoryslug';
        return $vars;
    }

    public function flush_rules(){
        $this->catrewrite_rules();
        flush_rewrite_rules();
    }

    public function parse_request($wp){
        if ( array_key_exists( 'categoryslug', $wp->query_vars ) ){
            //echo $ab = $wp->query_vars['categoryslug'];
            include plugin_dir_path(__FILE__) . 'categoryproduct.php';
            exit();
        }
    }

}
// End category rewrite

// Event details

add_action(
    'plugins_loaded', 
    array(EventDetailsRegistration::get_instance(), 'setup')
);

class EventDetailsRegistration {

    protected static $instance = NULL;

    public function __construct() {}

    public static function get_instance() {
        NULL === self::$instance and self::$instance = new self;
        return self::$instance;
    }    

    public function setup() {



        add_action('init', array($this, 'eventrewrite_rules'));
        add_filter('query_vars', array($this, 'query_vars'), 10, 1);
        add_action('parse_request', array($this, 'parse_request'), 10, 1);

        register_activation_hook(__FILE__, array($this, 'flush_rules' ));

    }

    

    public function eventrewrite_rules(){
        
        $evposid = basename(rtrim($_SERVER["REQUEST_URI"], "/"));
        
        $posid = explode("-",$evposid);   

        $post_id = $posid[0];
        $post = get_post($post_id); 
        $pageslugurl = $post->post_name;

        
        //$pageslugurl = 'events';
         
        //$slugval = '^'.$sl.'/([^/]*)/?';
        add_rewrite_rule('^'.$pageslugurl.'/([^/]*)/?', 'index.php?eventslug=$matches[1]', 'top');
        //add_rewrite_rule('([^/]+)/([^/]*)/?', 'index.php?eventslug=$matches[2]', 'top');
        //add_rewrite_rule('^'.$pageslugurl.'/([^/]*)/?','index.php?page_id=1302&eventslug=$matches[1]','top');
        //add_rewrite_rule('^(.*)?/([A-Za-z0-9-]+-)(\d+)/?','index.php?page_id='.$page_id.'&eventslug=$matches[1]','top');
        
        flush_rewrite_rules();
    }

    public function query_vars($vars){
        $vars[] = 'eventslug';
        return $vars;
    }

    public function flush_rules(){
        $this->eventrewrite_rules();
        flush_rewrite_rules();
    }

    public function parse_request($wp){
        if ( array_key_exists( 'eventslug', $wp->query_vars ) ){
            include plugin_dir_path(__FILE__) . 'eventdetails.php';
            exit();
        }
    }

}

// End event details


//Include Scripts & Styles
if( !function_exists('wpdams_plugin_scripts')) {
    function wpdams_plugin_scripts() {

        wp_enqueue_style( 'slider', WPD_AMS_PLUGIN_DIR . 'assets/css/amsstyle.css',false,'1.1','all');


        //wp_enqueue_style('wpac-css', WPD_AMS_PLUGIN_DIR. 'assets/css/style.css');
        wp_enqueue_script('amsjsajax', WPD_AMS_PLUGIN_DIR. 'assets/js/main.js', 'jQuery', '1.0.0', true );

        wp_localize_script( 'amsjsajax', 'amsjs_ajax_url',
            array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))
        );
    }
    add_action('wp_enqueue_scripts', 'wpdams_plugin_scripts');
}


function wptuts_scripts_important()
{
     wp_enqueue_style('wpac-css', WPD_AMS_PLUGIN_DIR. 'assets/css/style.css',false,'10','all');

     if(wp_script_is('jquery')) {

        } else {
            wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
        }
}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_important', 20 );

// Sidebar category function
function get_sidebarcategory()
{
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $url = "https://".$apiurl.".amsnetwork.ca/api/v3/";
    $carurl = $url ."/categories?access_token=".$apikey."&method=get&format=json";

    $catch = curl_init();
    curl_setopt($catch,CURLOPT_URL,$carurl);
    curl_setopt($catch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($catch,CURLOPT_CONNECTTIMEOUT, 4);
    $json = curl_exec($catch);
    if(!$json) {
        echo curl_error($catch);
    }
    curl_close($catch);

    return $catArrayResultData = json_decode($json, true);
}
add_action('wp_ajax_get_sidebarcategory','get_sidebarcategory');
add_action('wp_ajax_nopriv_get_sidebarcategory','get_sidebarcategory');
// End sidebar category

// Sidebar category function
function get_member_types()
{
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $url = "https://".$apiurl.".amsnetwork.ca/api/v3/";
    $carurl = $url ."/member_types?access_token=".$apikey."&method=get&format=json";

    $catch = curl_init();
    curl_setopt($catch,CURLOPT_URL,$carurl);
    curl_setopt($catch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($catch, CURLOPT_CONNECTTIMEOUT, 10);
    $json = curl_exec($catch);
    if(!$json) {
        echo curl_error($catch);
    }
    curl_close($catch);

    return $catArrayResultData = json_decode($json, true);
}
add_action('wp_ajax_get_member_types','get_member_types');
add_action('wp_ajax_nopriv_get_member_types','get_member_types');
// End sidebar category

// Event location
function get_eventlocation()
{
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');

    //echo $_POST['eventtype'];
    $eventtype = $_POST['eventtype'];
    $eventstatus = $_POST['eventstatus'];

    if(isset($_POST['eventtype']))
    {
        $url = "https://".$apiurl.".amsnetwork.ca/api/v3/";
        $carurl = $url ."/programs/filters?type=".$eventtype."&status=".$eventstatus."&access_token=".$apikey."&method=get&format=json";
    }
    else
    {
        $url = "https://".$apiurl.".amsnetwork.ca/api/v3/";
        $carurl = $url ."/programs/filters?type=All&status=1&access_token=".$apikey."&method=get&format=json";
    }


    $catch = curl_init();
    curl_setopt($catch,CURLOPT_URL,$carurl);
    curl_setopt($catch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($catch,CURLOPT_CONNECTTIMEOUT, 4);

    $json = curl_exec($catch);
    if(!$json) {
        echo curl_error($catch);
    }
    curl_close($catch);

    $locationArrayResult = json_decode($json, true);

    if(isset($_POST['eventtype']))
    {   
        echo "<option value=''>All Location</option>";
        foreach($locationArrayResult['json']['locations'] as $c => $c_value) {
          echo "<option  value='".$c_value."'>".$c_value."</option>";     
        }
    }
    else
    {
       return $locationArrayResult;
    }
}

add_action('wp_ajax_get_eventlocation','get_eventlocation');
add_action('wp_ajax_nopriv_get_eventlocation','get_eventlocation');
// End event location


//subdomain validation
function subdomainkey_validation()
{
    $subdomain = $_POST['subdomain'];
   
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $url = "https://".$subdomain.".amsnetwork.ca/api/v3/assets";
    $carurl = $url ."/filter?access_token=".$apikey."&method=get&format=json";
    
    $catch = curl_init();
    curl_setopt($catch,CURLOPT_URL,$carurl);
    curl_setopt($catch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($catch,CURLOPT_CONNECTTIMEOUT, 4);
    $json = curl_exec($catch);
    if(!$json) {
        echo curl_error($catch);
    }
    curl_close($catch);
    //echo "hello";
    $catArrayResultData = json_decode($json, true);
    print_r($catArrayResultData);
}
add_action('wp_ajax_subdomainkey_validation','subdomainkey_validation');
add_action('wp_ajax_nopriv_subdomainkey_validation','subdomainkey_validation');




function get_sidebaroption()
{
    $post_id = get_the_ID();
    $post = get_post($post_id);
    $blocks = parse_blocks($post->post_content);
    return $blockname = $blocks[0]['attrs'];
}

function getDataOrDash($data) {
    if (isset($data) & !is_null($data) & $data != "") {
        return $data;
    }
    return "-";
}
// Page template



function wpdams_settings_page_html() {
   
   ?>
        <div class="wrap">
            <h1 style="padding:10px; background:#333;color:#fff"><?= esc_html(get_admin_page_title()); ?></h1>
            <div id="subdomainerror" class='notice notice-error is-dismissible'></div>
            <?php
            //echo "fasdfsd";
            $catArrayResult = get_sidebarcategory();
            //print_r($catArrayResult);
            if(isset($catArrayResult['error']))
            {
                 //settings_errors();

                echo "<div class='notice notice-error is-dismissible'><p>".$catArrayResult['error']."</p></div>";
            }
            else
            {
                settings_errors();
            }
            
            ?>

            <form action="options.php" method="post" class="wpamsform">
                <input class="ajaxurl" id="ajaxurl" type="hidden" value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" />
                <?php 
                    // output security fields for the registered setting "wpac-settings"
                    settings_fields('wpams-settings' );

                    do_settings_sections('wpams-settings');

                    // output save settings button 
                    //submit_button( 'Save Changes' );
                ?>

                <input type="button" class="button cleartext" id="cleartext" style="background: #dc3545;color: #fff; border-color: #dc3545;" value="<?php esc_attr_e( 'Clear API Settings' ); ?>" />

                <input name="submit" class="button button-primary savechanges" id="savechanges" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
            </form>
        </div>
    <?php

}

//Top Level Administration Menu
function wpac_register_menu_page() {
    add_submenu_page( 'settings.php','AMS System', 'AMS Settings', 'manage_options', 'wpams-settings', 'wpdams_settings_page_html', 30 );
}
add_action('admin_menu', 'wpac_register_menu_page');
//=====================


// Register settings, sections & fields.
function wpams_plugin_settings(){

    register_setting( 'wpams-settings', 'wpams_url_btn_label' );
    register_setting( 'wpams-settings', 'wpams_apikey_btn_label' );
    register_setting( 'wpams-settings', 'wpams_landing_url_btn_label' );
    register_setting( 'wpams-settings', 'wpams_landing_register_url_btn_label' );
    register_setting( 'wpams-settings', 'wpams_button_colour_btn_label' );
    
    register_setting('wpams-settings', 'url_window');
    register_setting('wpams-settings', 'register_url_window');

    add_settings_section( 'wpams_label_settings_section', '', 'wpams_plugin_settings_section_cb', 'wpams-settings' );

    add_settings_field( 'wpams_url_label_field', 'API  Subdomain', 'wpams_url_label_field_cb', 'wpams-settings', 'wpams_label_settings_section' );

    add_settings_field( 'wpams_apikey_label_field', 'API  Key', 'wpams_apikey_label_field_cb', 'wpams-settings', 'wpams_label_settings_section' );
   
    add_settings_field( 'wpams_landing_url_label_field', 'Booking  URL', 'wpams_landing_url_label_field_cb', 'wpams-settings', 'wpams_label_settings_section' );

    add_settings_field( 'wpams_landing_register_url_label_field', 'Register  URL', 'wpams_landing_register_url_label_field_cb', 'wpams-settings', 'wpams_label_settings_section' );

    add_settings_field( 'wpams_button_colour_label_field', 'Button  colour', 'wpams_button_colour_label_field_cb', 'wpams-settings', 'wpams_label_settings_section' );
}
add_action('admin_init', 'wpams_plugin_settings');




// Section callback function
function wpams_plugin_settings_section_cb(){
    //echo '<p></p>';
}

// Field callback function
function wpams_url_label_field_cb(){ 
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('wpams_url_btn_label');
    // output the field
    ?>
    <input type="text" id="subdomainurl" name="wpams_url_btn_label" style="width: 500px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <!-- <span  style="color: red;"></span> -->
    <?php
}


// Field callback function
function wpams_apikey_label_field_cb(){ 
    // get the value of the setting we've registered with register_setting()
    $apikey = get_option('wpams_apikey_btn_label');
    // output the field

   
    if(!empty($apikey))
    {
        $setting = sanitize_text_field("**************************************************************");
    }
     

    ?>
    <input type="text" id="starapikey" name="star_apikey" style="width: 500px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">

    <input type="text" id="apikeytext" name="wpams_apikey_btn_label" style="width: 500px;" value="<?php echo isset( $apikey ) ? esc_attr( $apikey ) : ''; ?>">

    <!-- <input type="text" name="wpams_apikey_btn_label" style="width: 500px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>"> -->

    <?php
}

// Field Booking url
function wpams_landing_url_label_field_cb(){ 
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('wpams_landing_url_btn_label');
    // output the field
    ?>
    <input type="url" required="" name="wpams_landing_url_btn_label" style="width: 350px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">

    <select name="url_window" id="urlwindow" style="width: 140px;">
      <option value="_self" <?php selected(get_option('url_window'), "_self"); ?>>Same Tab</option>
      <option value="_blank" <?php selected(get_option('url_window'), "_blank"); ?>>New Tab</option>
    </select>

    <?php
}

// Field for register url
function wpams_landing_register_url_label_field_cb()
{
    $setting = get_option('wpams_landing_register_url_btn_label');
    // output the field
    ?>
    <input type="url" required="" name="wpams_landing_register_url_btn_label" style="width: 350px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">

    <select name="register_url_window" id="urlwindow" style="width: 140px;">
      <option value="_self" <?php selected(get_option('register_url_window'), "_self"); ?>>Same Tab</option>
      <option value="_blank" <?php selected(get_option('register_url_window'), "_blank"); ?>>New Tab</option>
    </select>

    <?php
}

// Field Button colour url
function wpams_button_colour_label_field_cb(){ 
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('wpams_button_colour_btn_label');
    // output the field
    ?>
    <input type="color" id="colorpicker" name="color" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" style="width: 250px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">

    <input type="text" name="wpams_button_colour_btn_label" id="hexcolor"  pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" style="width: 250px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    
    <?php
}


//=====Settings option after activate plugin
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'salcode_add_plugin_page_settings_link');
function salcode_add_plugin_page_settings_link( $links ) {
    $links[] = '<a href="' .
        admin_url( 'admin.php?page=wpams-settings' ) .
        '">' . __('Settings') . '</a>';
    return $links;
}
//=====End Settings option after activate plugin

// Load assets for wp-admin when editor is active
$apiurlcheck = get_option('wpams_url_btn_label');
$apikeycheck = get_option('wpams_apikey_btn_label');

if(!empty($apiurlcheck) && !empty($apikeycheck))
{
    function ams_gutenberg_api_block_admin() {
       wp_enqueue_script(
          'amsblock-js',
          plugins_url( 'assets/js/amsblock.js', __FILE__ ),
          array( 'wp-blocks', 'wp-element', 'wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-i18n', 'wp-components', 'wp-data' )
       );

       wp_enqueue_script(
          'amsevent-js',
          plugins_url( 'assets/js/amsevent.js', __FILE__ ),
          array( 'wp-blocks', 'wp-element', 'wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-i18n', 'wp-components', 'wp-data' )
       );

       wp_enqueue_style(
          'amsblockstyle-css',
          plugins_url( 'assets/css/amsblockstyle.css', __FILE__ ),
          array()
       );
       
       wp_enqueue_script(
        'amsmember-js',
        plugins_url( 'assets/js/amsmember.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-plugins', 'wp-editor', 'wp-edit-post', 'wp-i18n', 'wp-components', 'wp-data' )
     );
    }
    add_action( 'enqueue_block_editor_assets', 'ams_gutenberg_api_block_admin' );
}
else
{
    function sample_admin_notice__error() {
    $class = 'notice notice-error is-dismissible';
    $message = __( 'Error! Please add subdomain and API key after active AMS Plugin.', 'sample-text-domain' );
     
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
    add_action( 'admin_notices', 'sample_admin_notice__error' );
}
//====================

function my_script($hook) {
    
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'assets/js/script.js');
}

add_action('admin_enqueue_scripts', 'my_script');


// CTA for Short code amscategoryequipment
require plugin_dir_path( __FILE__ ). 'inc/categoryequipment.php';

// CTA
require plugin_dir_path( __FILE__ ). 'inc/members.php';

// CTA for Short code event listing
require plugin_dir_path( __FILE__ ). 'inc/eventlisting.php';

// Get equipment product
function get_apirequest($categoryid,$productname,$prodictid)
{
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');

    
    
    if($categoryid)
    {
        $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&category_ids=%5B".$categoryid."%5D&access_token=".$apikey."&method=get&format=json";
    }
    elseif($productname)
    {
        $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&query_string=".$productname."&access_token=".$apikey."&method=get&format=json";
    }
    elseif($prodictid)
    {
        $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets/".$prodictid."?type=Equipment&access_token=".$apikey."&method=get&format=json";
    }
    else
    {

       $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&access_token=".$apikey."&method=get&format=json";
    }        


    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$producturl);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    curl_close($ch);

    return $arrayResultData = json_decode($json, true);
}
add_action('wp_ajax_get_apirequest','get_apirequest');
add_action('wp_ajax_nopriv_get_apirequest','get_apirequest');
// End equipment product

function get_members($member_type, $member_id) {
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');

    $base_url = "https://".$apiurl.".amsnetwork.ca/api/v3/";

    if($member_type) {
        $url = $base_url . "users/?access_token=".$apikey."&method=get&format=json&type=search_and_browse&sub_type=active_members&member_type_id=".$member_type;
    }
    else if($member_id) {
        $url = $base_url . "users/".$member_id."/?access_token=".$apikey."&method=get&format=json";
    }
    else {
        $url = $base_url . "users/?access_token=".$apikey."&method=get&format=json&type=search_and_browse&sub_type=active_members";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    curl_close($ch);

    return $arrayResultData = json_decode($json, true);
}

// Event Listing
function get_eventlisting($eventid)
{
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');

    if($eventid)
    {
        $eventlistingurl = "https://".$apiurl.".amsnetwork.ca/api/v3/programs/".$eventid."?type=Events&access_token=".$apikey."&method=get&format=json";
    }
    else
    {
        $blockdata = get_sidebaroption();

        $numberofevents = $blockdata['event_pagination'];

        if(isset($numberofevents))
        {
            $totalevents = $numberofevents;
        }
        else
        {
            $totalevents = 8;
        }
        $eventlistingurl = "https://".$apiurl.".amsnetwork.ca/api/v3/programs?type=All&per_page=".$totalevents."&access_token=".$apikey."&method=get&format=json";
    }


    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$eventlistingurl);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    curl_close($ch);

    return $arrayEventResultData = json_decode($json, true);
}
add_action('wp_ajax_get_eventlisting','get_eventlisting');
add_action('wp_ajax_nopriv_get_eventlisting','get_eventlisting');
// End Event Listing

// schedule time
function get_eventscheduletime($eventid)
{
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');

    
    $eventlistingurl = "https://".$apiurl.".amsnetwork.ca/api/v3/scheduled_program_dates?program_id=".$eventid."&access_token=".$apikey."&method=get&format=json";
    


    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$eventlistingurl);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    curl_close($ch);

    return $arrayEventResultData = json_decode($json, true);
}
add_action('wp_ajax_get_eventscheduletime','get_eventscheduletime');
add_action('wp_ajax_nopriv_get_eventscheduletime','get_eventscheduletime');
// End schedule time

// Get data on click id from sidebar menu
function ams_get_category_action()
{
    global $post;
    $pageslug = $post->post_name;
    $bgcolor = get_option('wpams_button_colour_btn_label');
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $categoryid = $_POST['catid'];



    $arrayResult = get_apirequest($categoryid,NULL,NULL);

    foreach($arrayResult as $json_value) {
        
        foreach($json_value as $x_value) { 

            if(isset($x_value['id']))
            {
                echo "<div class='productstyle'>";
                
                    if(isset($x_value['name']))
                    {
                        echo "<a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$x_value['id'])."'> <p class='product-title 123'>". $x_value['name'] ."</p> </a>";
                        
                        if($x_value['photo'] == NULL || $x_value['photo'] == "")
                        {                                    
                            echo "<div class='product-img-wrap'>";
                                echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$x_value['name'].">";
                             echo "</div>";
                        }
                        else
                        {
                         echo "<div class='product-img-wrap'>";
                            echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                         echo "</div>";
                        } 

                        echo "<div class='bottom-fix'>"; 
                        if($x_value['status_text'] == "Active")
                            echo "<p><span class='label label-success btn-common'>Available</span></p>";
                            else
                            {
                                echo "<p><span class='label label-danger btn-common'>Unavailable</span></p>";
                            }
                           
                        echo "</div>";    
                        }
                    echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";    
                    echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";

                    
                echo "</div>";
            }
        }
    }
         

    die();
}
add_action('wp_ajax_getcategory_action','ams_get_category_action');
add_action('wp_ajax_nopriv_getcategory_action','ams_get_category_action');
// End Get data on click id from sidebar menu


// Get data after search product
function search_category_action()
{
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $bgcolor = get_option('wpams_button_colour_btn_label');
    $prodname = $_POST['keyword'];
    $pageslug = $_POST['slugurl'];
    $categoryid = $_POST['catid'];
    $productname = urlencode($prodname);

    $post_data = get_page_by_path($pageslug);
    $pageid = $post_data->ID;

    //$arrayResult = get_apirequest($categoryid,$productname,NULL);

    $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&category_ids=%5B".$categoryid."%5D&query_string=".$productname."&access_token=".$apikey."&method=get&format=json";

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$producturl);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    curl_close($ch);

    $arrayResult = json_decode($json, true);

    $totalitems = $arrayResult['assets'];

    $post = get_post($pageid);
    $blocks = parse_blocks($post->post_content);
    
    //print_r($arrayResult);   
    if(!empty($totalitems))
    {
        if($blocks[0]['attrs']['radio_attr'] == "list_view")
            {  
            foreach($arrayResult as $json_value) {
            
                foreach($json_value as $x_value) { 
            
                    if(isset($x_value['id']))
                    {
                        
                        echo "<div class='listview-assets'>";
                        
                          echo   "<div class='assets-list-items'>";
                              if($x_value['photo'] == NULL || $x_value['photo'] == "")
                              {                                    
                                  echo "<div class='product-img'>";
                                      echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$x_value['name'].">";
                                   echo "</div>";
                              }
                              else
                              {
                               echo "<div class='product-img'>";
                                  echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                               echo "</div>";
                              }
                              echo"<div class='assetsproduct-content'>"; 

                                $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];
                                echo "<a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                                
                                echo"<div class='assetsprice'>" ;
                                if (!isset($blocks[0]['attrs']['member']))
                                {     
                                echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";
                                }
                                if(!isset($blocks[0]['attrs']['nonmember']))          
                                {         
                                echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";
                                }
                                echo"</div>"; 
                                
                                if($x_value['status_text'] == "Active")
                                {  
                                 echo "<span class='assetsproductlabel label-success btn-common' style='background-color: $bgcolor;'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Available</a></span>";
                                } 
                                else
                                {
                                  echo "<span class='label label-danger btn-common'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Unavailable</a></span>";
                                }
                                
                              echo "</div>";
                          echo "</div>";

                        echo "</div>";
                    }
                }
            }
        }
        else 
        {
            foreach($arrayResult as $json_value) {
                
                foreach($json_value as $x_value) { 
                    if(isset($x_value['id']))
                    {
                        echo "<div class='productstyle'>";
                           
                            if(isset($x_value['name']))
                            {
                                echo "<a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title 123'>". $x_value['name'] ."</p> </a>";
                                
                                if($x_value['photo'] == NULL || $x_value['photo'] == "")
                                {                                    
                                    echo "<div class='product-img-wrap'>";
                                        echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$x_value['name'].">";
                                     echo "</div>";
                                }
                                else
                                {
                                    echo "<div class='product-img-wrap'>";
                                        echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                                    echo "</div>";
                                }
                                 

                                echo "<div class='bottom-fix'>"; 
                                if($x_value['status_text'] == "Active")
                                    echo "<span class='label label-success btn-common' style='background-color: $bgcolor;'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Available</a></span>";
                                    else
                                    {
                                        echo "<span class='label label-danger btn-common'><a href='".site_url('/'.$pageslug.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Unavailable</a></span>";
                                    }
                                    
                                echo "</div>";    
                                }

                            echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";    
                            echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";

                            
                        echo "</div>";
                    }
                }
            }
        }    
    }
    else
    {
        echo $arrayResult = "No item found";
    }


    die();
}
add_action('wp_ajax_searchcategorydata_action','search_category_action');
add_action('wp_ajax_nopriv_searchcategorydata_action','search_category_action');
// End get data after search product

// Event search data
function search_event_action()
{   

    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $bgcolor = get_option('wpams_button_colour_btn_label');
    $prodname = $_POST['getevent'];
    $productname = urlencode($prodname);

    $pageslug = $_POST['pageslug'];
    $pageid = $_POST['pageid'];

    $eventtype = $_POST['eventtype'];

    $locaton = $_POST['evtlocation'];
    $eventlocaton = urlencode($locaton);

    $eventstatus = $_POST['eventstatus'];

    $eventperpg = $_POST['eventperpg'];

    if(!empty($_POST['getevent']))
    {
        $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/programs?type=All&query=".$productname."&access_token=".$apikey."&method=get&format=json";
    }
    else if(isset($eventtype))
    {
       $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/programs?type=".$eventtype."&location=".$eventlocaton."&status=".$eventstatus."&page=".$page."&per_page=".$eventperpg."&access_token=".$apikey."&method=get&format=json";
    }
    else
    {
        $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/programs?type=All&access_token=".$apikey."&method=get&format=json";
    }

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$producturl);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    curl_close($ch);

    $arrayResult = json_decode($json, true);

    
    if(!empty($arrayResult['programs']))
    {
        $post = get_post($pageid);
        $blocks = parse_blocks($post->post_content);
        $gridlayout = $blocks[0]['attrs']['radio_attr_event'];
        
        echo "<input type='hidden' id='totalprogram' value='".$arrayResult['meta']['total_count']."'>";

        if($gridlayout == "list_view")
        {
            foreach($arrayResult['programs'] as $x_value) 
            { 
                  if(isset($x_value['id']))
                  {

                   $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];  
                //List View
                echo "<div class='listview-events'>";
                  echo "<div class='productstyle-list-items'>";
                     
                      if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                      {                                    
                          echo "<div class='product-img'>";
                          echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." >"; 
                          echo "</div>";
                      }
                      else
                      {
                           echo "<div class='product-img'>";
                              echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                           echo "</div>";
                      }

                      echo "<div class='product-content'>";
                        $date=$x_value['earliest_scheduled_program_date'];
                          if(empty($date))
                          {
                            echo "<p>No Date Scheduled</P>";
                          }
                          else
                          {
                            if($eventtype == 'Classes')
                            {
                                echo "<p class='product-date'>".date('D, M d', strtotime($date))."<span class='multidate'> (Multiple Dates)</span></P>";
                            }
                            else
                            {
                                echo "<p class='product-date'> <span class='datetitle'>Earliest Date: </span>".date('D, M d Y', strtotime($date))."</P>"; 
                            }
                          }
                          echo "<p class='locationname'><strong>Location: </strong>".$x_value['location']."</p>";
                          echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";
                      echo "</div>";
                      
                        
                    echo "</div>";
                echo "</div>";
                //End list view
                   } // End if
            } // End foreach
        }
        else
        {

            foreach($arrayResult['programs'] as $x_value) { 

                    if(isset($x_value['id']))
                    {
                        
                        echo "<div class='productstyle eventlayout'>";
                        
                            if(isset($x_value['name']))
                            {
                                $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];


                                if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                                {                                    
                                    echo "<div class='product-img-wrap'>";
                                    echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ ).">";   
                                    echo "</div>";

                                }
                                else
                                {
                                     echo "<div class='eventlayout-image'>";
                                        echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                                     echo "</div>";
                                }

                                echo "<div class='eventtitle'>";
                                    $date=$x_value['earliest_scheduled_program_date'];
                                    if(empty($date))
                                    {
                                      echo "<p>No Date Scheduled</P>";
                                    }
                                    else
                                    {
                                      if($eventtype == 'Classes')
                                      {  
                                      echo "<p>".date('D, M d', strtotime($date))."<span class='multidate'> (Multiple Dates)</span></P>"; 
                                      }
                                      else
                                      {  
                                      echo "<p><span class='datetitle'>Earliest Date: </span>".date('D, M d', strtotime($date))."</P>"; 
                                      }
                                       
                                    } 
                                    echo "<a href='".site_url('/'.$pageslug.'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                                echo "</div>";
                                  
                                }
                            
                        echo "</div>";
                    }
                }
        }        
    }
    else
    {
        echo $arrayResult = "No item found";
    }


    die();
}
add_action('wp_ajax_searcheventdata_action','search_event_action');
add_action('wp_ajax_nopriv_searcheventdata_action','search_event_action');
// End event search data

// Infinite scroll
function infinitescroll_action()
{

    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $bgcolor = get_option('wpams_button_colour_btn_label');

    $categoryid = $_POST['catid'];

    $page = $_POST['page'];
    $newslugname = $_POST['slugname'];

    $allavailability = $_POST['allavailability'];
    //
    $post_data = get_page_by_path($newslugname);
    $pageid = $post_data->ID;
    $post = get_post($pageid);
    $blocks = parse_blocks($post->post_content);

    $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&is_available=".$allavailability."&category_ids=%5B".$categoryid."%5D&page=".$page."&access_token=".$apikey."&method=get&format=json";

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$producturl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
        $json = curl_exec($ch);
        if(!$json) {
            echo curl_error($ch);
        }
        curl_close($ch);

        $arrayResult = json_decode($json, true);
        echo "<input type='hidden' id='totalavailability' value='".$arrayResult['meta']['total_count']."'>";
        if($blocks[0]['attrs']['radio_attr'] == "list_view")
            {  
            foreach($arrayResult as $json_value) {
            
                foreach($json_value as $x_value) { 
            
                    if(isset($x_value['id']))
                    {
                        
                        echo "<div class='listview-assets'>";
                        
                          echo   "<div class='assets-list-items'>";
                              if($x_value['photo'] == NULL || $x_value['photo'] == "")
                              {                                    
                                  echo "<div class='product-img'>";
                                      echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$x_value['name'].">";
                                   echo "</div>";
                              }
                              else
                              {
                               echo "<div class='product-img'>";
                                  echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                               echo "</div>";
                              }
                              echo"<div class='assetsproduct-content'>"; 

                                $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];
                                echo "<a href='".site_url('/'.$newslugname.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                                
                                echo"<div class='assetsprice'>" ;
                                if (!isset($blocks[0]['attrs']['member']))
                                {     
                                echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";
                                }
                                if(!isset($blocks[0]['attrs']['nonmember']))          
                                {         
                                echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";
                                }
                                echo"</div>"; 
                                
                                if($x_value['status_text'] == "Active")
                                {  
                                 echo "<span class='assetsproductlabel label-success btn-common' style='background-color: $bgcolor;'><a href='".site_url('/'.$newslugname.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Available</a></span>";
                                } 
                                else
                                {
                                  echo "<span class='label label-danger btn-common'><a href='".site_url('/'.$newslugname.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Unavailable</a></span>";
                                }
                                
                              echo "</div>";
                          echo "</div>";

                        echo "</div>";
                    }
                }
            }
        }
        else 
        {
            foreach($arrayResult as $json_value) {
                
                foreach($json_value as $x_value) { 

                    if(isset($x_value['id']))
                    {
                        echo "<div class='productstyle'>";

                            if(isset($x_value['name']))
                            {

                                $assetstitle = (strlen($x_value['name']) > 34) ? substr($x_value['name'],0,34).'..' : $x_value['name'];
                                
                                echo "<a href='".site_url('/'.$newslugname.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'> <p class='product-title'>".$assetstitle ."</p> </a>";

                                if($x_value['photo'] == NULL || $x_value['photo'] == "")
                                {                                    
                                    echo "<div class='product-img-wrap'>";
                                        echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$x_value['name'].">";
                                     echo "</div>";
                                }
                                else
                                {
                                    echo "<div class='product-img-wrap'>";
                                    echo "<img  src=".$x_value['photo']." alt=".$x_value['name'].">";
                                    echo "</div>";
                                }    

                                echo "<div class='bottom-fix'>"; 
                                if($x_value['status_text'] == "Active")
                                    echo "<span class='label label-success btn-common' style='background-color: $bgcolor;'><a href='".site_url('/'.$newslugname.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Available</a></span>";
                                else
                                {
                                    
                                    echo "<span class='label label-danger btn-common'><a href='".site_url('/'.$newslugname.'/'.$x_value['category_name'].'/'.$pageid.'-'.$x_value['id'])."'>Unavailable</a></span>";
                                }
                                     
                                echo "</div>";
                            }
                            if (!isset($blocks[0]['attrs']['member']))
                            {
                            echo "<p class='memberprice'>".$x_value['price_types'][0][0]."</p>";
                            }
                            if (!isset($blocks[0]['attrs']['nonmember']))
                            {    
                            echo "<p class='price-non-mem'>".$x_value['price_types'][1][0]."</p>";
                            }

                        echo "</div>";
                    }    
                }
            }
        }
        //echo "<img src=". esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) . ">";

    die();
}
add_action('wp_ajax_infinitescroll_action','infinitescroll_action');
add_action('wp_ajax_nopriv_infinitescroll_action','infinitescroll_action');
// End infinite scroll

// Infinite scroll for Members
function member_ajax()
{

    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');

    $member_type = $_POST['member_type'];
    $query = $_POST['query'];

    $page = $_POST['page'];
    $newslugname = $_POST['slugname'];

    $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/users?access_token=".$apikey."&method=get&format=json&type=search_and_browse&sub_type=active_members&page=".$page;

    if (!is_null($member_type) && $member_type != "") {
        $producturl .= '&member_type_id='.$member_type;
    }

    if (!is_null($query) && $query != "") {
        $producturl .= '&query='.$query;
    }

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$producturl);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 10);
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    curl_close($ch);
    $dummy_image = "https://ssl.gstatic.com/images/branding/product/1x/avatar_square_blue_512dp.png";

    $arrayResult = json_decode($json, true);
    if (false) {
        foreach ($arrayResult["users"] as $member) {
            echo '<a class="member-item" href="'.site_url('/members/'.$member["id"].'/details' ).'">';
            echo '<div class="row member-entry">';
                echo '<div class="col-xs-12 col-sm-3 col-md-3 user-image">';
                    echo '<img src="'.$member['photo'] .'" onerror=\'this.src="'.$dummy_image.'"\' alt="'.$member["email"].'" style="height:150px; border-radius:5px">';
                echo "</div>";
                echo '<div class="col-xs-12 col-sm-9 col-md-9">';
                    echo '<div class="name">';
                        echo '<h5> '.$member["first_name"].' '.$member["last_name"].' </h5>';
                        echo '<p>';
                            echo '<strong>Job Position:</strong> <?= getDataOrDash($member["job_position"]) ?>';
                            echo '<br>';
                            echo '<strong>City:</strong> <?= getDataOrDash($member["city"]) ?>';
                            echo "<br>";
                            $join_date = strtotime($member['created_at']);
                            $newformat = date('M Y', $join_date);
                            echo "Member since: ".$newformat;
                            echo "<br>";
                        echo "</p>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
            echo "</a>";
        }
    } else {
        foreach ($arrayResult["users"] as $member) {
            echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="member">';
                    echo '<a class="member-item" href="'.site_url('/members/'.$member["id"].'/details' ).'">';
                        echo '<div class="col-lg-12 member-overlay"></div>';
                        echo '<img class="member-image-tile" src="'.$member['photo'] .'" onerror=\'this.src="'.$dummy_image.'"\' alt="'.$member["email"].'">';
                        echo '<div class="member-details fadeIn-bottom">';
                            echo '<h4 class="member-title">'.$member["first_name"].' '.$member["last_name"].'</h3>';
                            echo '<p class="member-text">'.$member["job_position"].'</p>';
                        echo "</div>";
                    echo "</a>";
                echo '</div>';
            echo '</div>';
        }
    }
    die();
}
add_action('wp_ajax_member_ajax','member_ajax');
add_action('wp_ajax_nopriv_member_ajax','member_ajax');


// Event button click
function geteventonclick_action()
{

    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $bgcolor = get_option('wpams_button_colour_btn_label');

    $categoryid = $_POST['catid'];
    //die;

    $page = $_POST['page'];
    $pageslug = $_POST['pageslugname'];
    $pageslugid = $_POST['pageslugid'];
    $eventperpg = $_POST['eventperpg'];

    $eventtype = $_POST['eventtype'];
    $eventstatus = $_POST['eventstatus'];
    $locaton = $_POST['evtlocation'];
    $eventlocaton = urlencode($locaton);


    $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/programs?type=".$eventtype."&location=".$eventlocaton."&status=".$eventstatus."&page=".$page."&per_page=".$eventperpg."&access_token=".$apikey."&method=get&format=json";

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$producturl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 4);
        $json = curl_exec($ch);
        if(!$json) {
            echo curl_error($ch);
        }
        curl_close($ch);

        $arrayResult = json_decode($json, true);

        $post = get_post($pageslugid);
        $blocks = parse_blocks($post->post_content);
        $gridlayout = $blocks[0]['attrs']['radio_attr_event'];
        
        if($gridlayout == "list_view")
        {
            foreach($arrayResult['programs'] as $x_value) 
                { 
                  if(isset($x_value['id']))
                  {

                   $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];  
                //List View
                echo "<div class='listview-events'>";
                  echo "<div class='productstyle-list-items'>";
                     
                      if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                      {                                    
                          echo "<div class='product-img'>";
                          echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." >"; 
                          echo "</div>";
                      }
                      else
                      {
                           echo "<div class='product-img'>";
                              echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                           echo "</div>";
                      }

                      echo "<div class='product-content'>";
                        $date=$x_value['earliest_scheduled_program_date'];
                          if(empty($date))
                          {
                            echo "<p>No Date Scheduled</P>";
                          }
                          else
                          {
                            echo "<p class='product-date'><span class='datetitle'>Earliest Date: </span>".date('D, M d Y', strtotime($date))."</P>"; 
                          }
                          echo "<p class='locationname'><strong>Location: </strong>".$x_value['location']."</p>";
                          echo "<a href='".site_url('/'.$pageslug.'/'.$pageslugid.'-'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";
                      echo "</div>";
                      
                        
                    echo "</div>";
                echo "</div>";
                //End list view
                   } // End if
                } // End foreach
        
        }
        else
        {

            foreach($arrayResult['programs'] as $x_value) { 

                if(isset($x_value['id']))
                {
                    
                    echo "<div class='productstyle eventlayout'>";
                    
                        if(isset($x_value['name']))
                        {
                            $assetstitle = (strlen($x_value['name']) > 43) ? substr($x_value['name'],0,40).'...' : $x_value['name'];


                            if($x_value['photo']['photo']['medium']['url'] == NULL || $x_value['photo']['photo']['medium']['url'] == "")
                            {                                    
                                echo "<div class='product-img-wrap'>";
                                  echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$x_value['name'].">";    
                                echo "</div>";
                            }
                            else
                            {
                                 echo "<div class='eventlayout-image'>";
                                    echo "<img src=".$x_value['photo']['photo']['medium']['url'].">";
                                 echo "</div>";
                            }

                            echo "<div class='eventtitle'>";
                            $date=date_create($arrayResult['program']['created_at']);
                                echo "<p><span class='datetitle'>Earliest Date: </span>".date_format($date, 'D, M d')."</P>"; 
                                echo "<a href='".site_url('/'.$pageslug.'/'.$pageslugid.'-'.$x_value['id'])."'> <p class='product-title'>". $assetstitle ."</p> </a>";
                            echo "</div>";
                              
                            }
                        
                    echo "</div>";
                }
            }
        
        }
        //echo "<img src=". esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) . ">";

    die();
}
add_action('wp_ajax_geteventonclick_action','geteventonclick_action');
add_action('wp_ajax_nopriv_geteventonclick_action','geteventonclick_action');
// End Event button click

// Ajax page detal
function equipmentproductdetails_action()
{
    
    $prodictid = $_POST['prodictid'];
    $arrayResult = get_apirequest(NULL,NULL,$prodictid);

    echo "<div class='product-detail-wrap'>";
    foreach($arrayResult as $json_value) {

        echo "<div class='pro-detail-inner'>";

             echo "<div class='pro-detail-left'>";

                echo "<a href='javascript:void(0)' onclick='return productback()' class='pro-back'><img class='back-img' src='". plugins_url( 'assets/img/back.png', __FILE__ ) ."' >Back</a>"; 

                if($json_value['photo'] == NULL || $json_value['photo'] == "")
                {                                    
                    echo "<div class='pro-img'>";
                        echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ )." alt=".$json_value['name'].">";
                     echo "</div>";
                }
                else
                {
                    echo "<div class='pro-img'>"; 
                    echo "<img src=".$json_value['photo_medium']." alt='".$json_value['name']."' onerror='this.src=\"".$json_value['photo_small']."\"'>";
                    echo "</div>";
                }
             echo "</div>";

             echo "<div class='pro-detail-right'>"; 
                echo "<div class='cat-name'>"; 
                    echo "<p >". $json_value['category_name'] ."</p>";
                echo "</div>";

                echo "<div class='pro-name'>"; 
                     echo "<p >". $json_value['name'] ."</p>";
                echo "</div>";

                echo "<div class='price_types'>";
                    echo "<div class='cat-name'>"; 
                        echo "<p >Prices(per day)</p>";
                    echo "</div>";
                    
                    echo "<p class='pro-price'>". $json_value['price_types'][0][0] ."</p>";
                    echo "<p class='pro-price non-mem'>". $json_value['price_types'][1][0] ."</p>";
                echo "</div>";
                
                echo "<div class='available-details'>"; 
                    if($json_value['status_text'] == "Active")
                    {
                        echo "<bR class='d-n'>";
                        echo "<p><span class='label label-success btn-common'>Available</span></p>";
                    }    
                    else
                    {
                        echo "<p><span class='label label-danger btn-common'>Unavailable</span></p>";
                    }
                echo "</div>"; 

                echo "<div class='pro-num'>";
                    echo "<div class='barcode cat-name'>"; 
                        echo "<p>Barcode Number:</p>";
                        echo "<spna class='B-text'>".$json_value['barcode']."</span>";
                    echo "</div>";

                    echo "<div class='barcode cat-name'>"; 
                        echo "<p>Serial Number:</p>";
                        echo "<spna class='B-text'>"
                        .$json_value['serial_number']."</span>";
                    echo "</div>";

                     echo "<div class='barcode cat-name'>"; 
                        echo "<p>Insurance Value:</p>";
                        echo "<spna class='B-text'>31738.25</span>";
                    echo "</div>";

                    
                echo "</div>";

             echo "</div>";

        echo "</div>";
        if($json_value['description'])
        {
            echo "<div class='product-des acc-des'>";
                echo "<p class='product-des-title'>Information</p>";
                echo "<p class='pro-des-text'>". $json_value['description'] ."</p>";
            echo "</div>";
        }
       
        echo "<div class='product-des-wrap'>";

            if($json_value['included_accessories']) 
            {
                echo "<div class='product-des m-r-pro'>";  
                echo "<p class='product-des-title' id='include-acc'>Included Accessories</p>";
                    echo "<div class='included_accessories' id='include-acc-des'>"; 
                        echo $json_value['included_accessories'];
                    echo "</div>";
                echo "</div>";
            }

            if($json_value['warranty_info']) 
            {
               echo "<div class='product-des '>";     
                echo "<p class='product-des-title'>Warranty Information</p>";
                echo "<p class='pro-des-text'>". $json_value['warranty_info'] ."</p>";

                echo "</div>"; 
            }
            
         echo "</div>";

       
           
    }
    echo "</div>";

    die();
}
add_action('wp_ajax_equipmentproductdetails_action','equipmentproductdetails_action');
add_action('wp_ajax_nopriv_equipmentproductdetails_action','equipmentproductdetails_action');
// End ajax page detal

?>