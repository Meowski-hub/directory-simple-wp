<?php
if (!defined('WPINC')) {
    die;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="directory-simple-admin-container">
        <!-- Add New Entry Form -->
        <div class="directory-simple-form-container">
            <h2><?php _e('Add New Entry', 'directory-simple'); ?></h2>
            <form id="directory-simple-add-form" class="directory-form">
                <div class="form-group">
                    <label for="name"><?php _e('Name', 'directory-simple'); ?></label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="description"><?php _e('Description', 'directory-simple'); ?></label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="contact_info"><?php _e('Contact Information', 'directory-simple'); ?></label>
                    <textarea id="contact_info" name="contact_info" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="category"><?php _e('Category', 'directory-simple'); ?></label>
                    <input type="text" id="category" name="category">
                </div>

                <button type="submit" class="button button-primary">
                    <?php _e('Add Entry', 'directory-simple'); ?>
                </button>
            </form>
        </div>

        <!-- CSV Import Form -->
        <div class="directory-simple-import-container">
            <h2><?php _e('Import Entries', 'directory-simple'); ?></h2>
            <form id="directory-simple-import-form" class="directory-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="csv_file"><?php _e('CSV File', 'directory-simple'); ?></label>
                    <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
                </div>
                <button type="submit" class="button button-secondary">
                    <?php _e('Import CSV', 'directory-simple'); ?>
                </button>
            </form>
        </div>

        <!-- Existing Entries Table -->
        <div class="directory-simple-entries-container">
            <h2><?php _e('Existing Entries', 'directory-simple'); ?></h2>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'directory_entries';
            $entries = $wpdb->get_results("SELECT * FROM $table_name ORDER BY name ASC");
            ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Name', 'directory-simple'); ?></th>
                        <th><?php _e('Description', 'directory-simple'); ?></th>
                        <th><?php _e('Contact Info', 'directory-simple'); ?></th>
                        <th><?php _e('Category', 'directory-simple'); ?></th>
                        <th><?php _e('Actions', 'directory-simple'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $entry): ?>
                        <tr>
                            <td><?php echo esc_html($entry->name); ?></td>
                            <td><?php echo esc_html($entry->description); ?></td>
                            <td><?php echo esc_html($entry->contact_info); ?></td>
                            <td><?php echo esc_html($entry->category); ?></td>
                            <td>
                                <button class="button button-small edit-entry" 
                                        data-id="<?php echo esc_attr($entry->id); ?>">
                                    <?php _e('Edit', 'directory-simple'); ?>
                                </button>
                                <button class="button button-small button-link-delete delete-entry" 
                                        data-id="<?php echo esc_attr($entry->id); ?>">
                                    <?php _e('Delete', 'directory-simple'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>