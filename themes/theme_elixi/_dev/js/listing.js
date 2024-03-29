/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
import $ from 'jquery';
import prestashop from 'prestashop';
// eslint-disable-next-line
import "velocity-animate";

import Product from './product';
import ProductMinitature from './components/product-miniature';
import infiniteScroll from './components/infinite-scroll';
import loadMore from './components/load-more';

$(document).ready(() => {
  const history = window.location.href;
  let $productDiv = $('#products');

  var gridProducts = $productDiv.find('.js-product-miniature:not(.product-miniature-list) .product-content');
  if(gridProducts.length > 0){
    gridProducts.addClass('match_height');
    matchHeight($productDiv);
  }

  infiniteScroll($productDiv);
  if($('.widget-productlist-trigger.loadmore').length > 0){
    loadMore();
  }

  $('.facet-label').each(function(){
    if($(this).hasClass('active')){
      $(this).parents('section.facet').addClass('active');
    }
  });

  $('body').on('click', '.filters-top .facet .facet-title', function(e){
    e.preventDefault();
    $('.facet-content').not($(this).next('.facet-content')).removeClass('facet-open');
    $(this).next('.facet-content').toggleClass('facet-open');
  })
  $("body").on('click', function(e) {
      e = $(e.target);
      if(!e.is(".facet-content") && !e.next(".facet-content").length > 0 && !e.parents(".facet-content").hasClass('facet-open')) {
          $(".facet-content").removeClass("facet-open");
      }     
  })

  $('body').on('click', '.filter-button a', function(e){
      e.preventDefault();
      e.stopPropagation();
      $('.filters-canvas').addClass('filter-open');
      $('.vec-overlay').addClass('open');
  })

  $('body').on('click', '.vec-overlay, .filter-close-btn', function(e){
      $('.filters-canvas').removeClass('filter-open');
      $('.vec-overlay').removeClass('open');
  })

  $('body').on('click', 'a.block-expand', function(){
    var target = $(this).parents('.expand-content');
    if(target.hasClass('expanded-content')) {
      target.removeClass('expanded-content');
    }else{
      target.addClass('expanded-content');
    }
    
  })

  prestashop.on('clickQuickView', (elm) => {
    $('.js-quick-view').each(function(){
      if($(this).data('id-product') == elm.dataset.idProduct){
        $(this).addClass('loading');
      }
    })
    const data = {
      action: 'quickview',
      id_product: elm.dataset.idProduct,
      id_product_attribute: elm.dataset.idProductAttribute,
    };
    $.post(prestashop.urls.pages.product, data, null, 'json')
      .then((resp) => {
        $('body').append(resp.quickview_html);
        const productModal = $(
          `#quickview-modal-${resp.product.id}-${resp.product.id_product_attribute}`,
        );
        productModal.modal('show');
        $('.js-quick-view').removeClass('loading');
        productConfig(productModal);
        
        const productPage = new Product();
        
        productModal.on('shown.bs.modal', function (e) {
          productPage.productImageSlider();
        })
        productModal.on('hidden.bs.modal', () => {
          productModal.remove();
        });
      })
      .fail((resp) => {
        prestashop.emit('handleError', {
          eventType: 'clickQuickView',
          resp,
        });
      });
  });

  const productConfig = (qv) => {
    const MAX_THUMBS = 4;
    const $arrows = $(prestashop.themeSelectors.product.arrows);
    const $thumbnails = qv.find('.js-qv-product-images');
    $(prestashop.themeSelectors.product.thumb).on('click', (event) => {
      if ($(prestashop.themeSelectors.product.thumb).hasClass('selected')) {
        $(prestashop.themeSelectors.product.thumb).removeClass('selected');
      }
      $(event.currentTarget).addClass('selected');
      $(prestashop.themeSelectors.product.cover).attr(
        'src',
        $(event.target).data('image-large-src'),
      );
    });
    if ($thumbnails.find('li').length <= MAX_THUMBS) {
      $arrows.hide();
    } else {
      $arrows.on('click', (event) => {
        if (
          $(event.target).hasClass('arrow-up')
          && $('.js-qv-product-images').position().top < 0
        ) {
          move('up');
          $(prestashop.themeSelectors.arrowDown).css('opacity', '1');
        } else if (
          $(event.target).hasClass('arrow-down')
          && $thumbnails.position().top + $thumbnails.height()
            > $('.js-qv-mask').height()
        ) {
          move('down');
          $(prestashop.themeSelectors.arrowUp).css('opacity', '1');
        }
      });
    }
    qv.find(prestashop.selectors.quantityWanted).TouchSpin({
      verticalbuttons: true,
      verticalupclass: 'material-icons touchspin-up',
      verticaldownclass: 'material-icons touchspin-down',
      buttondown_class: 'btn btn-touchspin js-touchspin',
      buttonup_class: 'btn btn-touchspin js-touchspin',
      min: 1,
      max: 1000000,
    });

    $(prestashop.themeSelectors.touchspin).off('touchstart.touchspin');
  };

  const move = (direction) => {
    const THUMB_MARGIN = 20;
    const $thumbnails = $('.js-qv-product-images');
    const thumbHeight = $('.js-qv-product-images li img').height() + THUMB_MARGIN;
    const currentPosition = $thumbnails.position().top;
    $thumbnails.velocity(
      {
        translateY:
          direction === 'up'
            ? currentPosition + thumbHeight
            : currentPosition - thumbHeight,
      },
      () => {
        if ($thumbnails.position().top >= 0) {
          $('.arrow-up').css('opacity', '.2');
        } else if (
          $thumbnails.position().top + $thumbnails.height()
          <= $('.js-qv-mask').height()
        ) {
          $('.arrow-down').css('opacity', '.2');
        }
      },
    );
  };
  $('body').on(
    'click',
    prestashop.themeSelectors.listing.searchFilterToggler,
    () => {
      $(prestashop.themeSelectors.listing.searchFiltersWrapper).removeClass(
        'hidden-sm-down',
      );
      $(prestashop.themeSelectors.contentWrapper).addClass('hidden-sm-down');
      $(prestashop.themeSelectors.footer).addClass('hidden-sm-down');
    },
  );
  $(
    `${prestashop.themeSelectors.listing.searchFilterControls} ${prestashop.themeSelectors.clear}`,
  ).on('click', () => {
    $(prestashop.themeSelectors.listing.searchFiltersWrapper).addClass(
      'hidden-sm-down',
    );
    $(prestashop.themeSelectors.contentWrapper).removeClass('hidden-sm-down');
    $(prestashop.themeSelectors.footer).removeClass('hidden-sm-down');
  });
  $(`${prestashop.themeSelectors.listing.searchFilterControls} .ok`).on(
    'click',
    () => {
      $(prestashop.themeSelectors.listing.searchFiltersWrapper).addClass(
        'hidden-sm-down',
      );
      $(prestashop.themeSelectors.contentWrapper).removeClass('hidden-sm-down');
      $(prestashop.themeSelectors.footer).removeClass('hidden-sm-down');
    },
  );

  const parseSearchUrl = function (event) {
    if (event.target.dataset.searchUrl !== undefined) {
      return event.target.dataset.searchUrl;
    }

    if ($(event.target).parent()[0].dataset.searchUrl === undefined) {
      throw new Error('Can not parse search URL');
    }

    return $(event.target).parent()[0].dataset.searchUrl;
  };

  $('body').on(
    'change',
    `${prestashop.themeSelectors.listing.searchFilters} input[data-search-url]`,
    (event) => {
      prestashop.emit('updateFacets', parseSearchUrl(event));
    },
  );

  $('body').on(
    'click',
    prestashop.themeSelectors.listing.searchFiltersClearAll,
    (event) => {
      prestashop.emit('updateFacets', parseSearchUrl(event));
    },
  );

  $('body').on('click', prestashop.themeSelectors.listing.searchLink, (event) => {
    event.preventDefault();
    prestashop.emit(
      'updateFacets',
      $(event.target)
        .closest('a')
        .get(0).href,
    );
  });

  if ($(prestashop.themeSelectors.listing.list).length) {
    window.addEventListener('popstate', (e) => {
      const {state} = e;
      window.location.href = state && state.current_url ? state.current_url : history;
    });
  }

  $('body').on(
    'change',
    `${prestashop.themeSelectors.listing.searchFilters} select`,
    (event) => {
      const form = $(event.target).closest('form');
      prestashop.emit('updateFacets', `?${form.serialize()}`);
    },
  );

  prestashop.on('updateProductList', (data) => {
    updateProductListDOM(data);
    prestashop.emit('afterUpdateProductListFacets');
    prestashop.emit('afterUpdateProductList');

    $('.filters-canvas').removeClass('filter-open');
    $('.vec-overlay').removeClass('open');
    vecScrollTop($('#vec-use-scroll'));
    
    let gridProducts = $productDiv.find('.js-product-miniature:not(.product-miniature-list) .product-content');
    if(gridProducts.length > 0){
      gridProducts.addClass('match_height');
      matchHeight($productDiv);
    }
  });
});

