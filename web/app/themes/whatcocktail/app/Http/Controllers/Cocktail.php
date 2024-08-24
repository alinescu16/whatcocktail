<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use GuzzleHttp\Exception\RequestException;

class Cocktail extends Controller
{
    // Create 
    public function create_cocktail($form_data) 
    {   
        global $wpdb;

        $data = array(
            'name' => $form_data['name'],
            'description' => isset($form_data['description']) ? $form_data['description'] : null,
            'main_category' => isset($form_data['main_category']) ? $form_data['main_category'] : null,
            'sub_category' => isset($form_data['sub_category']) ? $form_data['sub_category'] : null,
            'type' => isset($form_data['type']) ? $form_data['type'] : null,
            'season' => isset($form_data['season']) ? $form_data['season'] : null,
            'serving_type' => isset($form_data['serving_type']) ? $form_data['serving_type'] : null,
            'recipe' => isset($form_data['recipe']) ? json_encode($form_data['recipe']) : null,
            'ingredients' => isset($form_data['ingredients']) ? json_encode($form_data['ingredients']) : null,
            'preparation_instructions' => isset($form_data['preparation_instructions']) ? $form_data['preparation_instructions'] : null,
            'preparation_duration' => isset($form_data['preparation_duration']) ? $form_data['preparation_duration'] : null,
            'created_at' => current_time('mysql'), 
            'updated_at' => current_time('mysql')  
        );

        $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

        $wpdb->insert($wpdb->prefix . 'cocktails', $data, $format);

        return $wpdb->insert_id;
    }

