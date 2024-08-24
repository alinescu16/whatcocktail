<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'siteName' => $this->siteName(),
            'siteLogo' => $this->siteLogo()
        ];
    }

    /**
     * Returns the site name.
     *
     * @return string
     */
    private function siteName()
    {
        return get_bloginfo('name', 'display');
    }

    /**
     * Returns the site logo.
     * 
     * @return string
     */
    private function siteLogo()
    {
        return get_theme_mod('site_logo') ?? null;
    }
}
