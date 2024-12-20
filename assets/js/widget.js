jQuery(window).on('elementor/frontend/init', function() {
    elementor.hooks.addAction('panel/open_editor/widget/category-widget', function(panel, model, view) {
        var $postTypeControl = panel.$el.find('[data-setting="post_type"]');
        var $categoriesControl = panel.$el.find('[data-setting="categories"]');

        // Initialize Select2
        if ($categoriesControl.length) {
            $categoriesControl.select2({
                multiple: true,
                placeholder: 'Select categories',
                allowClear: true,
                width: '100%'
            });
        }

        // Handle post type changes
        $postTypeControl.on('change', function() {
            var postType = jQuery(this).val();
            updateCategories(postType);
        });

        // Update categories based on post type
        function updateCategories(postType) {
            if (!$categoriesControl.length) return;

            $categoriesControl.prop('disabled', true);
            var currentValues = $categoriesControl.val();

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'get_categories_by_post_type',
                    post_type: postType,
                    nonce: elementor.config.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        $categoriesControl.empty();
                        Object.keys(response.data).forEach(function(termId) {
                            var option = new Option(response.data[termId], termId, false, false);
                            $categoriesControl.append(option);
                        });

                        // Restore previously selected values if they still exist
                        if (currentValues) {
                            $categoriesControl.val(currentValues);
                        }

                        $categoriesControl.trigger('change');

                        // Render preview without reloading panel
                        view.renderOnChange();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', error);
                },
                complete: function() {
                    $categoriesControl.prop('disabled', false);
                }
            });
        }

        // Initial load of categories
        var initialPostType = $postTypeControl.val() || 'post';
        updateCategories(initialPostType);

        // Handle typography and other control changes
        panel.$el.on('input change', '[data-setting]', function() {
            // Render preview without reloading panel
            view.renderOnChange();
        });
    });
});

// End of File widget.js