<?php

class Directory_Simple_Public {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name,
            DIRECTORY_SIMPLE_PLUGIN_URL . 'public/css/directory-simple-public.css',
            array(),
            $this->version,
            'all'
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name,
            DIRECTORY_SIMPLE_PLUGIN_URL . 'public/js/directory-simple-public.js',
            array('jquery'),
            $this->version,
            false
        );

        wp_localize_script($this->plugin_name, 'directorySimple', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('directory_simple_public_nonce')
        ));
    }

    public function display_directory($atts) {
        // Extract shortcode attributes
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => -1,
            'orderby' => 'name',
            'order' => 'ASC'
        ), $atts, 'directory_simple');

        global $wpdb;
        $table_name = $wpdb->prefix . 'directory_entries';

        // Build query
        $query = "SELECT * FROM $table_name";
        if (!empty($atts['category'])) {
            $query .= $wpdb->prepare(" WHERE category = %s", $atts['category']);
        }
        $query .= " ORDER BY " . esc_sql($atts['orderby']) . " " . esc_sql($atts['order']);
        if ($atts['limit'] > 0) {
            $query .= $wpdb->prepare(" LIMIT %d", $atts['limit']);
        }

        $entries = $wpdb->get_results($query);

        ob_start();
        include DIRECTORY_SIMPLE_PLUGIN_DIR . 'public/partials/directory-simple-public-display.php';
        return ob_get_clean();
    }

    public function search_entries() {
        check_ajax_referer('directory_simple_public_nonce', 'nonce');

        $search_term = sanitize_text_field($_POST['search_term']);
        $category = sanitize_text_field($_POST['category']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'directory_entries';

        $query = "SELECT * FROM $table_name WHERE 1=1";
        if (!empty($search_term)) {
            $query .= $wpdb->prepare(
                " AND (name LIKE %s OR description LIKE %s)",
                '%' . $wpdb->esc_like($search_term) . '%',
                '%' . $wpdb->esc_like($search_term) . '%'
            );
        }
        if (!empty($category)) {
            $query .= $wpdb->prepare(" AND category = %s", $category);
        }

        $results = $wpdb->get_results($query);
        wp_send_json_success($results);
    }
}