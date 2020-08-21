<?php
/*
* Plugin Name: AMS for WordPress
* Plugin URI: https://amsnetwork.ca
* Author: AMS Network
* Author URI: https://amsnetwork.ca
* Description: Lorem Ipsum is simply dummy text of the printing and typesetting industry.
* Version: 1.0.0
*/

//If this file is called directly, abort.
if (!defined( 'WPINC' )) {
    die;
}

//Define Constants
if ( !defined('WPD_AMS_PLUGIN_VERSION')) {
    define('WPD_AMS_PLUGIN_VERSION', '1.0.0');
}
if ( !defined('WPD_AMS_PLUGIN_DIR')) {
    define('WPD_AMS_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
}

//Include Scripts & Styles
if( !function_exists('wpdams_plugin_scripts')) {
    function wpdams_plugin_scripts() {

        wp_enqueue_style( 'slider', WPD_AMS_PLUGIN_DIR . 'assets/css/amsstyle.css',false,'1.1','all');


        wp_enqueue_style('wpac-css', WPD_AMS_PLUGIN_DIR. 'assets/css/style.css');
        wp_enqueue_script('amsjsajax', WPD_AMS_PLUGIN_DIR. 'assets/js/main.js', 'jQuery', '1.0.0', true );

        wp_enqueue_script('amsblock-js', WPD_AMS_PLUGIN_DIR. 'assets/js/amsblock.js', 'jQuery', '1.0.0', true );

        wp_localize_script( 'amsjsajax', 'amsjs_ajax_url',
            array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))
        );
    }
    add_action('wp_enqueue_scripts', 'wpdams_plugin_scripts');
}





function wpdams_settings_page_html() {
   
   ?>
        <div class="wrap">
            <h1 style="padding:10px; background:#333;color:#fff"><?= esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post" class="wpamsform">
                <?php 
                    // output security fields for the registered setting "wpac-settings"
                    settings_fields('wpams-settings' );

                    // output setting sections and their fields
                    // (sections are registered for "wpac-settings", each field is registered to a specific section)
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
    //add_menu_page( 'AMS System', 'AMS Settings', 'manage_options', 'wpams-settings', 'wpdams_settings_page_html', 'dashicons-admin-plugins', 30 );

    //add_theme_page( 'AMS System', 'AMS Settings', 'manage_options', 'wpams-settings', 'wpdams_settings_page_html', 30 );

    add_submenu_page( 'settings.php','AMS for WordPress', 'AMS Settings', 'manage_options', 'wpams-settings', 'wpdams_settings_page_html', 30 );

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
    $setting = get_option('wpams_apikey_btn_label');
    // output the field
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

//=======================
$apiurlcheck = get_option('wpams_url_btn_label');
$apikeycheck = get_option('wpams_apikey_btn_label');

// Load assets for wp-admin when editor is active
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


//===================================================================
//========================== category shortcode =======================
//====================================================================
require plugin_dir_path( __FILE__ ). 'inc/filter.php';


//===================================================================
//========================== product shortcode =======================
//====================================================================
require plugin_dir_path( __FILE__ ). 'inc/equipment.php';

require plugin_dir_path( __FILE__ ). 'inc/categoryequipment.php';


function ams_get_category_action()
{
    //echo "hello 1235";

    //https://wpd.amsnetwork.ca/api/v3/assets?type=Equipment&access_token=de5cb03d9c6bb57d5cd41b0616e72716d53fb5f6ec34e6bb0b8ff05acf029dac&method=get&format=json

    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    $categoryid = $_POST['catid'];

    //die();

    $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&category_ids=%5B".$categoryid."%5D&access_token=".$apikey."&method=get&format=json";


        /*echo $producturl;
        die;*/

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

                        if(isset($x_value['name']))
                        {
                            echo "<h3>". $x_value['name'] ."</h3>";
                            echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                        }
                   
                }
            }
         

    die();
}
add_action('wp_ajax_getcategory_action','ams_get_category_action');
add_action('wp_ajax_nopriv_getcategory_action','ams_get_category_action');

//Settings Menu & Page
//require plugin_dir_path( __FILE__ ). 'inc/settings.php';


function search_category_action()
{
    
    //https://wpd.amsnetwork.ca/api/v3/assets?type=Equipment&access_token=de5cb03d9c6bb57d5cd41b0616e72716d53fb5f6ec34e6bb0b8ff05acf029dac&method=get&format=json

    $apiurlcat = get_option('wpams_url_btn_label');
    $apikeycat = get_option('wpams_apikey_btn_label');
    //$categoryid = $_POST['catid'];

    $prodname = $_POST['keyword'];
    $productname = urlencode($prodname);

    $producturl = "https://".$apiurlcat.".amsnetwork.ca/api/v3/assets?type=Equipment&query_string=".$productname."&access_token=".$apikeycat."&method=get&format=json";


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

                        if(isset($x_value['name']))
                        {
                            echo "<h3>". $x_value['name'] ."</h3>";
                            echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                        }
                   
                }
            }
         

    die();
}
add_action('wp_ajax_searchcategorydata_action','search_category_action');
add_action('wp_ajax_nopriv_searchcategorydata_action','search_category_action');



// Infinite scroll

function infinitescroll_action()
{
    //echo "hello 1235";

    //https://wpd.amsnetwork.ca/api/v3/assets?type=Equipment&access_token=de5cb03d9c6bb57d5cd41b0616e72716d53fb5f6ec34e6bb0b8ff05acf029dac&method=get&format=json

    $apiurl = get_option('wpams_url_btn_label');
    $apikey = get_option('wpams_apikey_btn_label');
    
    $categoryid = $_POST['catid'];

    $page = $_POST['page'];

    //die();

        /*$producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&category_ids=%5B".$categoryid."%5D&access_token=".$apikey."&method=get&format=json";*/

        $producturl = "https://".$apiurl.".amsnetwork.ca/api/v3/assets?type=Equipment&category_ids=%5B".$categoryid."%5D&page=".$page."&access_token=".$apikey."&method=get&format=json";

        /*echo $producturl;
        die;*/

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
                                echo "<p>". $x_value['name'] ."</p>";
                                echo "<img src=".$x_value['photo']." alt=".$x_value['name'].">";
                            }
                        echo "</div>";
                    }    
                }
            }
        //echo "<img src=". esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) . ">";

    die();
}
add_action('wp_ajax_infinitescroll_action','infinitescroll_action');
add_action('wp_ajax_nopriv_infinitescroll_action','infinitescroll_action');

?>
