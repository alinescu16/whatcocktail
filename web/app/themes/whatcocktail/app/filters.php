<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () : string
{
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Add known variables to the query vars filter.
 *
 * @return array
 */
add_filter( 'query_vars', function ( $query_vars ) : array 
{
    $query_vars[] = 'cocktail';
    return $query_vars;
} );