(function($) {
    'use strict';

    function getNonce() {
        if (typeof WRW_VARS !== 'undefined' && WRW_VARS.nonce) {
            return WRW_VARS.nonce;
        }
        return '';
    }

    $(document).on('click', '.wrw-wishlist-btn', function(event) {
        event.preventDefault();

        var $button   = $(this);
        var productId = $button.data('product');
        var nonce     = getNonce();

        if (!productId || $button.data('loading')) {
            return;
        }

        $button.data('loading', true);

        $.post(WRW_VARS.ajax_url, {
            action: 'wrw_toggle_wishlist',
            product_id: productId,
            nonce: nonce
        })
        .done(function(response) {
            if (!response || !response.success) {
                console.error(response && response.data ? response.data.message : 'Wishlist request failed.');
                return;
            }

            var isActive = response.data.status === 'added';

            $button.attr('data-active', isActive ? '1' : '0');
            $button.find('.wrw-heart').toggleClass('active', isActive);

            $(document).trigger('wrw:wishlist:toggled', [isActive, response.data]);

            if ($('.wrw-wishlist-count').length) {
                $('.wrw-wishlist-count').text(response.data.count);
            }
        })
        .fail(function(jqXHR) {
            console.error('Wishlist AJAX failed:', jqXHR.statusText);
        })
        .always(function() {
            $button.data('loading', false);
        });
    });
})(jQuery);
