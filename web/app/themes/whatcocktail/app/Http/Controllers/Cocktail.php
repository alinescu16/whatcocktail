<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use GuzzleHttp\Exception\RequestException;

class Cocktail extends Controller
{
    private $cocktails = 0;

    // Create 
    public function create_cocktail(array $form_data) : int
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
    public function get_cocktail(int $id) : stdClass | array
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
    public function update_cocktail(int $id, array $form_data) : int
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
    public function delete_cocktail(int $id) : int
    {
        global $wpdb;

        $wpdb->delete($wpdb->prefix . 'cocktails', array('id' => $id), array('%d'));

        return $wpdb->rows_affected;
    }

    /**
     * Save the cocktail to a user's session.
     * 
     * Try to find the Cocktail ID, try to get the user identifier and it's ID, then use the pivot table to link them.
     * 
     * @param       $name : string | array
     * 
     * @return      WP_REST_Response
     */
    public function save_cocktail(string | array $cocktail) : \WP_REST_Response
    {
        // User can not be identified, we can not save the cocktail for the user so we return an error
        if ( !isset( $_COOKIE['user_identifier'] ) ) {
            return new \WP_REST_Response( array(
                'success' => false,
                'message' => "User can not be identified."
            ), 500);
        }

        // We try to get the cocktail id
        if (is_array($cocktail) && isset($cocktail['name'])) {
            $cocktail_id = $this->get_cocktail_id_by_name($cocktail['name']);
        } else if (is_string($cocktail)) {
            $cocktail_id = $this->get_cocktail_id_by_name($cocktail);
        } 

        // If we don't have an array of values and we don't have a cocktail id, we return a message that we can not create and save a cocktail
        if (!$cocktail_id && is_string($cocktail)) {
            return new \WP_REST_Response( array(
                'success' => false,
                'message' => "Cocktail can not be found or created, thus not marked as saved."
            ), 500);
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

        return new \WP_REST_Response( array(
            'success' => true,
            'data' => $wpdb->insert_id
        ), 200);
    }

    /**
     * Get Cocktail's Ingredients.
     * 
     * Given a cocktail name, it queries it and returns the ingredients.
     * Given a cocktail data array, it extracts the ingredients if they are present or, it queries it and returns the ingredients.
     * 
     * @param       $cocktail : int | string | array
     * 
     * @return      \WP_REST_Response
     */
    public function get_cocktail_ingredients(int | string | array $cocktail) : \WP_REST_Response
    {

        $ingredients = array();

        if (is_int($cocktail)) {
            try {
                $ingredients = $this->get_cocktail($cocktail)->ingredients;    
            } catch (\Exception $e) {
                return new \WP_REST_Response( array(
                    'success' => false,
                    'message' => $e->getMessage()
                ), 500);
            }
        }

        if (is_string($cocktail)) {
            try {
                $ingredients = $this->get_cocktail($this->get_cocktail_id_by_name($cocktail))->ingredients;    
            } catch (\Exception $e) {
                return new \WP_REST_Response( array(
                    'success' => false,
                    'message' => $e->getMessage()
                ), 500);
            }
        }

        if (is_array($cocktail)) {
            if (isset($cocktail['ingredients'])) {
                $ingredients = $cocktail['ingredients'];
            }

            if (isset($cocktail['id'])) {
                try {
                    $ingredients = $this->get_cocktail($cocktail['id'])->ingredients;
                } catch (\Exception $e) {
                    return new \WP_REST_Response( array(
                        'success' => false,
                        'message' => $e->getMessage()
                    ), 500);
                }
            }
        }

        if (!empty($ingredients)) {
            return new \WP_REST_Response( array(
                'success' => true,
                'data' => $ingredients
            ), 200);
        }

        return new \WP_REST_Response( array(
            'success' => false,
            'message' => "Server error."
        ), 500);
    }

    /**
     * Query the Cocktail ID from the database. If the cocktail can not be found, create it and return the inserted ID. If fail, return 0.
     * 
     * @param       $name : string
     * 
     * @return      int
     */
    private function get_cocktail_id_by_name(string $name) : int
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}cocktails WHERE name = %s", $name);
        $result = $wpdb->get_row($query);

