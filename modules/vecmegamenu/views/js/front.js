/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also avaiposle through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$(document).ready(function()
{	
	activeMobileMenu();
	if($(window).width() < 1025)
	{
		$('.vec-menu-horizontal').addClass('vec-mobile-menu');
		$('#_mobile_megamenu img').parent('a').addClass("img_banner"); 
		$('.vec-mobile-menu').removeClass('container');	
	}
	else
	{
		$('.vec-menu-horizontal').removeClass('vec-mobile-menu');
		$('.vec-menu-horizontal .menu-dropdown').show(); 		
	}	
	$(window).resize(function(){
		if($(window).width() < 1025)
		{
			$('.vec-menu-horizontal').addClass('vec-mobile-menu');
			$('#_mobile_megamenu img').parent('a').addClass("img_banner"); 
			$('.vec-mobile-menu').removeClass('container');	
		}
		else
		{
			$('.vec-menu-horizontal').removeClass('vec-mobile-menu');
			$('.vec-menu-horizontal .menu-dropdown').show(); 		
		}	
	});

	vecInitHorizontalMegamenu(); 

	$('#_desktop_megamenu img').parent('a').addClass("img_desktop"); 
	$('#_mobile_megamenu img').parent('a').addClass("img_banner");

	// $('li.menu-item').hover(function(e) {
	// 	$('li.menu-item').removeClass('menu-action-hover');
	// 	$(this).addClass('menu-action-hover');
	// }, function(e) {
	// 	setTimeout(function() {
	// 		$('li.menu-item').removeClass('menu-action-hover');
	// 	}, 500);
	// })

	$('li.menu-item').mouseenter(function(){
		$(this).addClass('menu-action-hover');
	})
	$('li.menu-item').mouseleave(function(){
		setTimeout(function() {
			$('li.menu-item').removeClass('menu-action-hover');
		}, 500);
	})
});

