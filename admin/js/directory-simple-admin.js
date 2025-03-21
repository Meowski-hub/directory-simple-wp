(function($) {
    'use strict';

    $(document).ready(function() {
        // Handle form submission for new entries
        $('#directory-simple-add-form').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                action: 'save_directory_entry',
                nonce: directorySimple.nonce,
                name: $('#name').val(),
                description: $('#description').val(),
                contact_info: $('#contact_info').val(),
                category: $('#category').val()
            };

            $.post(directorySimple.ajaxurl, formData, function(response) {
                if (response.success) {
                    alert('Entry saved successfully!');
                    location.reload();
                } else {
                    alert('Error saving entry. Please try again.');
                }
            });
        });

        // Handle CSV import
        $('#directory-simple-import-form').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'handle_csv_import');
            formData.append('nonce', directorySimple.nonce);

            $.ajax({
                url: directorySimple.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('CSV import completed successfully!');
                        location.reload();
                    } else {
                        alert('Error importing CSV. Please try again.');
                    }
                }
            });
        });

        // Handle entry deletion
        $('.delete-entry').on('click', function() {
            if (confirm('Are you sure you want to delete this entry?')) {
                const entryId = $(this).data('id');
                
                $.post(directorySimple.ajaxurl, {
                    action: 'delete_directory_entry',
                    nonce: directorySimple.nonce,
                    entry_id: entryId
                }, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error deleting entry. Please try again.');
                    }
                });
            }
        });

        // Handle entry editing
        $('.edit-entry').on('click', function() {
            const entryId = $(this).data('id');
            const row = $(this).closest('tr');
            
            const name = row.find('td:eq(0)').text();
            const description = row.find('td:eq(1)').text();
            const contactInfo = row.find('td:eq(2)').text();
            const category = row.find('td:eq(3)').text();

            // Populate form with entry data
            $('#name').val(name);
            $('#description').val(description);
            $('#contact_info').val(contactInfo);
            $('#category').val(category);

            // Change form submit button text
            $('#directory-simple-add-form button[type="submit"]')
                .text('Update Entry')
                .data('entry-id', entryId);

            // Scroll to form
            $('html, body').animate({
                scrollTop: $('#directory-simple-add-form').offset().top - 50
            }, 500);
        });
    });
})(jQuery);