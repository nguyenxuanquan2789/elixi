{**
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
 *}

{capture assign="productClasses"}{if !empty($productClass)}{$productClass}{else}col-xs-6 col-xl-4{/if}{/capture}

<div class="products{if !empty($cssClass)} {$cssClass}{/if}">
    {foreach from=$products item="product" key="position"}
        {if isset($vectheme.shop_view) && $vectheme.shop_view == 'list'}
        {include file="catalog/_partials/miniatures/product-list-view.tpl" product=$product position=$position productClasses=$productClasses}
        {else}
        <div class="column-product {if $vectheme.category_column == 1}column_2 col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-6 {else if $vectheme.category_column == 2}column_3 col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4{else if $vectheme.category_column == 3 }column_4 col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-3{else if $vectheme.category_column == 4 }column_5 col-xs-12 col-sm-6 col-md-4 col-lg-4{else if $vectheme.category_column == 5 }column_6 col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-2{/if}">   
            {include file="catalog/_partials/miniatures/product.tpl" product=$product position=$position productClasses=$productClasses}    
        </div> 
       
        {/if}
    {/foreach}
</div>
