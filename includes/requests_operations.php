<?php

class requests_operations{
    public function __construct(){

    }
    public function make_credits_curl($api_key){
        $api_url = 'https://api.prisakaru.lt/get_user_info';
        $data = json_encode(array(
            'api_key' => $api_key
        ));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if($response === false) {
            $error_message = curl_error($ch);
        }
        curl_close($ch);
        $response_data = json_decode($response, true);
        return $response;
    }
    public function make_curl($image_url, $language) {
        $api_url = 'https://api.prisakaru.lt/request_description';
        $data = json_encode(array(
            'image_url' => $image_url,
            'api_key' => get_option('describer_api_key'),
            'language' => $language
        ));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if($response === false) {
            $error_message = curl_error($ch);
        }
        curl_close($ch);
        $response_data = json_decode($response, true);
        return $response;
    }
    //Generates for images without alt
    public function generate_alt_for_images($language = "English"){
        $this->make_images_request(false, $language);
    }
    //Generates for all images
    public function generate_alt_for_all_images($language = "English") {
        $this->make_images_request(true, $language);
    } 
    public function proccess_image_response($response, $image, $database_operations){
        if($response['status'] == "error"){
            if($response['type'] == "no_credits" || $response['type'] == "no_key" || $response['type'] == "wrong_key")
                wp_send_json($response);
            if($response['type'] == "description_error")
                $database_operations->save_description($image['id'], $image['url'], $response['content']);
        }
        elseif($response['status'] == "success"){
            update_post_meta($image['id'], '_wp_attachment_image_alt', $response['content']);
            $database_operations->save_description($image['id'], $image['url'], $response['content']);
        }
    }
    public function make_images_request($all_images = true, $language){
        $image_operations = new image_operations();
        $database_operations = new database_operations();
        $images = $all_images ? $image_operations->get_all_images_list() : $image_operations->get_list_without_alts();
        $images_to_process = array_filter($images, function($image) use ($database_operations) {
            return $database_operations->get_description($image['id']) === null;
        });
        $totalImages = count($images_to_process);
        $processedImages = 0;
        $image = reset($images_to_process);
        if ($image !== false) {
            $response = json_decode($this->make_curl($image['url'], $language), true);
            $this->proccess_image_response($response, $image, $database_operations);
            $processedImages++;
        }
        wp_send_json(array(
            'status' => 'success',
            'total' => $totalImages,
            'processed' => $processedImages
        ));
    }
    function get_user_credits_by_api_key($api_key){
        $data = ['api_key' => $api_key];
        return json_decode($this->make_credits_curl($api_key))->credits_left;
    }
    function get_user_info_from_api($user_id) {
        $api_key = get_user_meta($user_id, 'alt_describer_api_key', true);
        if($api_key == null) return;
        $data = ['api_key' => $api_key];
        $this->make_request('get_user_info', $data,
            function($json_response) use ($user_id){
                update_user_meta($user_id, 'describer_api_credits_left', $json_response->credits_left);
                return $json_response;
            },
            function ($error_message) {
                error_log($error_message);
            }
        );
    }
    function make_request($url, $data, $success_callback, $error_callback) {
        $response = wp_remote_post('https://api.prisakaru.lt/'.$url, array(
            'body' => json_encode($data),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
        ));
        if (is_wp_error($response)) {
            $error_callback($response->get_error_message());
        } else {
            $json_response = json_decode(wp_remote_retrieve_body($response));
            $success_callback($json_response);
        }
    }
    function remove_user_from_api($user_id){
        $api_key = get_user_meta($user_id, 'alt_describer_api_key', true);
        $data = ['api_key' => $api_key];
        $this->make_request('remove_user', $data,
            function($json_response){
            },
            function ($error_message) {
                error_log('Error while removing user from API: ' . $error_message);
            }
        );
    }
    function send_user_data_to_alt_describer_api($user_id) {
        $user = get_userdata($user_id);
        $data = array(
            'username' => $user->user_login,
            'email' => $user->user_email,
            'cms' => 'wp',
            'origin_url' => 'https://prisakaru.lt'
        );
        $this->make_request('register_user', $data,
            function ($json_response) use ($user_id) {
                update_user_meta($user_id, 'alt_describer_api_key', $json_response->api_key);
            },
            function ($error_message) {
                error_log('Error sending user data to API: ' . $error_message);
            }
        );
    }
}