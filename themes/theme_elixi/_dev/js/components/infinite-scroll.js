import $ from 'jquery';
import prestashop from 'prestashop';
import ProductMinitature from './product-miniature';


export default function ($productDiv) {

    let initialize = false;
    let infiniteWaypoint;
    let $infiniteUrlButton = $('.trigger_infinite');
    let pendingQuery = false;

    let initWaypoint = () => {

        var element = $('#js-product-list').find('.products')[0];
        var options = {
            element: element,
            handler: function (direction) {
                if (direction == 'down') {

                    let url = $infiniteUrlButton.attr('href');
                    let slightlyDifferentURL = [url, url.indexOf('?') >= 0 ? '&' : '?', 'from-xhr'].join('');



                    $productDiv.addClass('-infinity-loading');
                    infiniteWaypoint.destroy();
                    initialize = false;

                    $.get(slightlyDifferentURL, null, null, 'json')
                        .then(function (data) {
                                pendingQuery = false;
                                history.pushState({}, '', url);
                                let $productList = $('#js-product-list');

                                $productList.find('.products').first().append($(data.rendered_products).find('.products').first().html());
                                $productList.find('.pagination').first().replaceWith($(data.rendered_products).find('.pagination').first());
                                $('#js-product-list-bottom').replaceWith(data.rendered_products_bottom);

                                let productMinitature = new ProductMinitature();
                                productMinitature.init();

                                $productDiv.removeClass('-infinity-loading');
                                prestashop.emit('afterUpdateProductList');

                                var $newInfiniteUrlButton = $(data.rendered_products_bottom).find('.trigger_infinite');
                                if ($newInfiniteUrlButton.length) {
                                    $infiniteUrlButton = $newInfiniteUrlButton;
                                    infiniteWaypoint = new Waypoint(options);
                                    initialize = true;
                                } else{
                                    if (initialize){
                                        infiniteWaypoint.destroy();
                                    }
                                }
                            }
                        );
                }
            },
            offset: 'bottom-in-view'
        };

        if ($infiniteUrlButton.length) {
            infiniteWaypoint = new Waypoint(options);
            initialize = true;
        }
    };

    initWaypoint();

    prestashop.on('afterUpdateProductListFacets', () => {
        if (initialize){
            infiniteWaypoint.destroy();
        }
        $infiniteUrlButton = $('.trigger_infinite');
        initWaypoint();

    });

}





