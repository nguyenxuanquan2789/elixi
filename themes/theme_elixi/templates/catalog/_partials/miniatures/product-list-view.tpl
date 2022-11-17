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
 {block name='product_miniature_item'}
	
	<article class="thumbnail-container product-miniature-list product-miniature js-product-miniature item_in" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
		<div class="img-block">
		  {block name='product_thumbnail'}
			<a href="{$product.url}" class="thumbnail product-thumbnail">
			  <img class="first-image lazyload"
				src = "{$product.cover.bySize.home_default.url}" 
				alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
				data-full-size-image-url = "{$product.cover.large.url}"
			  >
			  {if $vectheme.rotator}
				{foreach from=$product.images item=image}
					{if !$image.cover}
						<img
							src="{$image.bySize.cart_default.url}"
							data-src="{$image.bySize.cart_default.url}"
							width="{$image.bySize.cart_default.width}"
							height="{$image.bySize.cart_default.height}"
							alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if} 2"
							class="lazy-product-image product-thumbnail-rotator"  loading="lazy"
						>
						{break}
					{/if}
				{/foreach}
		   	  {/if}
			</a>
		  {/block}
		</div>
		<div class="product-content"> 
			{if isset($product.id_manufacturer)}
			 <div class="manufacturer"><a href="{url entity='manufacturer' id=$product.id_manufacturer }">{Manufacturer::getnamebyid($product.id_manufacturer)}</a></div>
			{/if}
			{block name='product_name'}
			  <h3 itemprop="name"><a href="{$product.url}" class="product_name" title="{$product.name}">{$product.name}</a></h3> 
			{/block}
			{block name='product_reviews'}
				<div class="hook-reviews">
				{hook h='displayProductListReviews' product=$product}
				</div>
			{/block}
			
			<div class="availability"> 
			{if $product.show_availability }
				{if $product.quantity > 0}
				<div class="availability-list in-stock">{l s='Availability' d='Shop.Theme.Actions'}: <span>{$product.quantity} {l s='In Stock' d='Shop.Theme.Actions'}</span></div>

				{else}

				<div class="availability-list out-of-stock">{l s='Availability' d='Shop.Theme.Actions'}: <span>{l s='Out of stock' d='Shop.Theme.Actions'}</span></div> 
				{/if}
			{/if}
			</div>
			{block name='product_description_short'}
				<div class="product-desc" itemprop="description">{$product.description_short nofilter}</div>
			{/block}
		
			<div class="variant-links">
			{block name='product_variants'}
			{if $product.main_variants}
			{include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
			{/if}
			{/block} 
			</div>
			<div class="col-buy">
			  {block name='product_price_and_shipping'}
				{if $product.show_price}
				  <div class="product-price-and-shipping">
					{if $product.has_discount}
					  {hook h='displayProductPriceBlock' product=$product type="old_price"}
  
					  <span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
					  <span class="regular-price">{$product.regular_price}</span>
					{/if}
  
					{hook h='displayProductPriceBlock' product=$product type="before_price"}
  
					<span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
					<span itemprop="price" class="price {if $product.has_discount}price-sale{/if}">{$product.price}</span>
					{hook h='displayProductPriceBlock' product=$product type='unit_price'}
  
					{hook h='displayProductPriceBlock' product=$product type='weight'}
				  </div>
				{/if}
			  {/block} 
			  <div class="product-cart">
				{include file='catalog/_partials/miniatures/customize/button-cart.tpl' product=$product}
			  </div>
			  <div class="add-links">
				{block name='quick_view'}	
				<a class="quick-view" href="#" data-link-action="quickview" title="{l s='Quick view' d='Shop.Theme.Actions'}">
				<span>{l s='Quick view' d='Shop.Theme.Actions'}</span>
				</a>
				{/block}					
				{hook h='displayProductListFunctionalButtons' product=$product}
				{hook h='displayWishlistButton' product=$product}
			   </div>
			</div>
			
		</div>
	</article>
{/block}