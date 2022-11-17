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
import ProductSelect from './components/product-select';
import './lib/easyzoom.min';

export default class Product {
  productImageSlider(){
		var $productContainer = $('.product-container'),
			$images = $('.product-images.slick-block'),
			$thumbnails = $('.product-thumbs.slick-block'),
      isQuickview = false;
    if($('.quickview').length > 0){
      isQuickview = true;
    }
		if($productContainer.hasClass('horizontal-thumb') || isQuickview){
			var item = $thumbnails.data('item');
			$images.not('.slick-initialized').slick({
				infinite: false,
			});
			$thumbnails
				.on('init', function(event, slick) {$('.product-thumbs.slick-block .slick-slide.slick-current').addClass('is-active');})
				.not('.slick-initialized').slick({
					slidesToShow: item,
					infinite: false,
				});
		};
		if(($productContainer.hasClass('vertical-left') || $productContainer.hasClass('vertical-right')) && !isQuickview){
			var item = $thumbnails.data('item');
			$images.not('.slick-initialized').slick({
				infinite: false,
			});
			$thumbnails
			.on('init', function(event, slick) {$('.product-thumbs.slick-block .slick-slide.slick-current').addClass('is-active');})
			.not('.slick-initialized').slick({
				slidesToShow: item,
				infinite: false,
				vertical: true,
				responsive: [
          {
					  breakpoint: 767,
					  settings: {
              vertical: false,
            }
					},
					{
					  breakpoint: 399,
					  settings: {
              slidesToShow: 3,
              slidesToScroll: 1, 
              vertical: false,
            }
					}
				]
			});
			$('.product-images.slick-block img').load(function() {
				$thumbnails.slick("setPosition", 0);
			});
			
		};
	
		$images.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
		 	$thumbnails.slick('slickGoTo', nextSlide);
		 	var currrentNavSlideElem = $thumbnails.find('.slick-slide[data-slick-index="' + nextSlide + '"]');
		 	$thumbnails.find('.slick-slide').removeClass('is-active');
		 	currrentNavSlideElem.addClass('is-active');
		});

		$thumbnails.on('click', '.slick-slide', function(event) {
		 	event.preventDefault();
		 	var goToSingleSlide = $(this).data('slick-index');

		 	$images.slick('slickGoTo', goToSingleSlide);
		});
		if(isQuickview){
      $('.product-thumbs.slick-block .slick-slide.slick-current').addClass('is-active');
    }

	};
}

