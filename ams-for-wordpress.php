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
}

//Define Constants
if ( !defined('WPD_AMS_PLUGIN_VERSION')) {
    define('WPD_AMS_PLUGIN_VERSION', '1.0.0');
}
if ( !defined('WPD_AMS_PLUGIN_DIR')) {
    define('WPD_AMS_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
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
        add_rewrite_rule('^([^/]*)/([^/]*)/([^/]*)/?', 'index.php?pagename=$matches[1]&category=$matches[2]&proname=$matches[3]', 'top');

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
        add_rewrite_rule('^([^/]*)/([^/]*)/?', 'index.php?page=$matches[1]&categoryslug=$matches[2]', 'top');

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


//Include Scripts & Styles
if( !function_exists('wpdams_plugin_scripts')) {
    function wpdams_plugin_scripts() {

        wp_enqueue_style( 'slider', WPD_AMS_PLUGIN_DIR . 'assets/css/amsstyle.css',false,'1.1','all');


        //wp_enqueue_style('wpac-css', WPD_AMS_PLUGIN_DIR. 'assets/css/style.css');
        wp_enqueue_script('amsjsajax', WPD_AMS_PLUGIN_DIR. 'assets/js/main.js', 'jQuery', '1.0.0', true );

        wp_enqueue_script('amsblock-js', WPD_AMS_PLUGIN_DIR. 'assets/js/amsblock.js', 'jQuery', '1.0.0', true );

        wp_localize_script( 'amsjsajax', 'amsjs_ajax_url',
            array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))
        );
    }
    add_action('wp_enqueue_scripts', 'wpdams_plugin_scripts');
}


function wptuts_scripts_important()
{
     wp_enqueue_style('wpac-css', WPD_AMS_PLUGIN_DIR. 'assets/css/style.css',false,'10','all');
}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_important', 20 );

// Sidebar category function
function get_sidebarcategory()
{
    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $url = "https://".$apiurl.".amsnetwork.ca/api/v3/assets";
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

    return $catArrayResultData = json_decode($json, true);
}
add_action('wp_ajax_get_sidebarcategory','get_sidebarcategory');
add_action('wp_ajax_nopriv_get_sidebarcategory','get_sidebarcategory');
// End sidebar category

function wpdams_settings_page_html() {
   
   ?>
        <div class="wrap">
            <h1 style="padding:10px; background:#333;color:#fff"><?= esc_html(get_admin_page_title()); ?></h1>

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
                <?php 
                    // output security fields for the registered setting "wpac-settings"
                    settings_fields('wpams-settings' );

                    do_settings_sections('wpams-settings');

                    // output save settings button 
                    submit_button( 'Save Changes' );
                ?>
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
    

    add_settings_section( 'wpams_label_settings_section', '', 'wpams_plugin_settings_section_cb', 'wpams-settings' );

    add_settings_field( 'wpams_url_label_field', 'API  Subdomain', 'wpams_url_label_field_cb', 'wpams-settings', 'wpams_label_settings_section' );

    add_settings_field( 'wpams_apikey_label_field', 'API  Key', 'wpams_apikey_label_field_cb', 'wpams-settings', 'wpams_label_settings_section' );
   
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
    <input type="text" name="wpams_url_btn_label" style="width: 500px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <?php
}


// Field callback function
function wpams_apikey_label_field_cb(){ 
    // get the value of the setting we've registered with register_setting()
    $apikey = get_option('wpams_apikey_btn_label');
    if(!empty($apikey))
    {
        $setting = sanitize_text_field("**************************************************************");
    }

    ?>
    <input type="text" name="wpams_apikey_btn_label" style="width: 500px;" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">

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
          array( 'wp-blocks', 'wp-element' )
       );

       wp_enqueue_style(
          'amsblockstyle-css',
          plugins_url( 'assets/css/amsblockstyle.css', __FILE__ ),
          array()
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

// CTA for Short code amscategoryequipment
require plugin_dir_path( __FILE__ ). 'inc/categoryequipment.php';


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



// Get data on click id from sidebar menu
function ams_get_category_action()
{
    $categoryid = $_POST['catid'];

    $arrayResult = get_apirequest($categoryid,NULL,NULL);

    foreach($arrayResult as $json_value) {
        
        foreach($json_value as $x_value) { 

            if(isset($x_value['id']))
            {
                echo "<div class='productstyle'>";
                
                    if(isset($x_value['name']))
                    {
                        echo "<a href='".site_url('/product/'.$x_value['category_name'].'/'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";

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
    
    $prodname = $_POST['keyword'];
    $productname = urlencode($prodname);

    $arrayResult = get_apirequest(NULL,$productname,NULL);

    foreach($arrayResult as $json_value) {
        
        foreach($json_value as $x_value) { 
            if(isset($x_value['id']))
            {
                echo "<div class='productstyle'>";
                   
                    if(isset($x_value['name']))
                    {
                        echo "<a href='".site_url('/product/'.$x_value['category_name'].'/'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";
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
add_action('wp_ajax_searchcategorydata_action','search_category_action');
add_action('wp_ajax_nopriv_searchcategorydata_action','search_category_action');
// End get data after search product


// Infinite scroll
function infinitescroll_action()
{

    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    
    $categoryid = $_POST['catid'];
    //die;

    $page = $_POST['page'];
    $newslugname = $_POST['slugname'];

    $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&category_ids=%5B".$categoryid."%5D&page=".$page."&access_token=".$apikey."&method=get&format=json";

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

        
            foreach($arrayResult as $json_value) {
                
                foreach($json_value as $x_value) { 

                    if(isset($x_value['id']))
                    {
                        echo "<div class='productstyle'>";

                            if(isset($x_value['name']))
                            {

                                echo "<a href='".site_url('/'.$newslugname.'/'.$x_value['category_name'].'/'.$x_value['id'])."'> <p class='product-title'>". $x_value['name'] ."</p> </a>";

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
        
        //echo "<img src=". esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) . ">";

    die();
}
add_action('wp_ajax_infinitescroll_action','infinitescroll_action');
add_action('wp_ajax_nopriv_infinitescroll_action','infinitescroll_action');
// End infinite scroll


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
                        echo "<img src=".$json_value['photo_medium']." alt=".$json_value['name'].">";
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