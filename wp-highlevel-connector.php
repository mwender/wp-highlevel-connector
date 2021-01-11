<?php
/**
 * Plugin Name:     WordPress HighLevel Connector
 * Plugin URI:      https://github.com/mwender/wp-highlevel-connector
 * Description:     Provides various connections between WordPress and HighLevel CRM
 * Author:          Michael Wender
 * Author URI:      https://mwender.com
 * Text Domain:     highlevel-connector
 * Domain Path:     /languages
 * Version:         1.0.1
 *
 * @package         Highlevel_Connector
 */

// Include required files
require_once('assets/fns/the-events-calendar.php');

/**
 * Adds a WordPress admin notice with instructions for
 * adding your HighLevel Account API Key to wp-config.php.
 */
function hlc_admin_notice(){
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
  add_action( 'admin_notices', 'hlc_admin_notice' );


if( ! function_exists( 'uber_log' ) ){
  /**
   * Enhanced logging.
   *
   * @param      string  $message  The log message
   */
  function uber_log( $message = null ){
    static $counter = 1;

    $bt = debug_backtrace();
    $caller = array_shift( $bt );

    if( 1 == $counter )
      error_log( "\n\n" . str_repeat('-', 25 ) . ' STARTING DEBUG [' . date('h:i:sa', current_time('timestamp') ) . '] ' . str_repeat('-', 25 ) . "\n\n" );
    error_log( "\n" . $counter . '. ' . basename( $caller['file'] ) . '::' . $caller['line'] . "\n" . $message . "\n---\n" );
    $counter++;
  }
}