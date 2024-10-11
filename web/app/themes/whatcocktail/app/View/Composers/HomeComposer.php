<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class HomeComposer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'home',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'featured_image' => $this->get_home_featured_image(),
        ];
    }

    private function get_home_featured_image() {
        $homepage_id = get_option( 'page_on_front' ); 

        if ( $homepage_id ) {
            $featured_image_url = get_the_post_thumbnail_url( $homepage_id, 'full' );

            if ( $featured_image_url ) {
                return $featured_image_url; 
            }
        }

        return null;
    }
}
