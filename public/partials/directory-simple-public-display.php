<?php
if (!defined('WPINC')) {
    die;
}
?>

<div class="directory-simple-container">
    <!-- Search and Filter Section -->
    <div class="directory-simple-search">
        <input type="text" id="directory-search" placeholder="<?php _e('Search...', 'directory-simple'); ?>">
        
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'directory_entries';
        $categories = $wpdb->get_col("SELECT DISTINCT category FROM $table_name ORDER BY category ASC");
        if (!empty($categories)):
        ?>
        <select id="directory-category-filter">
            <option value=""><?php _e('All Categories', 'directory-simple'); ?></option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo esc_attr($category); ?>">
                    <?php echo esc_html($category); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
    </div>

    <!-- Directory Entries -->
    <div class="directory-entries-grid">
        <?php foreach ($entries as $entry): ?>
            <div class="directory-entry" data-category="<?php echo esc_attr($entry->category); ?>">
                <h3 class="entry-name"><?php echo esc_html($entry->name); ?></h3>
                
                <?php if (!empty($entry->description)): ?>
                    <div class="entry-description">
                        <?php echo wp_kses_post(nl2br($entry->description)); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($entry->contact_info)): ?>
                    <div class="entry-contact">
                        <strong><?php _e('Contact:', 'directory-simple'); ?></strong>
                        <?php echo wp_kses_post(nl2br($entry->contact_info)); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($entry->category)): ?>
                    <div class="entry-category">
                        <span class="category-tag"><?php echo esc_html($entry->category); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($entries)): ?>
        <div class="directory-no-entries">
            <?php _e('No entries found.', 'directory-simple'); ?>
        </div>
    <?php endif; ?>
</div>