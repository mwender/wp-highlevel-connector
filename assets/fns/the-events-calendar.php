<?php

namespace HLC\theEventsCalendar;

/**
 * Sends an attendee to the HighLevel API.
 *
 * @param      int  $attendee_id  The attendee identifier
 * @param      int  $post_id      The post identifier
 * @param      int  $order        The order
 * @param      int  $product_id   The product identifier
 */
function send_attendee_to_highlevel( $attendee_id, $post_id, $order, $product_id ){
  $attendee = get_post( $attendee_id );
  $product = wc_get_product( $product_id );

  // Build string of tags associated with this product
  $tribe_event_id = get_post_meta( $product_id, '_tribe_wooticket_for_event', true );
  $terms = get_the_terms( $tribe_event_id, 'post_tag' );
  $tag_array = [];
  $tag_string = '';
  if( $terms && is_array( $terms ) ){
    foreach ($terms as $key => $term ) {
      $tag_array[] = strtolower( $term->name );
    }
    $tag_string = implode( ',', $tag_array );
  }

  // Build our contact
  // TODO: Get the contact's phone number:
  $contact = [];
  $contact['name'] = get_post_meta( $attendee_id, '_tribe_tickets_full_name', true );
  $contact['email'] = get_post_meta( $attendee_id, '_tribe_tickets_email', true );
  $contact['tags'] = $tag_string;
  $meta = get_post_meta( $attendee_id, '_tribe_tickets_meta', true );
  uber_log('🔔 $meta = ' . print_r( $meta, true ) );
  if( $meta && is_array( $meta ) ){
    foreach ($meta as $key => $value) {
      $contact[$$key] = $value;
    }
  }
  uber_log('🔔 $contact = ' . print_r( $contact, true ) );

  if( ! isset( $contact['phone'] ) ){
    // `_tribe_tickets_meta` hasn't been saved yet so
    // we'll add this contact to our delayed
    // processing queue to be sent over momentarily
    // once the additional meta data has been saved.
    $data = [
      'attendee_id' => $attendee_id,
      'contact'     => $contact,
    ];
  }

  // Send attendee to HighLevel
  //$response = post_ghl_contact( $contact );
  //uber_log('🔔 $response = ' . print_r( $response, true ) );
}
add_action('event_ticket_woo_attendee_created', __NAMESPACE__ . '\\send_attendee_to_highlevel', 10, 4 );

/**
 * Posts a Contact to the HighLevel API.
 *
 * @param      array  $contact {
 *     @type  string  $name  Contact's name.
 *     @type  string  $email Contact's email address.
 *     @type  string  $phone Contact's phone number.
 *     @type  string  $tags  Comma separated list of tags.
 * }
 *
 * @return     object  Response object from the HighLevel API.
 */
function post_ghl_contact( $contact ){
  $response = wp_remote_post( 'https://rest.gohighlevel.com/v1/contacts/', [
    'headers' => [
      'Authorization' => 'Bearer ' . HIGHLEVEL_API,
    ],
    'body' => $contact,
  ]);

  return $response;
}