function vecScrollTop(element){
  $('html, body').animate({
    scrollTop: element.offset().top
  }, 500);
}
function matchHeight(vec_content = null){
  function init(vec_content) {
    eventListeners(vec_content);
    matchItemHeight(vec_content);
  }
  
  function eventListeners(vec_content){
    $(window).on('resize', function() {
      matchItemHeight(vec_content);
    });
  }
  
  function matchItemHeight(vec_content){
    
    var groupName = vec_content.find('.match_height');
    var groupHeights = [];
    if(groupName.length <= 0) return;
    groupName.css('min-height', 'auto');
    
    groupName.each(function() {
      groupHeights.push($(this).outerHeight());
    });
    var maxHeight = Math.max.apply(null, groupHeights);
    groupName.css('min-height', maxHeight);
  };
  
  init(vec_content);
  
}

function updateProductListDOM(data) {

  $(prestashop.themeSelectors.listing.searchFilters).replaceWith(
    data.rendered_facets,
  );
  
  $(prestashop.themeSelectors.listing.activeSearchFilters).replaceWith(
    data.rendered_active_filters,
  );
  $(prestashop.themeSelectors.listing.listTop).replaceWith(
    data.rendered_products_top,
  );

  const renderedProducts = $(data.rendered_products);
  const productSelectors = $(prestashop.themeSelectors.listing.product, renderedProducts);

  if (productSelectors.length > 0) {
    productSelectors.removeClass().addClass($(prestashop.themeSelectors.listing.product).first().attr('class'));
  }

  $(prestashop.themeSelectors.listing.list).replaceWith(renderedProducts);

  $(prestashop.themeSelectors.listing.listBottom).replaceWith(
    data.rendered_products_bottom,
  );
  if (data.rendered_products_header) {
    $(prestashop.themeSelectors.listing.listHeader).replaceWith(
      data.rendered_products_header,
    );
  }

  const productMinitature = new ProductMinitature();
  productMinitature.init();

  $('.facet-label').each(function(){
    if($(this).hasClass('active')){
      $(this).parents('section.facet').addClass('active');
    }
  });
  
}