$(document).ready(() => {
  createProductSpin();
  createInputFile();
  addJsProductTabActiveSelector();
  productImageZoom();
  initThemeSliders($('.product-categoryslide'));

  const productPage = new Product();
  productPage.productImageSlider();

  prestashop.on('updatedProduct', (event) => {
    createInputFile();
    productPage.productImageSlider();
    if (event && event.product_minimal_quantity) {
      const minimalProductQuantity = parseInt(event.product_minimal_quantity, 10);
      const quantityInputSelector = prestashop.selectors.quantityWanted;
      const quantityInput = $(quantityInputSelector);
      // @see http://www.virtuosoft.eu/code/bootstrap-touchspin/ about Bootstrap TouchSpin
      quantityInput.trigger('touchspin.updatesettings', {
        min: minimalProductQuantity,
      });
    }
    $($(prestashop.themeSelectors.product.activeTabs).attr('href')).addClass('active').removeClass('fade');
    $(prestashop.themeSelectors.product.imagesModal).replaceWith(event.product_images_modal);
    $('.box_button').empty();
    $('.box_button').append($(event.product_add_to_cart).find('.box_button').html());
    const productSelect = new ProductSelect();
    productSelect.init();
  });

  function createInputFile() {
    $(prestashop.themeSelectors.fileInput).on('change', (event) => {
      let target;
      let file;

      // eslint-disable-next-line
      if ((target = $(event.currentTarget)[0]) && (file = target.files[0])) {
        $(target).prev().text(file.name);
      }
    });
  }

  function createProductSpin() {
    const $quantityInput = $(prestashop.selectors.quantityWanted);

    $quantityInput.TouchSpin({
      verticalbuttons: true,
      verticalupclass: 'material-icons touchspin-up',
      verticaldownclass: 'material-icons touchspin-down',
      buttondown_class: 'btn btn-touchspin js-touchspin',
      buttonup_class: 'btn btn-touchspin js-touchspin',
      min: parseInt($quantityInput.attr('min'), 10),
      max: 1000000,
    });

    $(prestashop.themeSelectors.touchspin).off('touchstart.touchspin');

    $quantityInput.focusout(() => {
      if ($quantityInput.val() === '' || $quantityInput.val() < $quantityInput.attr('min')) {
        $quantityInput.val($quantityInput.attr('min'));
        $quantityInput.trigger('change');
      }
    });

    $('body').on('change keyup', prestashop.selectors.quantityWanted, (e) => {
      if ($quantityInput.val() !== '') {
        $(e.currentTarget).trigger('touchspin.stopspin');
        prestashop.emit('updateProduct', {
          eventType: 'updatedProductQuantity',
          event: e,
        });
      }
    });
  }

  function addJsProductTabActiveSelector() {
    const nav = $(prestashop.themeSelectors.product.tabs);
    nav.on('show.bs.tab', (e) => {
      const target = $(e.target);
      target.addClass(prestashop.themeSelectors.product.activeNavClass);
      $(target.attr('href')).addClass(prestashop.themeSelectors.product.activeTabClass);
    });
    nav.on('hide.bs.tab', (e) => {
      const target = $(e.target);
      target.removeClass(prestashop.themeSelectors.product.activeNavClass);
      $(target.attr('href')).removeClass(prestashop.themeSelectors.product.activeTabClass);
    });
  }
  function productImageZoom(){
		var $easyzoom = $('.easyzoom');
		$easyzoom.trigger( 'zoom.destroy' );
	 	if($(window).width() >= 992) 
		{
			$easyzoom.easyZoom();
		}
		$(window).resize(function(){
			$easyzoom.trigger( 'zoom.destroy' );
			if($(window).width() >= 992){
				$easyzoom.easyZoom();
			}
		});
	}
  function initThemeSliders(){
    $('.product-accessoriesslide').on('init', function(event, slick, currentSlide){
      var slideToShow = $(this).find('.slick-active').length - 1;
      $(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
      $(this).find('.slick-active').eq(0).addClass('first-active');
      $(this).find('.slick-active').eq(slideToShow).addClass('last-active');
    });
    $('.product-accessoriesslide').not('.slick-initialized').slick({ 
       slidesToShow: 5,
       slidesToScroll: 1,
       dots: false, 
       arrows: true,  
       responsive: [
      {breakpoint: 1199, settings: { slidesToShow: 4}},
      {breakpoint: 991, settings: { slidesToShow: 3}},
      {breakpoint: 767, settings: { slidesToShow: 2}},
      {breakpoint: 575, settings: { slidesToShow: 2}},
      {breakpoint: 359, settings: { slidesToShow: 1}}
      ]
    });
    $('.product-accessoriesslide').on('afterChange', function(event, slick, currentSlide){
      var slideToShow = $(this).find('.slick-active').length - 1;
      $(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
      $(this).find('.slick-active').eq(0).addClass('first-active');
      $(this).find('.slick-active').eq(slideToShow).addClass('last-active');
    });
    $('.product-categoryslide').on('init', function(event, slick, currentSlide){
      var slideToShow = $(this).find('.slick-active').length - 1;
      $(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
      $(this).find('.slick-active').eq(0).addClass('first-active');
      $(this).find('.slick-active').eq(slideToShow).addClass('last-active');
    });
    $('.product-categoryslide').not('.slick-initialized').slick({ 
       slidesToShow: 5,
       slidesToScroll: 1,
       dots: false, 
       arrows: true,  
       responsive: [
          {breakpoint: 1199, settings: { slidesToShow: 4}},
          {breakpoint: 991, settings: { slidesToShow: 3}},
          {breakpoint: 767, settings: { slidesToShow: 2}},
          {breakpoint: 575, settings: { slidesToShow: 2}},
          {breakpoint: 359, settings: { slidesToShow: 1}}
        ]
    });
    $('.product-categoryslide').on('afterChange', function(event, slick, currentSlide){
      var slideToShow = $(this).find('.slick-active').length - 1;
      $(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
      $(this).find('.slick-active').eq(0).addClass('first-active');
      $(this).find('.slick-active').eq(slideToShow).addClass('last-active');
    });

    var viewedSlick = $('.vec-viewed-products .slick-slider');

    viewedSlick.on('init', function(event, slick, currentSlide){
      var slideToShow = $(this).find('.slick-active').length - 1;
      $(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
      $(this).find('.slick-active').eq(0).addClass('first-active');
      $(this).find('.slick-active').eq(slideToShow).addClass('last-active');
    });
    var defaultOptions = {
          responsive: [
              {
                  breakpoint: 1200,
                  settings: {
                    slidesToShow: 4,
                  }
                },
                {
                  breakpoint: 992,
                  settings: {
                    slidesToShow: 3,
                  }
                },
                {
                  breakpoint: 768,
                  settings: {
                    slidesToShow: 2,
                  }
                },
              {
                  breakpoint: 568,
                  settings: {
                    slidesToShow: 2,
                  }
                },
                {
                  breakpoint: 360,
                  settings: {
                    slidesToShow: 1,
                  }
                }
          ]
        };
    var slickOptions = $.extend({}, defaultOptions, viewedSlick.data('slick-options'));
    viewedSlick.not('.slick-initialized').slick(slickOptions);
    viewedSlick.on('afterChange', function(event, slick, currentSlide){
      var slideToShow = $(this).find('.slick-active').length - 1;
      $(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
      $(this).find('.slick-active').eq(0).addClass('first-active');
      $(this).find('.slick-active').eq(slideToShow).addClass('last-active');
    });
  }
});