    // Read 
    public function get_cocktail($id) 
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}cocktails WHERE id = %d", $id);
        $result = $wpdb->get_row($query);

        // Decode JSON fields
        if ($result) {
            $result->recipe = json_decode($result->recipe, true);
            $result->ingredients = json_decode($result->ingredients, true);
        }

        return $result;
    }

    // Update
    public function update_cocktail($id, $form_data) 
    {
        global $wpdb;

        $data = array(
            'name' => $form_data['name'],
            'description' => isset($form_data['description']) ? $form_data['description'] : null,
            'main_category' => isset($form_data['main_category']) ? $form_data['main_category'] : null,
            'sub_category' => isset($form_data['sub_category']) ? $form_data['sub_category'] : null,
            'type' => isset($form_data['type']) ? $form_data['type'] : null,
            'season' => isset($form_data['season']) ? $form_data['season'] : null,
            'serving_type' => isset($form_data['serving_type']) ? $form_data['serving_type'] : null,
            'recipe' => isset($form_data['recipe']) ? json_encode($form_data['recipe']) : null,
            'ingredients' => isset($form_data['ingredients']) ? json_encode($form_data['ingredients']) : null,
            'preparation_instructions' => isset($form_data['preparation_instructions']) ? $form_data['preparation_instructions'] : null,
            'preparation_duration' => isset($form_data['preparation_duration']) ? $form_data['preparation_duration'] : null,
            'updated_at' => current_time('mysql')  
        );

        $where = array('id' => $id);
        $format = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
        $where_format = array('%d');

        $wpdb->update($wpdb->prefix . 'cocktails', $data, $where, $format, $where_format);

        return $wpdb->rows_affected;
    }

    // Delete
    public function delete_cocktail($id) 
    {
        global $wpdb;

        $wpdb->delete($wpdb->prefix . 'cocktails', array('id' => $id), array('%d'));

        return $wpdb->rows_affected;
    }

    public function save_cocktail($cocktail)
    {
        // User can not be identified, we can not save the cocktail for the user so we return an error
        // TODO:: Change to WP_ERR
        if ( !isset( $_COOKIE['user_identifier'] ) ) {
            return json_encode(array(
                'success' => false,
                'code' => 500,
                'message' => 'User can not be identified.'
            ));
        }

        // We try to get the cocktail id
        if (is_array($cocktail) && isset($cocktail['name'])) {
            $cocktail_id = $this->get_cocktail_id_by_name($cocktail['name']);
        } else if (is_string($cocktail)) {
            $cocktail_id = $this->get_cocktail_id_by_name($cocktail);
        } 

        // If we don't have an array of values and we don't have a cocktail id, we return a message as we can not create a cocktail
        // TODO:: Change to WP_ERR
        if (!$cocktail_id && is_string($cocktail)) {
            return json_encode(array(
                'success' => false,
                'code' => 500,
                'message' => 'Cocktail can not be found or created.'
            ));
        }

        // If we have an array of values and we don't have a cocktail id, we try to create it
        if (!$cocktail_id && is_array($cocktail)) {
            $cocktail_id = $this->create_cocktail($cocktail);
        }

        // Still check if we have the cookie with the user uuid
        if ( isset( $_COOKIE['user_identifier'] ) ) {
            $user_id = $_COOKIE('user_identifier');
        }

        global $wpdb;

        $data = array(
            'user_id' => $user_id,
            'cocktail_id' => $cocktail_id,
            'created_at' => current_time('mysql'), 
            'updated_at' => current_time('mysql')  
        );

        $format = array('%d', '%d');

        $wpdb->insert($wpdb->prefix . 'user_saved_cocktails', $data, $format);

        return json_encode(array(
            'success' => true,
            'code' => 200,
            'data' => $wpdb->insert_id
        ));
    }

    public function get_cocktail_recipe($cocktail) {
        // TODO:: 
    }

    private function get_cocktail_id_by_name($name)
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}cocktails WHERE name = %s", $name);
        $result = $wpdb->get_row($query);

        if ($result) {
            return $result->id;
        }

        // TODO
            // Query API
            // Create
            // Return ID
    }

    /**
     * Hit the API for a cocktail or a list of cocktails by name or by an ingredient
     * 
     * @param       $request : \WP_REST_Request
     * 
     * @return      WP_REST_Response
     */
    public static function get_cocktail_from_api(\WP_REST_Request $request) : \WP_REST_Response
    {
        $client = app('http.client');

        $form_data = $request->get_json_params();
        
        $query = '';

        if (isset($form_data['name'])) {
            $query = $form_data['name'];
            
            try {
                
                $response = $client->request('GET', 'cocktailname', array(
                    'query' => array( 'name' => $query )
                ));

                if ($response->getStatusCode() == 200) {
                    $cocktail_data = json_decode($response->getBody(), true);

                    if (isset($cocktail_data['data']) && isset($cocktail_data['data']['cocktails'])) {
                        return new \WP_REST_Response( array(
                            'status' => $response->getStatusCode(),
                            'data' => $cocktail_data['data']['cocktails']
                        ), 200);
                    }
                }

            } catch (RequestException $e) {
                error_log("Getting Cocktail by Name error: {$e->getMessage()}");

                return new \WP_REST_Response( array(
                    'success' => false,
                    'message' => "API Unavailable."
                ), 500);
            } catch (Exception $e) {
                error_log("Getting Cocktail by Name error: {$e->getMessage()}");
            }
        }

        if (isset($form_data['ingredients'])) {
            $query = $form_data['ingredients'];

            try {

                $response = $client->request('GET', 'cocktailingredient', array(
                    'query' => array( 'ingredient' => $query )
                ));

                if ($response->getStatusCode() == 200) {
                    $cocktail_data = json_decode($response->getBody(), true);

                    if (isset($cocktail_data['data']) && isset($cocktail_data['data']['cocktails'])) {
                        return new \WP_REST_Response( array(
                            'status' => $response->getStatusCode(),
                            'data' => $cocktail_data['data']['cocktails']
                        ), 200);
                    }
                }

            } catch (RequestException $e) {
                error_log("Getting Cocktail by Name error: {$e->getMessage()}");

                return new \WP_REST_Response( array(
                    'success' => false,
                    'message' => "API Unavailable."
                ), 500);
            } catch (Exception $e) {
                error_log("Getting Cocktail by Name error: {$e->getMessage()}");
            }
        }

        return new \WP_REST_Response( array(
            'success' => false,
            'message' => "Could not find any cocktails based on your search for {$query}."
        ), 500);
    }

}