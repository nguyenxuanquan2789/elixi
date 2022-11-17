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
<div id="quickview-modal-{$product.id}-{$product.id_product_attribute}" class="modal quickview fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered animationShowPopup animated" role="document">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
       <i class="vecicon-cross"></i>
       </button>
     </div>
     <div class="modal-body">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          {block name='product_cover_thumbnails'}
            <div class="images-container js-images-container">
            {block name='product_cover'}
              <div class="product-images-cover">
                {if $product.default_image}
                  <div class="product-images slick-block items-desktop-1 items-tablet-1 items-mobile-1">
                      {foreach from=$product.images item=image}
                        <div class="cover-item">
                            <img class="" src="{$image.bySize.large_default.url}" alt="{$image.legend}" title="{$image.legend}" itemprop="image">
                        </div>
                      {/foreach}
                  </div>
                {else}
                  <img
                    class="img-fluid"
                    src="{$urls.no_picture_image.bySize.medium_default.url}"
                    loading="lazy"
                    width="{$urls.no_picture_image.bySize.medium_default.width}"
                    height="{$urls.no_picture_image.bySize.medium_default.height}"
                  >
                {/if}
              </div>
            {/block}

            {block name='product_images'}
              <div class="product-images-thumb">
                <div class="product-thumbs js-qv-product-images slick-block items-desktop-{$vectheme.thumbnail_items} items-tablet-4 items-mobile-3" data-item="{$vectheme.thumbnail_items}">
                  {foreach from=$product.images item=image}
                    <div class="thumb-item js-thumb-container">
                      <img
                        class="thumb js-thumb {if $image.id_image == $product.default_image.id_image} selected js-thumb-selected {/if}"
                        data-image-medium-src="{$image.bySize.medium_default.url}"
                        data-image-large-src="{$image.bySize.large_default.url}"
                        src="{$image.bySize.small_default.url}"
                        {if !empty($image.legend)}
                          alt="{$image.legend}"
                          title="{$image.legend}"
                        {else}
                          alt="{$product.name}"
                        {/if}
                      >
                    </div>
                  {/foreach}
                </div>
              </div>
            {/block}
          </div>
          {/block}
          <div class="product-additional-info js-product-additional-info">
            {hook h='displayProductAdditionalInfo' product=$product}
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <h1 class="h1 namne_details h1">{$product.name}</h1>
          {hook h="displayReviewsProduct"}
          {block name='product_prices'}
            {include file='catalog/_partials/product-prices.tpl'}
          {/block}
          {block name='product_description_short'}
            <div class="product-description">{$product.description_short nofilter}</div>
          {/block}
          {block name='product_buy'}
            <div class="product-actions js-product-actions">
              <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                <input type="hidden" name="token" value="{$static_token}">
                <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id" class="js-product-customization-id">
                {block name='product_variants'}
                  {include file='catalog/_partials/product-variants.tpl'}
                {/block}

                {block name='product_add_to_cart'}
                  {include file='catalog/_partials/product-add-to-cart.tpl'}
                {/block}

                {* Input to refresh product HTML removed, block kept for compatibility with themes *}
                {block name='product_refresh'}{/block}
            </form>
          </div>
        {/block}
        </div>
      </div>
     </div>
   </div>
 </div>
</div>
