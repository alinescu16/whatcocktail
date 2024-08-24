<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

use App\Http\Controllers\Cocktail;

use GuzzleHttp\Exception\ClientException;


class PopularCocktails extends Composer
{

    private $cocktails = 0;

    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'sections.popular'
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return array (
            'cocktails' => $this->getRandomCocktail(),
        );   
    }

    /**
     * Cocktail details getter function
     *
     * @return array
     */
    private function getRandomCocktail()
    {
        $client = app('http.client');

        $cocktails = array();

        while ($this->cocktails < 3) {
            try {
                $response = $client->get('randomcocktail');

                if ($response->getStatusCode() == 200) {
                    $cocktail_data = json_decode($response->getBody(), true);

                    if (isset($cocktail_data['data']) && isset($cocktail_data['data']['cocktails'])) {
                        $cocktails[] = $cocktail_data['data']['cocktails'];
                    }
                }
            } catch (ClientException $e) {
                error_log("Getting Random Cocktail error: {$e->getMessage()}");

                return new \WP_REST_Response( array(
                    'success' => false,
                    'message' => "API Unavailable."
                ), 500);    
            } catch (Exception $e) {
                error_log("Error while getting random cocktails: {$e->getMessage()}");
            }

            $this->cocktails++;
        }

        return $cocktails;
    }
}
