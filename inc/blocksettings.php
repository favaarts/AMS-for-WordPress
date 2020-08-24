<?php

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
    $message = __( 'Error! Please add subdomain and API key after activating the AMS Plugin.', 'sample-text-domain' );
     
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
    add_action( 'admin_notices', 'sample_admin_notice__error' );
}

?>