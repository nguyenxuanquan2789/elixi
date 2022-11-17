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
<div id="js-product-list-header">
    {if $listing.pagination.items_shown_from == 1}
        <div class="block-category {if $category.description && $vectheme.category_description != 'hide' && !$vectheme.category_description_bottom}desc-cate{/if}">
             {if $category.description && $vectheme.category_description != 'hide' && !$vectheme.category_description_bottom}
            <h1 class="h1">{$category.name}</h1>
            {/if}
            <div class="block-category-inner">
                {if !empty($category.image.large.url) && isset($vectheme.category_thumbnail) && $vectheme.category_thumbnail}
                    <div class="category-cover">
                        <img src="{$category.image.large.url}" alt="{if !empty($category.image.legend)}{$category.image.legend}{else}{$category.name}{/if}" loading="lazy">
                    </div>
                {/if}
                {if $category.description && $vectheme.category_description != 'hide' && !$vectheme.category_description_bottom}
                <div id="category-description" class="text-muted {if $vectheme.category_description == 'part'}expand-content{/if}">
                    {$category.description nofilter}
                {if $vectheme.category_description == 'part'}
                    <div class="block-expand-overlay">
                        <a class="block-expand btn-more">{l s='Show more' d='Shop.Theme.Vec'}</a>
                        <a class="block-expand btn-less">{l s='Show less' d='Shop.Theme.Vec'}</a>
                    </div>
                {/if}
                </div>
                {/if}
            </div>
        </div>
    {/if}
    {block name='subcategory_list'}
        {if isset($subcategories) && $subcategories|@count > 0 && $vectheme.category_sub}
          {include file='catalog/_partials/subcategories.tpl' subcategories=$subcategories}
        {/if}
    {/block}
</div>
