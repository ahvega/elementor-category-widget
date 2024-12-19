jQuery(document).ready(function($) {
    $(document).on('change', '.elementor-element select[name="post_type"]', function() {
        let postType = $(this).val();
        let widgetId = $(this).closest('.elementor-element').data('id');
        let categorySelect = $('.elementor-element[data-id="' + widgetId + '"]').find('select[name="categories"]');
        if (postType) {
            $.ajax({
                url: dynamic_category_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_categories_by_post_type',
                    post_type: postType,
                    nonce: dynamic_category_ajax.nonce,
                },
                success: function(response) {
                    if (response.success) {
                        categorySelect.empty().append('<option value="">Select Category</option>');
                        $.each(response.data, function(key, value) {
                            categorySelect.append('<option value="' + key + '">' + value + '</option>');
                        });
                        categorySelect.trigger('change');
                    } else {
                        console.error(response.data);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                }
            });
        } else {
            categorySelect.empty().append('<option value="">Select Category</option>');
        }
    });
});
