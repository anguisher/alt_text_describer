<?php

class requests_operations{
    public function __construct(){

    }
    public function makeApiRequest($url, $data, $timeout = 45) {
        $api_url = 'https://api.prisakaru.lt/' . $url;
        $response = wp_remote_post($api_url, array(
            'body' => wp_json_encode($data),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'timeout' => $timeout,
        ));
        return is_wp_error($response) ? array('error' => $response->get_error_message()) : $response;
    }
    public function getDescription($image_url, $language){
        $data = [
            'image_url' => $image_url,
            'api_key' => get_option('describer_api_key'),
            'language' => $language
        ];
        return $this->makeApiRequest('request_description', $data);
    }
    public function generate_alt_for_images($language = "English"){
        $this->make_images_request($language, false);
    }
    public function generate_alt_for_all_images($language = "English") {
        $this->make_images_request($language, true);
    } 
    public function proccess_image_response($response, $image, $database_operations, $is_ajax = true){
        if($response['status'] == "error" && $is_ajax){
            if(($response['type'] == "no_credits" || $response['type'] == "no_key" || $response['type'] == "wrong_key")){
                wp_send_json($response);
            }
            $alt = $response['content'];
            $database_operations->save_description($image['id'], $image['url'], $alt);
            update_post_meta($image['id'], '_wp_attachment_image_alt', $alt);
        }
        elseif($response['status'] == "success"){
            update_post_meta($image['id'], '_wp_attachment_image_alt', $response['content']);
            $database_operations->save_description($image['id'], $image['url'], $response['content']);
        }
    }
    public function make_single_request($image_url, $image_id, $language, $is_ajax = false){
        $database_operations = new database_operations();
        $image = [];
        $image['url'] = $image_url;
        $image['id'] = $image_id;
        $response = json_decode($this->getDescription($image['url'], $language)['body'], true);
        $this->proccess_image_response($response, $image, $database_operations, $is_ajax);
    }
    public function make_images_request($language, $all_images = true){
        $image_operations = new image_operations();
        $database_operations = new database_operations();
        $images = $all_images ? $image_operations->get_all_images_list() : $image_operations->get_list_without_alts();
        $totalImages = count($images);
        $processedImages = 0;
        $image = reset($images);
        $response = "";
        if ($image !== false) {
            $response = json_decode($this->getDescription($image['url'], $language)['body'], true);
            $this->proccess_image_response($response, $image, $database_operations);
            $processedImages++;
        }
        wp_send_json(array(
            'status' => 'success',
            'total' => $totalImages,
            'processed' => $processedImages,
            'response' => $response
        ));
    }
    function get_user_credits_by_api_key($api_key){
        $data = ['api_key' => $api_key];
        return json_decode($this->makeApiRequest('get_user_info', $api_key)['body'])->credits_left;
    }
}