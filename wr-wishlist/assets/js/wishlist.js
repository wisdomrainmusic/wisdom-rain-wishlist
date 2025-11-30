jQuery(function($){

    $(document).on('click', '.wr-wishlist-btn', function(e){
        e.preventDefault();

        let btn = $(this);
        let product_id = btn.data('id');

        $.post(WRWL.ajax, {
            action: 'wr_toggle_wishlist',
            nonce: WRWL.nonce,
            product_id: product_id
        }, function(res){

            if(res.success){

                // Toggle active state on icon
                btn.toggleClass('active');
            } else {
                alert(res.data.message);
            }
        });
    });

});
