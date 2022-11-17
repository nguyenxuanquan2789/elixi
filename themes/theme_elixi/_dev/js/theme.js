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
/* eslint-disable */
import 'expose-loader?exposes=Tether!tether';
import 'bootstrap/dist/js/bootstrap.min';
import 'flexibility';
import 'bootstrap-touchspin';
import 'jquery-touchswipe';
import './lib/slick.min';
import './lib/countdown.min';
import './selectors';

import './responsive';
import './checkout';
import './customer';
import './listing';
import './product';
import './cart';

import prestashop from 'prestashop';
import EventEmitter from 'events';
import DropDown from './components/drop-down';
import Form from './components/form';
import ProductMinitature from './components/product-miniature';
import ProductSelect from './components/product-select';

import './lib/bootstrap-filestyle.min';
import './lib/jquery.scrollbox.min';

import './components/block-cart';
import $ from 'jquery';
/* eslint-enable */

// "inherit" EventEmitter
// eslint-disable-next-line
for (const i in EventEmitter.prototype) {
  prestashop[i] = EventEmitter.prototype[i];
}

$(document).ready(() => {
  $('body').on('click', '[data-button-action="add-to-cart"]', (event) => {
    $(event.target).addClass('loading');
  })
  prestashop.on('updatedCart',function (event) {
    $('[data-button-action="add-to-cart"').removeClass('loading');
  })
  const dropDownEl = $('.js-dropdown');
  const form = new Form();
  //const topMenuEl = $('.js-top-menu ul[data-depth="0"]');
  const dropDown = new DropDown(dropDownEl);
  //const topMenu = new TopMenu(topMenuEl);
  const productMinitature = new ProductMinitature();
  const productSelect = new ProductSelect();
  dropDown.init();
  form.init();
  //topMenu.init();
  productMinitature.init();
  productSelect.init();

  $('.carousel[data-touch="true"]').swipe({
    swipe(event, direction) {
      if (direction === 'left') {
        $(this).carousel('next');
      }
      if (direction === 'right') {
        $(this).carousel('prev');
      }
    },
    allowPageScroll: 'vertical',
  });
  //specificPriceCountdown();
  $('input.form-control').each(function(){
    $(this)
        .focus(function(e){
            $(this).parents('.form-group').find('.form-control-label').addClass('has-value');
        })
        .focusout(function(e){
            if(!$(this).val()){
                $(this).parents('.form-group').find('.form-control-label').removeClass('has-value');
            }
            
        });
  })

  prestashop.on('updatedAddressForm', function (event) {
      $('input.form-control').each(function(){
          $(this)
              .focus(function(e){
                  $(this).parents('.form-group').find('.form-control-label').addClass('has-value');
              })
              .focusout(function(e){
                  if(!$(this).val()){
                      $(this).parents('.form-group').find('.form-control-label').removeClass('has-value');
                  }
                  
              });
      })
  });

  $('#menu-icon') .click(function(){ 
  $(this).toggleClass('open-menu'); 
  var hClass = $(this).hasClass('open-menu');
  if(hClass){
    $(window).resize(function(){
      if($(window).width() < 1024)   
      {
        $(this).parents('body').css( 'overflow','hidden');
      }
    });
    
    $(this).parents('body') .find( '#mobile_menu_wrapper' ) .addClass('box-menu');
  }
  else
  {
    $(this).parents('body').css( 'overflow','visible');
    $(this).parents('body') .find( '#mobile_menu_wrapper' ) .removeClass('box-menu');
    
  }
  });	  
  $('.menu-close') .click(function(){
  $(this).parents('body').css( 'overflow','visible');
  $(this).parents('body') .find( '#mobile_menu_wrapper' ) .removeClass('box-menu');
  $(this).parents('body').find( '#menu-icon' ).removeClass('open-menu');
  });	
  if($('#header').length > 0){
  var headerSpaceH = $('#header .sticky-inner').outerHeight(true);
  $('#header .sticky-inner').before('<div class="headerSpace unvisible" style="height: '+headerSpaceH+'px;" ></div>'); 
  if($('.page-index').length > 0 && $('.sticky-inner.absolute-header').length > 0){
    $('.headerSpace').remove();
  } 	
  }	
  $(window).scroll(function() {
  var headerSpaceH = $('#header').outerHeight();
  var screenWidth = $(window).width();

  if ($(this).scrollTop() > headerSpaceH && screenWidth >= 1024 ){  
      $(".use-sticky").find(".sticky-inner").addClass("scroll-menu"); 
      $('.headerSpace').removeClass("unvisible");
  }
  else{
    $(".use-sticky").find(".sticky-inner").removeClass("scroll-menu");
    $(".headerSpace").addClass("unvisible");
  }
  });	
  $(".back-top").hide();
  $(window).scroll(function () {
  if ($(this).scrollTop() > 150) {
    $('.back-top').fadeIn();
  } else {
    $('.back-top').fadeOut();
  }
  });
  $('.back-top').click(function () {
  $('body,html').animate({
    scrollTop: 0
  }, 1000);
  return false; 
  });
});

function specificPriceCountdown(){
	$( ".specific-prices-timer" ).each(function( index ) {
		
		var date_y = $(this).attr('data-date-y');
		var date_m = $(this).attr('data-date-m');
		var date_d = $(this).attr('data-date-d');
		var date_h = $(this).attr('data-date-h');
		var date_mi= $(this).attr('data-date-mi');
		var date_s = $(this).attr('data-date-s');

		$(this).countdown({
			until: new Date(date_y,date_m-1,date_d,date_h,date_mi,date_s),
			labels: ['Years', 'Months', 'Weeks', vectheme.cd_days_text, vectheme.cd_hours_text, vectheme.cd_mins_text, vectheme.cd_secs_text],
			labels1: ['Year', 'Month', 'Week', vectheme.cd_day_text, vectheme.cd_hour_text, vectheme.cd_min_text, vectheme.cd_sec_text],
		});

	});
	var end_date = $('.block-countdown').data('end-date');
	$('.block-countdown').countdown({
		until: new Date(end_date),
		labels: ['Years', 'Months', 'Weeks', vectheme.cd_days_text, vectheme.cd_hours_text, vectheme.cd_mins_text, vectheme.cd_secs_text],
		labels1: ['Year', 'Month', 'Week', vectheme.cd_day_text, vectheme.cd_hour_text, vectheme.cd_min_text, vectheme.cd_sec_text],
	})
}
