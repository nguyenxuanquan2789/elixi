/**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

/**
 * This module exposes an extension point through `showModal` function.
 *
 * If you want to customize the way the modal window is displayed, you need to do:
 *
 * prestashop.blockcart = prestashop.blockcart || {};
 * prestashop.blockcart.showModal = function myOwnShowModal (modalHTML) {
 *   // your own code
 *   // please not that it is your responsibility to handle the modal "close" behavior
 * };
 *
 * Warning: your custom JavaScript needs to be included **before** this file.
 * The safest way to do so is to place your "override" inside the theme main JavaScript file.
 *
 */

$(document).ready(function () {
  prestashop.blockcart = prestashop.blockcart || {};
  var cart = $('.blockcart');
  if($('.blockcart').hasClass('cart-sidebar')){
	  $('.blockcart > a').click(function(e){
		  e.preventDefault();
		  showCartSidebar();
	  })
  }
  var showCartSidebar = function(){
    $('.blockcart').find('.popup-sidebar').addClass('sidebar-opened');  
  }
  $('.close-cart').on('click', function(){
		$(this).parents('.popup-sidebar').removeClass('sidebar-opened'); 
	})
  var showModal = prestashop.blockcart.showModal || function (modal) {
    var $body = $('body');
    $body.append(modal);
    $body.on('click', '#blockcart-modal', function (event) {
      if (event.target.id === 'blockcart-modal') {
        $(event.target).remove();
      }
    });
  };
  prestashop.on(
    'updateCart',
    function (event) {
      var refreshURL = $('.blockcart').data('refresh-url');
      var requestData = {};
      if (event && event.reason && typeof event.resp !== 'undefined' && !event.resp.hasError) {
        requestData = {
          id_customization: event.reason.idCustomization,
          id_product_attribute: event.reason.idProductAttribute,
          id_product: event.reason.idProduct,
          action: event.reason.linkAction,
          icon : $('.blockcart').data('icon')
        };
      }
      if (event && event.resp && event.resp.hasError) {
        prestashop.emit('showErrorNextToAddtoCartButton', { errorMessage: event.resp.errors.join('<br/>')});
      }
      $.post(refreshURL, requestData).then(function (resp) {
        var html = $('<div />').append($.parseHTML(resp.preview));
        $('.cart-products-count').replaceWith($(resp.preview).find('.cart-products-count'));
        $('#qmcart-count').replaceWith($(resp.preview).find('.cart-products-count'));
        $('.cart-products-total').replaceWith($(resp.preview).find('.cart-products-total'));
        $('.popup_cart').replaceWith($(resp.preview).find('.popup_cart'));
        $('.shopping-cart-icon').replaceWith($(resp.preview).find('.shopping-cart-icon'));
        
		    $('button.add-to-cart').removeClass('loading'); 
        if (resp.modal && !cart.hasClass('cart-sidebar')) {
            showModal(resp.modal);
        }else{
			    if($('body').hasClass('page-cart') || $('body').hasClass('page-order')) return;
        	if(resp.modal){
  			    setTimeout(function(){ 
  			      $('.blockcart').find('.popup-sidebar').addClass('sidebar-opened');
  			    }, 1);
  		    }else{
  			    $('.blockcart').find('.popup-sidebar').addClass('sidebar-opened');
  		    }
          $('.blockcart > a').click(function(e){ 
  			    e.preventDefault();
  			    showCartSidebar();
		      })
  		    $('.close-cart').on('click', function(){
  			   $(this).parents('.popup-sidebar').removeClass('sidebar-opened');
  		    })
        }
      }).fail(function (resp) {
        prestashop.emit('handleError', { eventType: 'updateShoppingCart', resp: resp });
      });
    }
  );

  $('body').on(
    'click',
    '[data-button-action="buy-now"]',
    (event) => {
      event.preventDefault();
      var cart_url = $('.blockcart a').attr('href');
      if ($('#quantity_wanted').val() > $('[data-stock]').data('stock') && $('[data-allow-oosp]').data('allow-oosp').length === 0) {
          $('[data-button-action="add-to-cart"]').attr('disabled', 'disabled');
      } else {
        let $form = $(event.target).closest('form');
        let query = $form.serialize() + '&add=1&action=add';
        let actionURL = $form.attr('action');

        let isQuantityInputValid = ($input) => {
          var validInput = true;

          $input.each((index, input) => {
            let $input = $(input);
            let minimalValue = parseInt($input.attr('min'), 10);
            if (minimalValue && $input.val() < minimalValue) {
              onInvalidQuantity($input);
              validInput = false;
            }
          });

          return validInput;
        };

        let onInvalidQuantity = ($input) => {
          $input.parents('.product-add-to-cart').first().find('.product-minimal-quantity').addClass('error');
          $input.parent().find('label').addClass('error');
        };

        let $quantityInput = $form.find('input[min]' );
        if (!isQuantityInputValid($quantityInput)) {
          onInvalidQuantity($quantityInput);

          return;
        }

        $.post(actionURL, query, null, 'json');
        window.location.href = cart_url;
        
      }
    }
  );
});