        if ($result) {
            return $result->id;
        }

        // Query API
        $request = new \WP_REST_Request();

        $request->set_body(json_encode(array(
            "name" => $name
        )));

        $response = $this->get_cocktail_from_api($request);

        if ( !$response->is_error() ) {
            // Create
            $cocktail_data = json_decode($response);

            if (isset($cocktail_data['data']) && isset($cocktail_data['data']['cocktails'])) {
                return $this->create_cocktail($cocktail_data['data']['cocktails'][0]);
            }
        }

        return 0;

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

        // Get Cocktail by Name
        if (isset($form_data['name'])) {
            $query = $form_data['name'];
            
            try {
                $response = $client->request('GET', 'cocktailname', array(
                    'query' => array( 'name' => $query )
                ));

                if ($response->getStatusCode() == 200) {
                    $cocktail_data = json_decode($response->getBody(), true);

                    if (isset($cocktail_data['data']) && isset($cocktail_data['data']['cocktails'])) {
                        $cocktail_data = $cocktail_data['data']['cocktails'][0];

                        /**
                         * Get the cocktail's number of ingredients and estimated preparation time based on it.
                         */
                        if (isset($cocktail_data['ingredients'])) {
                            $cocktail_data['ingredients_count'] = 0;

                            foreach($cocktail_data['ingredients'] as $ingredient) {
                                $cocktail_data['ingredients_count'] += isset($ingredient['ingredient']) ? 1 : 0;
                            }

                            $cocktail_data['estimated_preparation_time'] = $this->estimate_cocktail_preparation_time($cocktail_data['ingredients_count']);
                        }
                        
                        /**
                         * Get the cocktail image 
                         */
                        if (isset($cocktail_data['name'])) {
                            $cocktail_data['image'] = $this->get_cocktail_image($cocktail_data['name']);
                        }

                        return new \WP_REST_Response( array(
                            'status' => $response->getStatusCode(),
                            'data' => $cocktail_data
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

        // Get Cocktail by Ingredients
        if (isset($form_data['ingredients'])) {
            $query = $form_data['ingredients'];

            try {

                $response = $client->request('GET', 'cocktailingredient', array(
                    'query' => array( 'ingredient' => $query )
                ));

                if ($response->getStatusCode() == 200) {
                    $cocktail_data = json_decode($response->getBody(), true);

                    if (isset($cocktail_data['data']) && isset($cocktail_data['data']['cocktails'])) {
                        $cocktail_data = $cocktail_data['data']['cocktails'][0];

                        /**
                         * Get the cocktail's number of ingredients and estimated preparation time based on it.
                         */
                        if (isset($cocktail_data['ingredients'])) {
                            $cocktail_data['ingredients_count'] = 0;

                            foreach($cocktail_data['ingredients'] as $ingredient) {
                                $cocktail_data['ingredients_count'] += isset($ingredient['ingredient']) ? 1 : 0;
                            }

                            $cocktail_data['estimated_preparation_time'] = $this->estimate_cocktail_preparation_time($cocktail_data['ingredients_count']);
                        }
                        
                        /**
                         * Get the cocktail image 
                         */
                        if (isset($cocktail_data['name'])) {
                            $cocktail_data['image'] = $this->get_cocktail_image($cocktail_data['name']);
                        }

                        return new \WP_REST_Response( array(
                            'status' => $response->getStatusCode(),
                            'data' => $cocktail_data
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

    /**
     * Cocktail details getter function
     *
     * @return array
     */
    public static function get_random_cocktails() : array
    {
        $client = app('http.client');

        $cocktails = array();

        while ($this->cocktails < 3) {
            try {
                $response = $client->get('randomcocktail');

                if ($response->getStatusCode() == 200) {
                    $cocktail_data = json_decode($response->getBody(), true);

                    if (isset($cocktail_data['data']) && isset($cocktail_data['data']['cocktails'])) {
                        $cocktail_data = $cocktail_data['data']['cocktails'][0];

                        /**
                         * Get the cocktail's number of ingredients and estimated preparation time based on it.
                         */
                        if (isset($cocktail_data['ingredients'])) {
                            $cocktail_data['ingredients_count'] = 0;

                            foreach($cocktail_data['ingredients'] as $ingredient) {
                                $cocktail_data['ingredients_count'] += isset($ingredient['ingredient']) ? 1 : 0;
                            }

                            $cocktail_data['estimated_preparation_time'] = $this->estimate_cocktail_preparation_time($cocktail_data['ingredients_count']);
                        }
                        
                        /**
                         * Get the cocktail image 
                         */
                        if (isset($cocktail_data['name'])) {
                            $cocktail_data['image'] = $this->get_cocktail_image($cocktail_data['name']);
                        }

                        $cocktails[] = $cocktail_data;
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

    /**
     * Estimate the cocktail preparation time based on the number of ingredients.
     * 
     * @param $ingredients_count : int
     *
     * @return array
     */
    private function estimate_cocktail_preparation_time($ingredients_count) : array
    {   
        // Early return if we don't have enaugh ingredients
        if ((int) $ingredients_count <= 10) {
            return array(10, 15);
        }

        $estimated_time = floor($ingredients_count / 5) * 5; 
        
        return array($estimated_time - 5, $estimated_time + 5);
    }

    /**
     * Get cocktail's image, and upload it to WordPress if it isn't already.
     * 
     * @param $name : string
     *
     * @return array 
     */
    private function get_cocktail_image($name) : array 
    {
        $image_url = '';
        $image = $this->get_image_from_app($name);

        // Early return if we have an image for that cocktial
        if ($image) {
            return $image;
        }

        $image_client = app('http.image.client');

        $images = json_decode($images_client->get_json(array(
            "engine" => "google_images",
            "q" => $name,
        )));

        // Early return if the call didn't returned a success status
        if ($images['search_metadata']['status'] != 'Success') {
            return array(
                'status' => false,
                'message' => 'API not available.'
            );
        }

        foreach($images['images_results'] as $image) {
            $image_url = $image['original'];

            $path_info = pathinfo($image_url);

            if (isset($path_info['extension']) && in_array(strtolower($path_info['extension']), ['jpg', 'jpeg', 'png'])) {
                break;
            }
        }

        try {
            $temp_file = download_url($image_url);

            if (is_wp_error($temp_file)) {
                throw new \Exception($temp_file->get_error_message());
            }

            $file_array = array(
                'name' => basename($image_url),
                'tmp_name' => $temp_file
            );

            $attachment_id = media_handle_sideload($file_array, 0);

            if (is_wp_error($attachment_id)) {
                throw new \Exception($attachment_id->get_error_message());
            }

            update_post_meta( $attachment_id, 'cocktail_name', $name );           

            return array(
                'status' => true,
                'image' => wp_get_attachment_url($attachment_id),
                'thumbnail' => wp_get_attachment_image_src($attachment_id, 'medium')
            );
        } catch (\Exception $e) {
            error_log("Image upload error: " . $e->getMessage());

            return array(
                'status' => false,
                'message' => "Exception while uploading Cocktail\'s image: {$e->getMessage()}."
            );
        } finally {
            @unlink($temp_file);
        }
    }

    /**
     * Try to get cocktail's image from our app.
     * 
     * @return array | bool
     */
    private function get_image_from_app($name) : array | bool
    {
        $args = array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'meta_query' => array(
            array(
                'key' => 'cocktail_name',
                'value' => $name,
                'compare' => '='
            ))
        );  

        $query = new WP_Query( $args );

        // Early return if we don't have posts
        if ( !$query->have_posts() ) {
            return false;
        }

        while ( $query->have_posts() ) {
            $query->the_post();

            return array(
                'status' => true,
                'image' => wp_get_attachment_url( $attachment_id ),
                'thumbnail' => wp_get_attachment_image_src($attachment_id, 'medium')
            );
        }

        wp_reset_postdata();    
    }
}