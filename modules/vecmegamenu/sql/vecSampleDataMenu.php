<?php
/**
* 2007-2014 PrestaShop
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
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class VecSampleDataMenu
{
	public function initData()
	{
		$return = true;
		$languages = Language::getLanguages(true);
		$id_shop = Configuration::get('PS_SHOP_DEFAULT');
		
		$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_item` (`id_vecmegamenu_item`, `type_link`, `link`, `type_icon`, `icon`, `icon_class`, `submenu_type`, `item_class`, `new_window`, `position`, `active`, `subtitle_bg_color`, `subtitle_color`, `subtitle_fontsize`, `subtitle_lineheight`) VALUES 		
		(1, 0, "PAGhomepage", 0, "", "", 2, "", 0, 0, 1, "", 0, 0, 0),		
		(2, 2, "", 0, "", "", 0, "", 0, 3, 1, "", 0, 0, 0)		
		;');
		foreach($languages as $language){
			$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_item_lang` (`id_vecmegamenu_item`, `id_lang`, `title`, `custom_link`, `subtitle`) VALUES 		
			(1, '.$language['id_lang'].', "Home", "", ""),		
			(2, '.$language['id_lang'].', "Mega item", "", "")		
			;');
		};
		$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_item_shop` (`id_vecmegamenu_item`, `id_shop`) VALUES 		
		(1, 1),		
		(2, 1)		
		;');

		$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_submenu` (`id_submenu`, `id_vecmegamenu_item`, `submenu_class`, `active`, `submenu_width`, `submenu_bg`, `submenu_bg_color`, `submenu_bg_image`, `submenu_bg_repeat`, `submenu_bg_position`) VALUES 		
		(1, 1, "", 1, "", "", "", "", "", ""),		
		(2, 2, "", 1, "900px", 1, "", "", 1, 1)		
		;');

		$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_submenu_row` (`id_row`, `id_vecmegamenu_item`, `class`, `position`, `active`) VALUES 			
		(1, 2, "", 1, 1)		
		;');

		$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_submenu_column` (`id_vecmegamenu_submenu_column`, `id_row`, `width`, `class`, `type_link`, `link`, `position`, `active_mobile`, `active`) VALUES 		
		(1, 1, 12, "", 0, "PAGhomepage", 1, 1, 1)	
		;');
		foreach($languages as $language){
			$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_submenu_column_lang` (`id_vecmegamenu_submenu_column`, `title`, `custom_link`, `id_lang`) VALUES 		
			(1, "", "", '.$language['id_lang'].')	
			;');
		}
		$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_submenu_item` (`id_vecmegamenu_submenu_item`, `id_vecmegamenu_submenu_column`, `type_link`, `category_tree`, `ps_link`, `id_product`, `id_manufacturer`, `position`, `active_mobile`, `active`) VALUES 		
		(1, 1, 6, "", "PAGhomepage", 0, 2, 1, 1, 1)		
		;');
		foreach($languages as $language){
			$return &= Db::getInstance()->Execute('INSERT IGNORE INTO `'._DB_PREFIX_.'vecmegamenu_submenu_item_lang` (`id_vecmegamenu_submenu_item`, `id_lang`, `customlink_title`, `customlink_link`, `htmlcontent`, `image`, `image_link`) VALUES 		
			(1, 1, "", "", \'<div class="custom_menu" style="line-height: 1.71428571429;">
			<div class="row">
			<div class="col-lg-6 col-md-12">
			<div class="menu_block">
			<h6 class="custom_txt1">Megamenu</h6>
			<p>Our built-in mega menu is the perfect choice for large menus. You can set up columns and rows, use icons and images easily.</p>
			</div>
			</div>
			<div class="col-lg-6 col-md-12">
			<div class="menu_block">
			<h6 class="custom_txt1">Simple Style</h6>
			<p>Our team created convenient functionality for managing menu according to your desire.</p>
			</div>
			</div>
			</div>
			</div>\', "", "")	
			;');
		};
		
		return $return;
	}
}
?>