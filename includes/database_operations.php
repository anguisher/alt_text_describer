<?php

class database_operations {
    private $table_name;
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'describer_descriptions';
    }
    public function create_descriptions_table() {
        global $wpdb;
        if ($wpdb->get_var("SHOW TABLES LIKE '$this->table_name'") != $this->table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $this->table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                image_id bigint(20) UNSIGNED NOT NULL,
                image_url varchar(255) NOT NULL,
                alt_text text NOT NULL,
                PRIMARY KEY  (id),
                UNIQUE KEY image_id (image_id)
            ) $charset_collate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
    public function save_description($image_id, $image_url, $alt_text) {
        global $wpdb;
        $data = array(
            'image_id' => $image_id,
            'image_url' => $image_url,
            'alt_text' => $alt_text
        );
        $format = array(
            '%d',
            '%s',
            '%s'
        );
        
        $wpdb->insert($this->table_name, $data, $format);
    }
    public function get_descriptions() {
        global $wpdb;
        $descriptions = $wpdb->get_results("SELECT * FROM $this->table_name", ARRAY_A);
        return $descriptions;
    }
    public function get_description($image_id) {
        global $wpdb;
        $description = $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE image_id = %d", $image_id), ARRAY_A);
        return $description;
    }
    
}