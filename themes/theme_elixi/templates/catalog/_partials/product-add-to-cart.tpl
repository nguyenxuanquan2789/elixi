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
<div class="product-add-to-cart js-product-add-to-cart">
  {block name='product_quantities'}
    {if $product.show_quantities}
      <div class="product-quantities">
        <label class="label in-stock">{l s='In stock' d='Shop.Theme.Catalog'}</label>:
        <span data-stock="{$product.quantity}" data-allow-oosp="{$product.allow_oosp}">{$product.quantity}
          {$product.quantity_label}</span>
      </div>
    {/if}
  {/block}

  {block name='product_availability_date'}
    {if $product.availability_date}
      <div class="product-availability-date">
        <label>{l s='Availability date:' d='Shop.Theme.Catalog'} </label>
        <span>{$product.availability_date}</span>
      </div>
    {/if}
  {/block}

  {block name='product_out_of_stock'}
    <div class="product-out-of-stock">
      {hook h='actionProductOutOfStock' product=$product}
    </div>
  {/block}
  {if !$configuration.is_catalog}
    {block name='product_quantity'}
      <div class="product-quantity clearfix">
        <div class="qty">
          <input type="number" name="qty" id="quantity_wanted" inputmode="numeric" pattern="[0-9]*"
            {if $product.quantity_wanted} value="{$product.quantity_wanted}" min="{$product.minimal_quantity}" 
            {else}
            value="1" min="1" {/if} class="input-group" aria-label="{l s='Quantity' d='Shop.Theme.Actions'}">
        </div>

        <div class="add">
          <button class="btn btn-primary add-to-cart" data-button-action="add-to-cart" type="submit"
            {if !$product.add_to_cart_url} disabled {/if}>
            {l s='Add to cart' d='Shop.Theme.Actions'}
          </button>
        </div>
        <div class="box_button">
          {hook h='displayAfterButtonCart'}
        </div>
        <div class="buy-cart">
          <button class="btn btn-primary buy-now" data-button-action="buy-now" type="submit" {if !$product.add_to_cart_url}
            disabled {/if}>
            {l s='Buy now' d='Shop.Theme.Actions'}
          </button>
        </div>
        {hook h='displayProductActions' product=$product}
      </div>
    {/block}

    {block name='product_availability'}
      <span id="product-availability" class="js-product-availability">
        {if $product.show_availability && $product.availability_message}
          {if $product.availability == 'available'}
            <i class="fa fa-check-square-o"></i>
          {elseif $product.availability == 'last_remaining_items'}
            <i class="fa fa-warning"></i>
          {else}
            <i class="fa fa-ban"></i>
          {/if}
          {$product.availability_message}
        {/if}
      </span>
    {/block}

    {block name='product_minimal_quantity'}
      <p class="product-minimal-quantity js-product-minimal-quantity">
        {if $product.minimal_quantity > 1}
          {l
                s='The minimum purchase order quantity for the product is %quantity%.'
                d='Shop.Theme.Checkout'
                sprintf=['%quantity%' => $product.minimal_quantity]
                }
        {/if}
      </p>
    {/block}
  {/if}
</div>