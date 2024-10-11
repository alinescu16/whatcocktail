<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

use App\Http\Controllers\Cocktail;

use GuzzleHttp\Exception\ClientException;


class PopularCocktails extends Composer
{


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
    public function with():  \stdClass | array
    {   

        // return Cocktail->get_random_cocktails();
        
        return array (
            'cocktails' => json_decode('[{"name":"Long Island Iced Tea","glass":"highball","category":"Longdrink","ingredients":[{"unit":"cl","amount":1.5,"ingredient":"Tequila"},{"unit":"cl","amount":1.5,"ingredient":"Vodka"},{"unit":"cl","amount":1.5,"ingredient":"White rum"},{"unit":"cl","amount":1.5,"ingredient":"Triple Sec"},{"unit":"cl","amount":1.5,"ingredient":"Gin"},{"unit":"cl","amount":2.5,"ingredient":"Lemon juice"},{"unit":"cl","amount":3,"ingredient":"Syrup","label":"Gomme syrup"},{"special":"1 dash of Cola"}],"garnish":"Lemon twist","preparation":"Add all ingredients into highball glass filled with ice. Stir gently. Serve with straw."},{"name":"Stinger","glass":"martini","category":"After Dinner Cocktail","ingredients":[{"unit":"cl","amount":5,"ingredient":"Cognac"},{"unit":"cl","amount":2,"ingredient":"Cr\u00e9me liqueur","label":"White Cr\u00e9me de Menthe"}],"preparation":"Stir in mixing glass with ice cubes. Strain into a cocktail glass."},{"name":"Tequila Sunrise","glass":"highball","category":"Longdrink","ingredients":[{"unit":"cl","amount":4.5,"ingredient":"Tequila"},{"unit":"cl","amount":9,"ingredient":"Orange juice"},{"unit":"cl","amount":1.5,"ingredient":"Syrup","label":"Grenadine"}],"garnish":"Orange slice and a cherry","preparation":"Build tequila and orange juice into highball with ice cubes. Add a splash of grenadine to create sunrise effect. Do not stir."}]'),
        );   
    }
}
