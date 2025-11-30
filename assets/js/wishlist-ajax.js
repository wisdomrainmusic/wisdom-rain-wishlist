jQuery(function($){

    $('body').on('click', '.wrw-wishlist-btn', function(e){
        e.preventDefault();

        let btn       = $(this);
        let productID = btn.data('product');

        if (!productID) return;

        btn.addClass('wrw-loading');

        $.post(WRW_VARS.ajax_url, {
            action:    'wrw_toggle_wishlist',
            nonce:     WRW_VARS.nonce,
            product_id: productID
        }, function(res){

            btn.removeClass('wrw-loading');

            if (!res || !res.success) {
                console.error("Wishlist Error:", res && res.data ? res.data.message : 'Unknown error');
                return;
            }

            // ADDED
            if (res.data.status === 'added') {
                btn.data('active', 1);
                btn.find('.wrw-heart').addClass('active');
            }

            // REMOVED
            if (res.data.status === 'removed') {
                btn.data('active', 0);
                btn.find('.wrw-heart').removeClass('active');

                // Eğer wishlist sayfasındaysak kartı DOM'dan da kaldır
                let item = btn.closest('.wrw-item');
                if (item.length) {
                    item.slideUp(200, function(){
                        $(this).remove();
                    });
                }
            }

        }, 'json');
    });

});
