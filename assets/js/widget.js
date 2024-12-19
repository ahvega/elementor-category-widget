jQuery(window).on('elementor/frontend/init', function() {
    elementor.hooks.addAction('panel/open_editor/widget/category-widget', function(panel, model, view) {
        var $postTypeControl = panel.$el.find('[data-setting="post_type"]');
        var $categoriesControl = panel.$el.find('[data-setting="categories"]');

        $postTypeControl.on('change', function() {
            var postType = $(this).val();

            // Clear current categories
            $categoriesControl.empty();

            // Get new categories via AJAX
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_categories_by_post_type',
                    post_type: postType,
                    nonce: elementorCommon.config.ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var options = response.data;
                        $.each(options, function(value, label) {
                            $categoriesControl.append(
                                new Option(label, value, false, false)
                            );
                        });
                        $categoriesControl.trigger('change');
                    }
                }
            });
        });
    });
});
