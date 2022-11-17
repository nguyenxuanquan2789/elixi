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
<div class="modal js-product-images-modal" id="product-modal">
  <div class="modal-dialog animationShowPopup animated" role="document">
    <div class="modal-content">
      <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
        <i class="vecicon-cross"></i>
      </button>
      <div class="modal-body">
        {assign var=imagesCount value=$product.images|count}
        <figure>
          {if $product.default_image}
            <img
              class="js-modal-product-cover product-cover-modal"
              width="{$product.default_image.bySize.large_default.width}"
              src="{$product.default_image.bySize.large_default.url}"
              {if !empty($product.default_image.legend)}
                alt="{$product.default_image.legend}"
                title="{$product.default_image.legend}"
              {else}
                alt="{$product.name}"
              {/if}
              height="{$product.default_image.bySize.large_default.height}"
            >
          {else}
            <img src="{$urls.no_picture_image.bySize.large_default.url}" loading="lazy" width="{$urls.no_picture_image.bySize.large_default.width}" height="{$urls.no_picture_image.bySize.large_default.height}" />
          {/if}
        </figure>
        <div id="thumbnails" class="thumbnails js-thumbnails">
          {block name='product_images'}
              <div class="product-images-modal js-modal-product-images slick-block items-desktop-6 items-tablet-6 items-mobile-4" data-item="6">
                {foreach from=$product.images item=image}
                  <div class="thumb-container js-thumb-container">
                    <img
                      data-image-large-src="{$image.large.url}"
                      class="thumb js-modal-thumb"
                      src="{$image.medium.url}"
                      {if !empty($image.legend)}
                        alt="{$image.legend}"
                        title="{$image.legend}"
                      {else}
                        alt="{$product.name}"
                      {/if}
                      width="{$image.medium.width}"
                      height="148"
                    >
                  </div>
                {/foreach}
              </div>
          {/block}
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
