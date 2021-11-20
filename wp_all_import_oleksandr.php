<?php
/**
 * @package Oleksandr
 * @version 1.0.0
 */
/*
Plugin Name: WP All Import Oleksandr
Plugin URI: 
Description: automatically assigned to the near/closest matching taxonomy
Author: Oleksandr S.
Version: 1.0.0
Author URI: 
*/

define(
    'OLEKSANDR_TERM_MAPPING',
    array(
        'cafe coffee shop' => 'Cafe / Espresso Bar',
        'chinese restaurant' => 'Restaurant',
        'cleaning company' => 'Cleaning Business',
    )
);
function oleksandr_pmxi_single_category( $term_into, $tax_name ) {

    if ( is_array( $term_into ) && ! empty( $term_into['name'] ) ) {
        $term = is_exists_term( $term_into['name'], $tax_name );
        if ( ! empty( $term ) ) {
            return $term_into;
        }

        if ( isset( OLEKSANDR_TERM_MAPPING[ strtolower( $term_into['name'] ) ] ) ) {
            $term_into['name'] = OLEKSANDR_TERM_MAPPING[ strtolower( $term_into['name'] ) ];
            return $term_into;
        }

        $terms = explode( '-', $term_into['name'] );
        if ( 1 === count( $terms ) ) {
            $terms = explode( ' ', $term_into['name'] );
        }
        
        foreach ( $terms as $t ) { 
            $t = trim( ucfirst( $t ) );
            $term = is_exists_term( $t, $tax_name );
            if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
                $cat_id = $term['term_id'];
                $term = get_term_by( 'id', $cat_id, $tax_name );
                if ( $term && ! is_wp_error( $term ) ) {
                    $term_into['name'] = $term->name;
                    return $term_into;
                }
            }
        }
    }
    return $term_into; 
  
}

add_filter( 'pmxi_single_category', 'OLEKSANDR_pmxi_single_category', 10, 2 );
