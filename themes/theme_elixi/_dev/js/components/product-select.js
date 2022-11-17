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
// eslint-disable-next-line
import 'velocity-animate';

export default class ProductSelect {
  init() {
    const MAX_THUMBS = 5;
    const $arrows = $('.js-modal-arrows');
    const $thumbnails = $('.js-modal-product-images');
    let $wrapper = $('#wrapper');

    $wrapper.on('shown.bs.modal', '#product-modal', function (e) {
      var $thumbnails_modal = $('.product-images-modal');
      $thumbnails_modal
      .on('init', function(event, slick) {$(this).find('.slick-slide.slick-current').addClass('is-active');})
      .slick({
        slidesToShow: $thumbnails_modal.data('item'),
      });
    })

    $('body')
      .on('click', '.js-modal-thumb', (event) => {
        if ($('.js-modal-thumb').hasClass('selected')) {
          $('.js-modal-thumb').removeClass('selected');
        }
        $(event.currentTarget).addClass('selected');
        $('.js-modal-product-cover').attr('src', $(event.target).data('image-large-src'));
        $('.js-modal-product-cover').attr('title', $(event.target).attr('title'));
        $('.js-modal-product-cover').attr('alt', $(event.target).attr('alt'));
        $('.product-images-modal').find('.slick-slide').removeClass('is-active')
        $(event.currentTarget).closest('.slick-slide').addClass('is-active');
      })
      .on('click', 'aside#thumbnails', (event) => {
        if (event.target.id === 'thumbnails') {
          $('#product-modal').modal('hide');
        }
      });

  }

 
}
