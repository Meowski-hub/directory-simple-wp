<?php

class Directory_Simple_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name . '-admin', 
            DIRECTORY_SIMPLE_PLUGIN_URL . 'admin/css/directory-simple-admin.css', 
            array(), 
            $this->version, 
            'all'
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name . '-admin',
            DIRECTORY_SIMPLE_PLUGIN_URL . 'admin/js/directory-simple-admin.js',
            array('jquery'),
            $this->version,
            false
        );

        wp_localize_script($this->plugin_name . '-admin', 'directorySimple', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('directory_simple_nonce')
        ));
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'Directory Simple', 
            'Directory Simple', 
            'manage_options', 
            $this->plugin_name, 
            array($this, 'display_plugin_admin_page'),
            'dashicons-grid-view',
            20
        );
    }

    public function display_plugin_admin_page() {
        include_once DIRECTORY_SIMPLE_PLUGIN_DIR . 'admin/partials/directory-simple-admin-display.php';
    }

    public function save_directory_entry() {
        check_ajax_referer('directory_simple_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'directory_entries';

        $data = array(
            'name' => sanitize_text_field($_POST['name']),
            'description' => sanitize_textarea_field($_POST['description']),
            'contact_info' => sanitize_textarea_field($_POST['contact_info']),
            'category' => sanitize_text_field($_POST['category'])
        );

        $wpdb->insert($table_name, $data);
        wp_send_json_success('Entry saved successfully');
    }

    public function handle_csv_import() {
        check_ajax_referer('directory_simple_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        if (!isset($_FILES['csv_file'])) {
            wp_send_json_error('No file uploaded');
        }

        $file = $_FILES['csv_file'];
        $csv_data = array_map('str_getcsv', file($file['tmp_name']));
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'directory_entries';

        foreach ($csv_data as $row) {
            if (count($row) >= 4) {
                $data = array(
                    'name' => sanitize_text_field($row[0]),
                    'description' => sanitize_textarea_field($row[1]),
                    'contact_info' => sanitize_textarea_field($row[2]),
                    'category' => sanitize_text_field($row[3])
                );
                $wpdb->insert($table_name, $data);
            }
        }

        wp_send_json_success('CSV import completed');
    }
}