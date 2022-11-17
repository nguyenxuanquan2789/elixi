import $ from 'jquery';
import prestashop from 'prestashop';
import ProductMinitature from './product-miniature';


export default function () {

    let initLoadMore = (button) => {
        let url = button.attr('href');
        let slightlyDifferentURL = [url, url.indexOf('?') >= 0 ? '&' : '?', 'from-xhr'].join('');
        button.on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            button.hide();
            $('.widget-productlist-loader').show();

            $.get(slightlyDifferentURL, null, null, 'json')
                .then(function (data) {
                        history.pushState({}, '', url);
                        let $productList = $('#js-product-list');

                        $productList.find('.products').first().append($(data.rendered_products).find('.products').first().html());
                        $productList.find('.pagination').first().replaceWith($(data.rendered_products).find('.pagination').first());
                        $('#js-product-list-bottom').replaceWith(data.rendered_products_bottom);
                        $('.widget-productlist-loader').hide();

                        let productMinitature = new ProductMinitature();
                        productMinitature.init();

                        prestashop.emit('afterUpdateProductList'); 

                        let newbutton = $('#js-product-list-bottom').find('.widget-productlist-trigger');
                        initLoadMore(newbutton);
                    }
                );
        })
        
    };
    
    var $loadMoreButton = $('.widget-productlist-trigger');
    initLoadMore($loadMoreButton);

    prestashop.on('afterUpdateProductListFacets', () => {
        var $loadMoreButton = $('.widget-productlist-trigger');
        if($loadMoreButton.length > 0){
            initLoadMore($loadMoreButton);
        }
        
    });

}





