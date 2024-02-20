<?php

class image_operations {
    private $images;
    public function __construct(){
        $this->images = get_posts(array(
            'post_type' => 'attachment',
            'posts_per_page' => -1,
            'post_status' => 'any',
        ));
    }
    public function get_list_without_alts(){
        $empty_images = [];
        foreach ($this->images as $image) {
            $alt_text = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
            if (empty($alt_text)) {
                $this->extract_image_info($image, $empty_images);
            }
        }
        return $empty_images;
    }
    public function get_all_images_list() {
        $all_images = [];
        foreach ($this->images as $image) {
            $this->extract_image_info($image, $all_images);
        }
        return $all_images;
    }
    private function extract_image_info($image, &$image_array){
        $image_info = [];
        $image_info['id'] = $image->ID;
        $image_info['url'] = wp_get_attachment_url($image->ID);
        $image_info['alt'] = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
        $image_array[] = $image_info;
    }
}