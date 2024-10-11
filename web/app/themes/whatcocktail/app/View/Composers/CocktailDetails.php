<?php
namespace App\View\Composers;

use Roots\Acorn\View\Composer;

use App\Http\Controllers\Cocktail;

use GuzzleHttp\Exception\RequestException; 

class CocktailDetails extends Composer
{
	private $cocktail_name;

	private $cocktail;

	/**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'cocktail-details'
    ];


	public function __construct()
	{
		$this->cocktail_name = get_query_var('cocktail');
		$this->cocktail = $this->get_cocktail();
	}

	/**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return array (
        	'cocktail' => $this->cocktail
        );
    }

	private function get_cocktail() : \stdClass | \WP_REST_Response
	{
		$form_data = [
            'name' => $this->cocktail_name
        ];

        $request = new \WP_REST_Request('GET', '');
		$request->set_header( 'content-type', 'application/json' );		
        $request->set_body(json_encode($form_data));

        try {
	        $response = Cocktail::get_cocktail_from_api($request); 

	        if ($response->is_error()) {
	            return new \WP_REST_Response(array(
                    'success' => false,
                    'message' => $response->get_data()['message']
                ), $response->get_status());
	        } else {
	        	return json_decode('{"status":200,"data":[{"name":"Stinger","glass":"martini","category":"After Dinner Cocktail","ingredients":[{"unit":"cl","amount":5,"ingredient":"Cognac"},{"unit":"cl","amount":2,"ingredient":"Cr\u00e9me liqueur","label":"White Cr\u00e9me de Menthe"}],"preparation":"Stir in mixing glass with ice cubes. Strain into a cocktail glass."}]}')->data[0];
	            
	            // 	$response = $response->get_data();
	        	// 	if (isset($response['data']) && isset($response['status']) && $response['status'] == 200) {
	        	// 		return $response['data'][0];
	        	// 	}

	        }
	    } catch (RequestException $e) {
	            error_log("API Request Error: {$e->getMessage()}");
            return new \WP_REST_Response(array(
                'success' => false,
                'message' => "API Unavailable." 
            ), 500); 
        } catch (Exception $e) {
            error_log("Unexpected Error: {$e->getMessage()}");
            return new \WP_REST_Response(array(
                'success' => false,
                'message' => "An error occurred." 
            ), 500); 
        }
	}

	public function save_cocktail_to_db() : \WP_REST_Response 
	{

	}

	public function save_cocktail_to_favorites() : \WP_REST_Response 
	{

	}

	public function save_cocktail_recipe() : blob | \WP_REST_Response
	{

	}
}