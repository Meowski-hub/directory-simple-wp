(function($) {
    'use strict';

    $(document).ready(function() {
        const $searchInput = $('#directory-search');
        const $categoryFilter = $('#directory-category-filter');
        const $entries = $('.directory-entry');

        function filterEntries() {
            const searchTerm = $searchInput.val().toLowerCase();
            const selectedCategory = $categoryFilter.val();

            $entries.each(function() {
                const $entry = $(this);
                const name = $entry.find('.entry-name').text().toLowerCase();
                const description = $entry.find('.entry-description').text().toLowerCase();
                const category = $entry.data('category');

                const matchesSearch = name.includes(searchTerm) || 
                                   description.includes(searchTerm);
                const matchesCategory = !selectedCategory || 
                                     category === selectedCategory;

                if (matchesSearch && matchesCategory) {
                    $entry.show();
                } else {
                    $entry.hide();
                }
            });

            // Show/hide no entries message
            const visibleEntries = $entries.filter(':visible').length;
            $('.directory-no-entries').toggle(visibleEntries === 0);
        }

        // Event listeners for search and filter
        $searchInput.on('input', filterEntries);
        $categoryFilter.on('change', filterEntries);

        // Initialize masonry layout if available
        if ($.fn.masonry) {
            $('.directory-entries-grid').masonry({
                itemSelector: '.directory-entry',
                columnWidth: '.directory-entry',
                percentPosition: true
            });
        }
    });
})(jQuery);