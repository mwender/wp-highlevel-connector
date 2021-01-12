<?php

namespace WPHLC\theEventsCalendar;

/**
 * Builds a string of tags assigned to the Event/Product.
 *
 * @param      int  $product_id  The product identifier
 *
 * @return     string  The order tags.
 */
function get_order_tags( $product_id ){
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

  return $tag_string;
}

/**
 * Grabs all Attendees for an event and sends their info to HighLevel.
 *
 * Hooks to `event_tickets_woo_complete_order`.
 *
 * @param      int  $order_id  The order ID.
 */
function order_complete( $order_id ){
  $attendees = tribe_tickets_get_attendees( $order_id );

  foreach( $attendees as $attendee ){
    $contact = [
      'name'  => $attendee['holder_name'],
      'email' => $attendee['holder_email'],
      'tags'  => get_order_tags( $attendee['product_id'] ),
      'phone' => $attendee['attendee_meta']['phone']['value'],
    ];

    // Send attendee to HighLevel
    $response = post_ghl_contact( $contact );
  }
}
add_action( 'event_tickets_woo_complete_order', __NAMESPACE__ . '\\order_complete' );

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