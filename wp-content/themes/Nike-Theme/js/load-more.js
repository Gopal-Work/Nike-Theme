jQuery(document).ready(function ($) {

    $(document).on('click', '.lm-load-more-btn', function (e) {
        e.preventDefault();

        var $button   = $(this);
        var $wrapper  = $button.closest('.lm-wrapper');
        var container = $wrapper.find('.lm-posts-container');

        var currentPage  = parseInt($button.data('page'));
        var maxPages     = parseInt($button.data('max-pages'));
        var postsPerPage = $button.data('posts-per-page');
        var category     = $button.data('category');
        var postType     = $button.data('post-type');

        $button.text('Loading...').prop('disabled', true);

        $.ajax({
            url: lm_ajax_obj.ajax_url,     // STEP 2 se aaya
            type: 'POST',
            data: {
                action: 'load_more_posts_action',  // STEP 3 hook se match
                page: currentPage,
                posts_per_page: postsPerPage,
                category: category,
                post_type: postType,
                nonce: lm_ajax_obj.nonce           // STEP 2 se aaya
            },
            success: function (response) {
                if (response.success) {
                    container.append(response.data.html);

                    var nextPage = response.data.next_page;
                    $button.data('page', nextPage).text('Load More');

                    if (nextPage >= response.data.max_pages) {
                        $button.remove(); // sab posts load ho gaye
                    }
                } else {
                    $button.text('No more posts');
                }
                $button.prop('disabled', false);
            },
            error: function () {
                $button.text('Error, try again').prop('disabled', false);
            }
        });
    });

});