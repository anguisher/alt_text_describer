<?php
    class Image_List_Table extends WP_List_Table {
        private $images_list;
        function __construct($images_list) {
            $this->images_list = $images_list;
            parent::__construct(array(
                'singular' => 'Image',
                'plural' => 'Images',
                'ajax' => false
            ));
        }

        function get_columns() {
            return array(
                'image_id' => 'Image ID',
                'image_url' => 'Image URL',
                'alt_text' => 'Alt Text'
            );
        }
        function column_default($item, $column_name) {
            return isset($item[$column_name]) ? $item[$column_name] : '';
        }
        function column_cb($item)
        {
            return sprintf(
                    '<input type="checkbox" name="element[]" value="%s" />',
                    $item['id']
            );
        }
        function prepare_items() {
            $per_page = 10;
            $current_page = $this->get_pagenum();
            $total_items = count($this->images_list);
            $data = array_slice($this->images_list, (($current_page - 1) * $per_page), $per_page);
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = array();
            $primary  = 'name';
            $this->items = $data;
            $this->_column_headers = array($columns, $hidden, $sortable, $primary);
            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page' => $per_page,
            ));
        }
    }