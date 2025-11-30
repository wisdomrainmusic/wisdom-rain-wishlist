jQuery(function($){

    $(document).on('click', '.wr-wishlist-btn', function(e){
        e.preventDefault();

        let product_id = $(this).data('id');

        $.post(WRWL.ajax, {
            action: 'wr_toggle_wishlist',
            nonce: WRWL.nonce,
            product_id: product_id
        }, function(res){

            if(res.success){
                console.log('Wishlist updated', res.data);
            } else {
                alert(res.data.message);
            }
        });
    });

});
