{**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{extends file='customer/page.tpl'}
	
{block name='page_content_title'}
	<h4>
	  	{l s='Wishlist' mod='vecwishlist'}
	</h4>
{/block}

{block name='page_content_content'}
    {if isset($wlProducts) && $wlProducts}
	 	
	 	<div id="my_wishlist">
	 		<div id="js-wishlist-table" class="wishlist-table-wrapper">
				<div class="wishlist-table-actions">
					<a href="javascript:void(0)" class="js-wishlist-remove-all">
						<i class="vecicon-cross"></i> {l s='Remove all products' mod='vecwishlist'}
					</a>
				</div>
				<table class="shop_table_responsive shop_table">
					<thead>
						<tr>
							{if !$readOnly}<th class="product-remove"></th>{/if}
							<th class="product-thumbnail"></th>
							<th class="product-name">{l s='Name' mod='vecwishlist'}</th>
							<th class="product-w-price">{l s='Price' mod='vecwishlist'}</th>
							<th class="product-button"></th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$wlProducts item="product"}
							<tr class="js-wishlist-{$product.id_product}-{$product.id_product_attribute}">
								{if !$readOnly}
									<td class="product-remove">
										<a href="javascript:void(0)" class="js-wishlist-remove btn-action-wishlist-remove js-wishlist-remove-{$product.id_product|intval}-{$product.id_product_attribute|intval}"
											data-id-product="{$product.id_product|intval}"
											data-id-product-attribute="{$product.id_product_attribute|intval}">
											<i class="vecicon-cross"></i>
										</a>
									</td>
								{/if}
								<td class="product-thumbnail">
									<a class="product-image" href="{$product.url}" title="{$product.name}">
									  <div class="img-placeholder">
										{if $product.default_image}
											{$image = $product.default_image}
										{else}
											{$image = $urls.no_picture_image}
										{/if}
										<img
											class="lazy-load" 
											data-src="{$image.bySize.home_default.url}"
											src="{$image.bySize.home_default.url}" 
											alt="{if !empty($image.legend)}{$image.legend}{else}{$product.name}{/if}"
											title="{if !empty($image.legend)}{$image.legend}{else}{$product.name}{/if}" 
											width="{$image.bySize.home_default.width}"
											height="{$image.bySize.home_default.height}"
										> 
									  </div>
									</a>  
								</td>
								<td class="product-name">
									<a class="product-title" href="{$product.url}">{$product.name}</a>
									<div class="text-muted">
									{foreach from=$product.attributes item="attribute"}
										<div><small class="label">{$attribute.group}: </small><small>{$attribute.name}</small></div>
									{/foreach}
									</div>
								</td>
								<td class="product-price price">
									{if $product.show_price}
										{hook h='displayProductPriceBlock' product=$product type="before_price"}
										{if $product.has_discount}
										  {hook h='displayProductPriceBlock' product=$product type="old_price"}
										  <span class="regular-price">{$product.regular_price}</span>&nbsp;&nbsp;
										{/if}
										<span class="price">{$product.price}</span>
										{hook h='displayProductPriceBlock' product=$product type='unit_price'}
										{hook h='displayProductPriceBlock' product=$product type='weight'}
									{/if}
								</td>	
								<td class="product-button">
									<div data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
										<form action="{$urls.pages.cart}" method="post">
											{if !$configuration.is_catalog}
												{if ($product.quantity > 0 || $product.allow_oosp)}
													<input type="hidden" name="token" value="{$static_token}">
													<input type="hidden" name="id_product" value="{$product.id_product}">
													<input type="hidden" name="id_product_attribute" value="{$product.id_product_attribute}">
                                                	<input type="hidden" name="qty" value="{$product.minimal_quantity}">
													<button class="btn btn-primary add-to-cart" data-button-action="add-to-cart" 
														title="{l s='Add to cart' d='Shop.Theme.Actions'}">
														{l s='Add to cart' d='Shop.Theme.Actions'}
													</button>
											  	{else}
													<button class="btn-primary add-to-cart out-of-stock" title="{l s='Out of stock' mod='vecwishlist'}">{l s='Out of stock' mod='vecwishlist'}</button>
											  	{/if}
											{/if}
										</form>
									</div>	
								</td>							
							</tr>
						{/foreach}
					</tbody>
				</table>
				{if !$readOnly}
					<h5>{l s='Share your wishlist' mod='vecwishlist'}</h5>
					<div class="input-group">
						<input class="form-control js-to-copy" readonly="readonly" type="url" value="{url entity='module' name='vecwishlist' relative_protocol=false controller='view' params=['token' => $token]}">
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" id="wishlist-copy-btn" data-text-copied="{l s='Copied' mod='vecwishlist'}" data-text-copy="{l s='Copy' mod='vecwishlist'}">{l s='Copy' mod='vecwishlist'}</button>
						</span>
					</div>
					{hook h='displayWishListShareButtons'}
				{/if}
			</div>
	 	</div>
		<div id="js-wishlist-warning" style="display:none;" class="empty-products">
			<p class="empty-title empty-title-wishlist">
				{l s='Wishlist is empty.' mod='vecwishlist'}				
			</p>
			<div class="empty-text">
				{l s='No products added in the wishlist list. You must add some products to wishlist them.' mod='vecwishlist'}
			</div>
			<p class="return-to-home">
				<a href="{$urls.pages.index}" class="btn btn-primary">
					<i class="vecicon-arrow-left-solid"></i>
					{l s='Return to home' mod='vecwishlist'}
				</a>
			</p>
		</div>
    {else}
		<div class="empty-products">
			<p class="empty-title empty-title-wishlist">
				{l s='Wishlist list is empty.' mod='vecwishlist'}				
			</p>
			<div class="empty-text">
				{l s='No products added in the wishlist list. You must add some products to wishlist them.' mod='vecwishlist'}
			</div>
			<p class="return-to-home">
				<a href="{$urls.pages.index}" class="btn btn-primary">
					<i class="vecicon-arrow-left-solid"></i>
					{l s='Return to home' mod='vecwishlist'}
				</a>
			</p>
		</div>
    {/if}
{/block}