function vecInitHorizontalMegamenu() {
    var $menuHorizontal = $('.vec-menu-horizontal');
    var $list = $menuHorizontal.find('li.hasChild');

    $list.hover(function() {
        setOffset($(this))
    });
    var setOffset = function($li) {
        var $dropdown = $li.find('.menu-dropdown');
		// if($dropdown.hasClass('menu-flyout')){
		// 	return;
		// }
    	$dropdown.css({
            'right': '',
            'left': '',
            'width': $dropdown.data('width')
        });
        
        var dropdownWidth = $dropdown.outerWidth();
        var dropdownOffset = $dropdown.offset();
        var toRight;
        var viewportWidth;
        var dropdownOffsetRight;
        var $window = $(window);
        var $body = $('body');
        var screenWidth = $window.width();
        if (!dropdownWidth || !dropdownOffset) {
            return
        }
        if (dropdownWidth > screenWidth) {
            dropdownWidth = screenWidth
        }
        $dropdown.css({
            'width': dropdownWidth
        });
        if ($li.hasClass('hasChild') && dropdownWidth > 1200) {
            viewportWidth = $window.width();
            if (dropdownOffset.left + dropdownWidth >= viewportWidth) { 
				toRight = dropdownOffset.left + dropdownWidth - viewportWidth;
				$dropdown.css({
					left: -toRight
				})
			}
            $li.addClass('menu_initialized')
        } else if ($li.hasClass('dropdown-mega')) {
    		viewportWidth = $('#header .elementor-container').innerWidth();
            dropdownOffsetRight = viewportWidth - dropdownOffset.left - dropdownWidth;
            var extraSpace = 0;
            var containerOffset = ($window.width() - viewportWidth) / 2;
            var dropdownOffsetLeft;
            if (dropdownWidth >= viewportWidth) {
                extraSpace = (viewportWidth - dropdownWidth) / 2
            }
            dropdownOffsetLeft = dropdownOffset.left - containerOffset;
			if (dropdownOffsetLeft + dropdownWidth >= viewportWidth) {
				toRight = dropdownOffsetLeft + dropdownWidth - viewportWidth;
				$dropdown.css({
					left: -toRight - extraSpace -10
				})
			}
            
            $li.addClass('menu_initialized')
        } else {
            $li.addClass('menu_initialized')
        }
    };
    $list.each(function() {
        setOffset($(this))
    })
}
function activeMobileMenu(){
	$('.vec-menu-horizontal .menu-item > .icon-drop-mobile').on('click', function(){
		if($(this).hasClass('open_menu')) {
			$('.vec-menu-horizontal .menu-item > .icon-drop-mobile').removeClass( 'open_menu' );   
			$(this).removeClass( 'open_menu' );  
			$(this).next('.menu-dropdown').slideUp();
			$('.vec-menu-horizontal .menu-item > .icon-drop-mobile').next('.vec-menu-horizontal .menu-dropdown').slideUp();
		}
		else {	
			$('.vec-menu-horizontal .menu-item > .icon-drop-mobile').removeClass( 'open_menu' ); 
			$('.vec-menu-horizontal .menu-item > .icon-drop-mobile').next('.vec-menu-horizontal .menu-dropdown').slideUp();
			$(this).addClass( 'open_menu' );   
			$(this).next('.menu-dropdown').slideDown();
	
		}
		
	});
	$('.vec-menu-horizontal .cat-drop-menu .icon-drop-mobile').on('click', function(){
		if($(this).hasClass('open_menu')) {
			$(this).parent().siblings().find('.icon-drop-mobile').removeClass( 'open_menu' );   
			$(this).removeClass( 'open_menu' );  
			$(this).next('.vec-menu-horizontal .cat-drop-menu').slideUp();
			$(this).parent().siblings().find('.cat-drop-menu').slideUp();
		}
		else {	
			$(this).parent().siblings().find('.icon-drop-mobile').removeClass( 'open_menu' );  
			$(this).parent().siblings().find('.cat-drop-menu').slideUp();
			$(this).addClass( 'open_menu' );   
			$(this).next('.vec-menu-horizontal .cat-drop-menu').slideDown();
	
		}
		
	});
	$('.vec-menu-horizontal .vec-menu-col > .icon-drop-mobile').on('click', function(){
		if($(this).hasClass('open_menu')) {
			$('.vec-menu-horizontal .vec-menu-col > .icon-drop-mobile').removeClass( 'open_menu' );   
			$(this).removeClass( 'open_menu' );  
			$(this).next('.vec-menu-horizontal ul.ul-column').slideUp();
			$('.vec-menu-horizontal .vec-menu-col > .icon-drop-mobile').next('.vec-menu-horizontal ul.ul-column').slideUp();
		} 
		else {	
			$('.vec-menu-horizontal .vec-menu-col > .icon-drop-mobile').removeClass( 'open_menu' ); 
			$('.vec-menu-horizontal .vec-menu-col > .icon-drop-mobile').next('.vec-menu-horizontal ul.ul-column').slideUp();
			$(this).addClass( 'open_menu' );   
			$(this).next('.vec-menu-horizontal ul.ul-column').slideDown();
	
		}
	
	});
	$('.vec-menu-horizontal .submenu-item  > .icon-drop-mobile').on('click', function(){
		if($(this).hasClass('open_menu')) {
			$('.vec-menu-horizontal .submenu-item  > .icon-drop-mobile').removeClass( 'open_menu' );   
			$(this).removeClass( 'open_menu' );  
			$(this).next('.vec-menu-horizontal ul.category-sub-menu').slideUp();
			$('.vec-menu-horizontal .submenu-item  > .icon-drop-mobile').next('.vec-menu-horizontal ul.category-sub-menu').slideUp();
		}
		else {	
			$('.vec-menu-horizontal .submenu-item  > .icon-drop-mobile').removeClass( 'open_menu' ); 
			$('.vec-menu-horizontal .submenu-item  > .icon-drop-mobile').next('.vec-menu-horizontal ul.category-sub-menu').slideUp();
			$(this).addClass( 'open_menu' );   
			$(this).next('.vec-menu-horizontal ul.category-sub-menu').slideDown();
	
		}
	});

	
}
