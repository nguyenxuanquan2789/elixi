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
{if isset($currentSortUrl)}
<div class="products-sort-nb-dropdown products-nb-per-page dropdown">
    <a class="select-title expand-more form-control" rel="nofollow" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
        {$listing.products|count}
        <i class="vecicon-angle_down float-xs-right"></i>
    </a>

    <div class="dropdown-menu">
        <a
                rel="nofollow"
                href="{$currentSortUrl}&resultsPerPage=12"
                class="select-list dropdown-item {['js-search-link' => true]|classnames}"
        >
            12
        </a>
        <a
                rel="nofollow"
                href="{$currentSortUrl}&resultsPerPage=24"
                class="select-list dropdown-item {['js-search-link' => true]|classnames}"
        >
            24
        </a>
        <a
                rel="nofollow"
                href="{$currentSortUrl}&resultsPerPage=48"
                class="select-list dropdown-item {['js-search-link' => true]|classnames}"
        >
            48
        </a>
        <a
                rel="nofollow"
                href="{$currentSortUrl}&resultsPerPage=96"
                class="select-list dropdown-item {['js-search-link' => true]|classnames}"
        >
            96
        </a>
        <a
                rel="nofollow"
                href="{$currentSortUrl}&resultsPerPage=9999"
                class="select-list dropdown-item {['js-search-link' => true]|classnames}"
        >
            {l s='All' d='Shop.Theme.Global'}
        </a>
     </div>
</div>
{/if}
<div class="products-sort-order dropdown">
  <button
    class="btn-unstyle select-title"
    rel="nofollow"
    data-toggle="dropdown"
    aria-label="{l s='Sort by selection' d='Shop.Theme.Global'}"
    aria-haspopup="true"
    aria-expanded="false">
    {if $listing.sort_selected}{$listing.sort_selected}{else}{l s='Select' d='Shop.Theme.Actions'}{/if}
    <i class="vecicon-angle_down float-xs-right"></i>
  </button>
  <div class="dropdown-menu">
    {foreach from=$listing.sort_orders item=sort_order}
      {if $sort_order.current}
          {assign var="currentSortUrl" value=$sort_order.url|regex_replace:"/&resultsPerPage=\d+$/":""}
      {/if}
      <a
        rel="nofollow"
        href="{$sort_order.url}"
        class="select-list {['current' => $sort_order.current, 'js-search-link' => true]|classnames}"
      >
        {$sort_order.label}
      </a>
    {/foreach}
  </div>
</div>

