<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Cocktail;

class ShoppingList extends Controller
{
    /**
     * Format the ingredients into a list of non-duplicates with total quantity and amount for each ingredient
     * 
     * @param       $cocktails : array
     * 
     * @return      WP_REST_RESPONSE
     */
    public function get_total_ingredients(array $cocktails) : \WP_REST_RESPONSE
    {   
        $ingredients = array();
        $grouped_ingredients = array();

        try {
            foreach ($cocktails as $key => $cocktail) {
                array_push($ingredients, Cocktail->get_cocktail_ingredients($cocktail);
            }

            $grouped_ingredients = array_reduce($ingredients, function ($result, $ingredient) {
                $name = $ingredient['name'];
                
                if (!isset($result[$name])) {
                    $result[$name] = array(
                        'unit' => $ingredient['unit'], 
                        'amount' => 0, 
                        'name' => $name, 
                        'quantity' => 0
                    ); 
                }
                
                $result[$name]['amount'] += $ingredient['amount'];
                $result[$name]['quantity'] += 1;

                return $result;
            }, []);
        } catch (Exception $e) {
            return new WP_REST_RESPONSE( array(
                'success' => false,
                'message' => $e->getMessage()
            ), 500);
        }
        
        return new WP_REST_RESPONSE( array(
            'success' => true,
            'data' => $grouped_ingredients
        ), 200);
    }
}
