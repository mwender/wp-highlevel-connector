<?php

namespace WPHLC\notices;

/**
 * Adds a WordPress admin notice when Composer dependencies are mising.
 */
function run_composer_install_notice(){
  $heading = __( 'Missing Composer Dependencies', 'highlevel-connector' );
  printf( '<div class="notice-error notice"><h2>HighLevel Connector - %1$s</h2><p>Missing Composer Dependencies: Please run <code>composer install</code> from the root directory of the WordPress HighLevel Plugin to install missing Composer dependencies.</p></div>', esc_html( $heading ) );
}

/**
 * Adds a WordPress admin notice with instructions for
 * adding your HighLevel Account API Key to wp-config.php.
 */
function no_highlevel_api_key_notice(){
  $heading = __( 'No API Key Found!', 'highlevel-connector' );
  $message_1 = __( 'In order to communicate with your HighLevel account, please add your account\'s API Key as follows:', 'highlevel-connector' );
  $message_2 = __( 'In your HighLevel Account, go to "Settings &gt; Company &gt; Company Data" and copy your "API Key".', 'highlevel-connector' );
  $message_3 = __( 'Add your API Key to wp-config.php as a Named Constant like so:', 'highlevel-connector' );
  printf(
    '<div class="notice-error notice"><h2>HighLevel Connector - %1$s</h2><p>%2$s</p><ol><li>%3$s</li><li>%4$s %5$s</li></ol></div>',
    esc_html( $heading ),
    esc_html( $message_1 ),
    esc_html( $message_2 ),
    esc_html( $message_3 ),
    '<code>define( \'HIGHLEVEL_API\', \'_API_KEY_GOES_HERE_\' );</code>'
  );
}
if( ! defined( 'HIGHLEVEL_API' ) )
  add_action( 'admin_notices', __NAMESPACE__ . '\\no_highlevel_api_key_notice' );